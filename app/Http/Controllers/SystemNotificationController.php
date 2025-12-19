<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessGuardianNotification;
use App\Jobs\ProcessSystemNotification;
use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\City;
use App\Models\ComClass;
use App\Models\Country;
use App\Models\Employee;
use App\Models\Guardian;
use App\Models\NetworkAssociate;
use App\Models\NotificationLog;
use App\Models\Section;
use App\Models\State;
use App\Models\Student;
use App\Models\SystemNotification;
use App\Models\User;
use App\Notifications\SendNotification;
use App\Notifications\SendPushNotification;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SystemNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SystemNotification::with(['branch', 'country', 'state', 'createdBy']);
            if (! isSuperAdmin() && ! isHeadOfficeEmp() /*!auth()->user()->hasRole('manager-parent-relations')*/) {
                $data->where('branch_id', get_branch_id());
            }
            if ($request->notification_type) {
                $data = $data->where('notification_type', $request->notification_type);
            }
            if ($request->audience) {
                $data = $data->where('audience', $request->audience);
            }
            if ($request->branch_id) {
                $data = $data->whereHas('branch', function ($q) use ($request) {
                    $q->where('id', $request->branch_id);
                });
            }
            $data->get();
            // dd($data->toArray());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.system_notifications.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $branches = Branch::get();
        return view('settings.system_notifications.notifications', ['branches' => $branches]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get();
        $states = State::get();
        $cities = City::get();
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $branches = [];
            $classes = [];
            $sections = [];
            // dd($sections->toArray());
        } else {
            $branches = [];
            $classes = [];
            $sections = [];
            // dd($sections);
        }
        // ['active_class']['branch_class_sections']
        // dd($classes->toArray());
        return view('settings.system_notifications.add_new_notification', [
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'audience' => 'required',
            'notification_type' => 'required',
            // 'class_id' => 'required',
            // 'section_id' => 'required',
        ]);
        // dd($request->all());

        if ($request->notification_type == 'Push Notification' || $request->notification_type == 'SMS' || $request->notification_type == "SMS, Email, Push Notification") {
            $request->validate([
                'class_id' => 'required',
                'section_id' => 'required',
            ]);
            $class_id = BranchClassSection::where(['id' => $request->section_id])->first()->class_id;
            $section_id = BranchClassSection::where(['id' => $request->section_id])->first()->section_id;
            $request->merge(['class_id' => $class_id, 'section_id' => $section_id]);
        }


        if ($request->notification_type == 'Email') {
            $request->merge(['message' => $request->message_email]);
        }

        $notification = SystemNotification::create($request->all());
        if ($request->branch_id) {
            if ($request->audience == 'Parents') {
                $audience = Student::where('branch_id', $request->branch_id)
                    ->whereHas('branch', function ($q) use ($notification) {
                        $q->whereHas('branch_class_section', function ($q) use ($notification) {
                            $q->where(['branch_id' => $notification->branch_id, 'class_id' => $notification->class_id, 'section_id' => $notification->section_id]);
                        });
                    })
                    ->with(['sibling_info.family.parent'])->get()->toArray();
                $this->send_notification_to_parent($audience, $request->email_header, $request->message, $request->email_salutation, $notification, $request->notification_type);
            }

            if ($request->audience == 'Employees') {
                $audience = Employee::with('user')->where('branch_id', $request->branch_id)->get()->toArray();
                $this->send_notification_to_user($audience, $request->email_header, $request->message, $request->email_salutation, $notification, $request->notification_type, $mobile = true);
            }
        }
        if ($request->audience == 'NWA') {
            if (isset($request->branch_id)) {
                $audience = NetworkAssociate::with(['branches', 'user', 'contact_information'])->whereHas('branches', function ($q) use ($request) {
                    $q->where('branch_id', $request->branch_id);
                })->get()->toArray();
                $this->send_notification_to_user($audience, $request->email_header, $request->message, $request->email_salutation, $notification, $request->notification_type, $mobile = false);
            } else {
                $allBranches = Branch::with('contact_information')->whereHas('contact_information', function ($query) use ($request) {
                    $query->where('state_id', $request->state_id);
                })->get();

                foreach ($allBranches as $branch) {
                    $audience[] = NetworkAssociate::with(['branches', 'user', 'contact_information'])
                        ->whereHas('branches', function ($q) use ($branch) {
                            $q->where('branch_id', $branch->id);
                        })
                        ->whereHas('contact_information')
                        ->whereHas('user')
                        ->get()
                        ->toArray();

                    // Filter out empty arrays before sending notifications
                    $audience = array_filter($audience);
                }
                // dd($audience);
                foreach ($audience as $value) {
                    $this->send_notification_to_user($value, $request->email_header, $request->message, $request->email_salutation, $notification, $request->notification_type, $mobile = false);
                }
            }
        }

        return redirect()->route('system-notifications.index')
            ->with('success', 'System Notification created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemNotification  $systemNotification
     * @return \Illuminate\Http\Response
     */
    public function show(SystemNotification $systemNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemNotification  $systemNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemNotification $systemNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemNotification  $systemNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemNotification $systemNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemNotification  $systemNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemNotification $systemNotification)
    {
        //
    }

    public function system_notification_logs($id)
    {
        $notifications = NotificationLog::where('system_notification_id', $id)->with(['notification', 'guardian', 'employee'])->get();
        return view('settings.system_notifications.logs', ['notifications' => $notifications]);
    }

    private function send_notification_to_parent($audience, $header = null, $message, $salutation = null, $notification, $type)
    {
        foreach ($audience as $student) {
            if ($student['sibling_info'] != null && $student['sibling_info']['family'] != null && $student['sibling_info']['family']['parent'] != null) {
                $guardian_id = $student['sibling_info']['family']['parent']['id'];
                $guardian = Guardian::find($guardian_id);
                $data = [
                    'header' => $header,
                    'message' => $message,
                    'salutation' => $salutation,
                    'notification' => $notification,
                    'type' => $type,
                    'guardian_id' => $guardian_id,
                    'system_notification_id' => $notification->id,
                ];
                ProcessGuardianNotification::dispatch($guardian, $data);
            }
        }
    }

    private function send_notification_to_user($audiences, $header = null, $message, $salutation = null, $notification, $type, $isMobile)
    {
        foreach ($audiences as $audience) {
            $user = User::where('id', $audience['user_id'])->first();
            // dd($user);
            $mobile_number = $isMobile ? $user->mobile_number : $audience['contact_information']['mobile'];
            $data = [
                'header' => $header,
                'message' => $message,
                'salutation' => $salutation,
                'notification' => $notification,
                'employee_id' => $user->id,
                'type' => $type,
                'system_notification_id' => $notification->id,
                'mobile' => $mobile_number,
            ];
            ProcessSystemNotification::dispatch($user, $data);
        }
    }

    public function nwa_notifcation_card(Request $request)
    {
        if ($request->ajax()) {
            $data = SystemNotification::where('audience', "NWA")->where('branch_id', get_NWABranchCode())->get();
            // dd($data);
            // if ($request->notification_type) {
            //     $data = $data->where('notification_type', $request->notification_type);
            // }
            // if ($request->audience) {
            //     $data = $data->where('audience', $request->audience);
            // }
            // if ($request->branch_id) {
            //     $data = $data->whereHas('branch', function ($q) use ($request) {
            //         $q->where('id', $request->branch_id);
            //     });
            // }
            // $data->get();
            // dd($data->get()->toArray());
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('notification_type', function ($row) {
                return $row->notification_type;
            })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y');
                })
                ->addColumn('message', function ($row) {
                    $msg = null;
                    if (isset($row->notification_type)) {
                        $msg = $row->notification_type == "SMS" ? $row->message : $row->email_header;
                    }
                    return ($msg) ? "<span class='d-inline-block text-truncate' style='max-width: 60%;'>$msg</span>" :  "";
                    // return $msg;
                })
                ->rawColumns(['notification_type', 'created_at', 'message'])
                ->make(true);
        }
        return view('settings.system_notifications.notifications');
    }
}
