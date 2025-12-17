<?php

namespace App\Http\Controllers;

//use Mail;
use App\Models\Branch;
use App\Models\Billing;
use App\Models\Section;
use App\Models\Student;
use App\Models\ComClass;
use App\Models\Guardian;
use App\Models\BillingOtp;
use App\Models\BranchClass;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Billing::with([
                'students.active_class.branch_class_sections.com_classes',
                'students.active_class.branch_class_sections.sections',
                'branch',
                'invoice'
            ]);

            if (auth()->user()->hasRole('network_associate'))
                $data = $data->where('branch_id', get_set_NWABranchId());

            if ($request->branch_id && $request->branch_id > 0) {
                $data = $data->where('branch_id', $request->branch_id);
            } elseif (!isSuperAdmin() && !isHeadOfficeEmp() /*!auth()->user()->hasRole('manager-parent-relations')*/) {
                $data = $data->where('branch_id', get_branch_id());
            }

            if ($request->section_id && $request->section_id > 0) {
                $data = $data->whereHas('students.active_class.branch_class_sections.sections', function ($query) use ($request) {
                    $query->where('section_id', $request->section_id);
                });
            }

            if ($request->class_id && $request->class_id > 0) {
                $data = $data->whereHas('students.active_class.branch_class_sections', function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                });
            }

            if ($request->gender && $request->gender != '') {
                $data = $data->whereHas('students', function ($query) use ($request) {
                    $query->where('gender', $request->gender);
                });

            }

        if ($request->status && $request->status != 'all') {
            if (in_array($request->status, ['on_roll', 'registered', 'left', 'pass-out']))
                $data = $data->whereHas('students', function ($query) use ($request) { $query->where('status', $request->status); });
            elseif (in_array($request->status, ['transferred'])) {
                $data = $data->whereHas('students', function ($query) use ($request) {
                    $query->where('from_branch', '!=', null);
                });
            } else
                $data = $data->whereHas('students', function ($query) use ($request) {
                    $query->whereNull('status');
                });
        }

        if ($request->searchName && $request->searchName != null) {
            //dd($request->searchName);
            $data = $data->where(function ($query) use ($request) {
                $query->orWhere('order_id', 'like', '%' . $request->searchName . '%');
                $query->orWhere('discountable_charges', 'like', '%' . $request->searchName . '%');
                $query->orWhere('royalty_amount', 'like', '%' . $request->searchName . '%');
                $query->orWhere('arrears', 'like', '' . $request->searchName . '%');
                $query->orWhere('billing_amount', 'like', '%' . $request->searchName . '%');
                $query->orWhere('total_after_royalty', 'like', '%' . $request->searchName . '%');
            })->OrWhereHas('invoice', function ($query) use ($request) {
                $query->where('invoice_no', 'like', '%' . $request->searchName . '%');
            })->OrWhereHas('students', function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->searchName . '%');
                $query->orWhere('middle_name', 'like', '%' . $request->searchName . '%');
                $query->orWhere('last_name', 'like', '%' . $request->searchName . '%');
                $query->orWhere('gender', 'like', '' . $request->searchName . '%');
                $query->orWhere('registration_no', 'like', '%' . $request->searchName . '%');
                $query->orWhere('roll_no', 'like', '%' . $request->searchName . '%');
            });


        }
            //$data = $data->get();
            //dd($data->toArray());

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_id', function ($row) {
                    return !empty($row['order_id']) ? $row['order_id'] : '';
                })
                ->addColumn('invoice_no', function ($row) {
                    return view('billing.invoice_list_link', ['row' => $row]);
                    //return !empty($row->invoice->invoice_no) ? $row->invoice->invoice_no : '';
                })
                ->addColumn('full_name', function ($row) {
                    return !empty($row['students']) ? $row->students->first_name . ' ' . $row->students->middle_name . ' ' . $row->students->last_name : '';
                })
                ->addColumn('branch', function ($row) {
                    return !empty($row->branch->br_name) ? $row->branch->br_name : '';
                })
                ->addColumn('class_section', function ($row) {
                    $class_name = isset($row['students']['active_class']['branch_class_sections']['com_classes']) ? $row['students']['active_class']['branch_class_sections']['com_classes']['class_name'] : 'N/A';
                    $section_name = isset($row['students']['active_class']['branch_class_sections']['sections']) ? $row['students']['active_class']['branch_class_sections']['sections']['section_name'] : 'N/A';
                    return $class_name . ' / ' . $section_name;
                })
                ->addColumn('discountable_charges', function ($row) {
                    return $row['discountable_charges'];
                })
                ->addColumn('non_refundable_charges', function ($row) {
                    return $row['non_refundable_charges'];
                })
                ->addColumn('sibling_discount_percentage', function ($row) {
                    return $row['sibling_discount_percentage'];
                })
                ->addColumn('concession_type', function ($row) {
                    return $row['concession_type'];
                })
                ->addColumn('concession_percentage', function ($row) {
                    return $row['concession_percentage'];
                })
                ->addColumn('concession_discount', function ($row) {
                    return $row['concession_discount'];
                })
                ->addColumn('royalty_percentage', function ($row) {
                    return $row['royalty_percentage'];
                })
                ->addColumn('royalty_amount', function ($row) {
                    return $row['royalty_amount'];
                })
                ->addColumn('total_after_royalty', function ($row) {
                    return $row['total_after_royalty'];
                })
                ->addColumn('arrears', function ($row) {
                    return $row['arrears'];
                })
                ->addColumn('billing_amount', function ($row) {
                    return $row['billing_amount'];
                })
                ->addColumn('created_at', function ($row) {
                    return !empty($row['created_at']) ? date('d-m-Y h:i:s',strtotime($row['created_at'])) : '';
                })
                ->make(TRUE);
        }

        $branches = Branch::all();
        if (!isSuperAdmin() && !isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } else {
            $classes = ComClass::all();
            $sections = Section::all();
        }

        return view('billing.payments', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show(Billing $billing)
    {
        //
    }

    public function online()
    {
        return view('billing.online');
    }

    public function billing()
    {
        //dd('I am here.');
        return view('billing.otp');
    }

    private function obfuscate_email($email)
    {
        $em   = explode("@",$email);
        $name = implode('@', array_slice($em, 0, count($em)-1));
        $len  = floor(strlen($name)/2);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
    }

    private function hide_mobile_no($number)
    {
        return substr($number, 0, 4) . '****' . substr($number, -3);
    }

    public function getGaurdianDetails(Request $request)
    {
        if ($request->ajax()) {
            //dd($request->all());
            $mode_html = null;
            if($request->field == 'roll_no')
            {
                $student = Student::where('roll_no', $request->field_val)->orWhere('registration_no', $request->field_val)->get('id');
                if (isset($student[0])) {
                    $student_id = $student[0]['id'];
                    $gaurdians = Guardian::where('student_id', $student_id)->with('relation')->get();
                    //dd($gaurdians->toArray());
                    foreach($gaurdians as $gaurdian)
                    {
                        $mode_html .= '<strong>'.$gaurdian['relation']['relation_name'].'</strong>
                                       <br>
                                       <input type="radio" name="mode" id="email_'.$gaurdian['relation']['relation_name'].'" value="'.$gaurdian['email'].'"> &nbsp; '.$this->obfuscate_email($gaurdian['email']).'
                                       <br>
                                       <input type="radio" name="mode" id="mobile_'.$gaurdian['relation']['relation_name'].'" value="'.$gaurdian['mobile'].'"> &nbsp; '.$this->hide_mobile_no($gaurdian['mobile']).'
                                       <br>';
                    }
                    $mode_html .='<input type="hidden" name="student_id" id="student_id" value="'.$student_id.'">';
                }
            }
            if(isset($mode_html))
            {
                return $mode_html;
            }
            else
            {
                return '';
            }
        }
    }

    private function sendOTPCode($message, $mobile = NULL)
    {
        $type = "xml";
        $id = "cd1094beacon";
        $pass = "system231";
        $lang = "English";
        $mask = "1";

        if ($mobile == NULL) {
            $mobile = $this->phone;
        }

        $mobile = preg_replace('/\D+/', '', $mobile);
        //dd($mobile);
        $to = '92' . substr($mobile, -10);

        $message = urlencode($message);
        $data = "id=" . $id . "&pass=" . $pass . "&msg=" . $message . "&to=" . $to . "&lang=" . $lang . "&mask=" . $mask . "&type=" . $type;

        $ch = curl_init('http://www.opencodes.pk/api/medver.php/sendsms/url');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        $xml = simplexml_load_string($result);
        $api_response = $xml->code;
        curl_close($ch);
        // dd($data);
        return $api_response;
    }

    public function SendOTP(Request $request){
        if (filter_var($request->mode, FILTER_VALIDATE_EMAIL)) {
            $gaurdians = Guardian::where('email', $request->mode)->with('relation')->get();
            $receiver_name = $gaurdians[0]['guardian_name'];
            $randomNumber = random_int(1000, 9999);
            $subject ='UCS Billing Application OTP';
            $message = 'Your UCS billing OTP is : '. $randomNumber;

            Mail::to($request->mode)->send(new NotifyMail($subject, $receiver_name, $message));
            if (Mail::failures()) {
                return '';
            }
            else
            {
                $billing_otp = null;
                $billing_otp = BillingOtp::create([
                    'student_id' => $request->student_id,
                    'OTP' => $randomNumber
                ]);
                if($billing_otp)
                {
                    return $randomNumber;
                }
            }
            //$message = 'Your OTP for UCS Billing is ' . $randomNumber;
            //new SendNotification($header, $message, $salutation);


        }
        else
        {
            $gaurdians = Guardian::where('mobile', $request->mode)->with('relation')->get();
            //$gaurdians[0]['id'];
            $randomNumber = random_int(1000, 9999);
            $message = 'Your OTP for UCS Billing is ' . $randomNumber;
            $this->sendOTPCode($message, $request->mode);
            $billing_otp = null;
            $billing_otp = BillingOtp::create([
                'student_id' => $request->student_id,
                'OTP' => $randomNumber
            ]);
            if($billing_otp)
            {
                return $randomNumber;
            }

        }
    }

    public function AddPaidDetails(Request $request)
    {
        if($request->ajax())
        {
            //dd($request->all());
            $input = $request->all();
            $billing = Billing::create($input);
            $invoice_record = StudentInvoice::find($request->invoice_id);
            $invoiceinput['is_paid'] = '1';
            $invoiceinput['paid_date'] = date('Y-m-d');
            $invoiceinput['bank_payment_status'] = 'paid';
            //$invoiceinput['bank_received_amount'] = $request->query('amount');
            $invoiceinput['bank_received_amount'] = $request->bank_received_amount;
            $invoiceinput['updated_at'] = date('Y-m-d H:i:s');
            $invoice_record->update($invoiceinput);
            
            // Handle admission to monthly package transition and status change to 'on_roll'
            if ($invoice_record->invoice_frequency === 'Admission' && $invoice_record->bank_payment_status === 'paid') {
                try {
                    // Check if this is the first paid admission invoice for this student
                    $paidAdmissionInvoiceCount = \App\Models\StudentInvoice::where([
                        'student_id' => $invoice_record->student_id,
                        'invoice_frequency' => 'Admission',
                        'bank_payment_status' => 'paid'
                    ])->count();

                    // Only process if this is the first paid admission invoice
                    if ($paidAdmissionInvoiceCount == 1) {
                        // Apply monthly package transition
                        $monthlyPackageResult = \App\Models\StudentInvoice::apply_monthly_package($invoice_record->student_id);
                        
                        if ($monthlyPackageResult) {
                            // Update student status to 'on_roll'
                            $student = \App\Models\Student::find($invoice_record->student_id);
                            if ($student && in_array($student->status, ['processing', 'registered'])) {
                                $student->update(['status' => 'on_roll']);
                                
                                // Update system ID and roll number if not already set
                                if (!$student->system_id) {
                                    \App\Models\Student::update_student_id($invoice_record->student_id);
                                }
                                if (!$student->roll_no) {
                                    \App\Models\Student::update_roll_no($invoice_record->student_id);
                                }
                                
                                \Log::info('Student status changed to on_roll and monthly package applied via BillingController', [
                                    'student_id' => $invoice_record->student_id,
                                    'invoice_id' => $invoice_record->id,
                                    'old_status' => $student->getOriginal('status'),
                                    'new_status' => 'on_roll',
                                    'monthly_package_id' => $monthlyPackageResult->id
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Error during admission to monthly package transition in BillingController', [
                        'student_id' => $invoice_record->student_id,
                        'invoice_id' => $invoice_record->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            if($billing)
            {
                return $input['invoice_id'];
            }
            else{
                return '';
            }
        }
    }
    public function VerifyOTP(Request $request)
    {
        //dd($request->query('otp'));
        $student_id = BillingOtp::where('OTP', $request->query('otp'))->get('student_id');
        if(isset($student_id[0]))
        {
            $fee_period_id = StudentInvoice::max_fee_period_id($student_id[0]['student_id']);
            $studentInvoice = StudentInvoice::where('student_id',$student_id[0]['student_id'])->where('bank_payment_status','unpaid')->where('fee_period_id',$fee_period_id)->with([
                'student',
                'student.branch',
                'student_fee_package.fee_package',
                'student_fee_package.fee_concession.fee_concession_type',
                'student_fee_package.academic_year',
                'student_fee_package.com_class',
                'student_fee_package.section',
                'student_invoice_items.fee_charges.fee_charges_type',
                'invoice_type',
                'payment_source',
                'promo.promo_type',
                'fee_period'
            ])->first();
            if (get_month_name($studentInvoice->fee_period->from_date) == 'February') {
                $calculations = calculate_total_price_by_invoice_index($studentInvoice);
            } else {
                $calculations = calculate_total_price_by_invoice($studentInvoice);
            }

            if($studentInvoice->student->roll_no != '') {
                $orderId = 'BR'.$studentInvoice->student->branch->branch_code.'_'.$studentInvoice->student->roll_no.'_'.$studentInvoice->invoice_no;
            }else{
                $orderId = 'BR'.$studentInvoice->student->branch->branch_code.'_'.$studentInvoice->student->registration_no.'_'.$studentInvoice->invoice_no;
            }
            $requestBody = '{
                "apiOperation": "CREATE_CHECKOUT_SESSION",
                "interaction": {
                    "operation": "PURCHASE"
                },
                "order": {
                    "id" : "'.$orderId.'",
                    "currency" : "PKR"
                }
            }' ;
            $ch = curl_init();
            //https://mcbpk.gateway.mastercard.com/api/rest/version/60/merchant/824410244809/session
            //https://test-mcbpk.mtf.gateway.mastercard.com/api/rest/version/60/merchant/Test829910158101/session  824410244809
            curl_setopt($ch, CURLOPT_URL, "https://mcbpk.gateway.mastercard.com/api/rest/version/60/merchant/824410244809/session");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody) ;  //Post Fields
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
            //test-mcbpk.mtf.gateway.mastercard.com, Test829910158101:b99e6dbc33ad0cdc2cf949b3cc6be236
            //mcbpk.gateway.mastercard.com, 824410244809:ffa59d1a8f9ba89d15c554654d427795
            $headers = [
                'Authorization: Basic '.base64_encode("merchant.824410244809:ffa59d1a8f9ba89d15c554654d427795"),
                'Content-Type: application/json',
                'Host: mcbpk.gateway.mastercard.com',
                'Referer: https//oms.ucs.edu.pk/ipg-billing', //Your referrer address
                'cache-control: no-cache',
                'Accept: application/json'
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $server_output = curl_exec($ch) ;
            curl_close ($ch);
            $json = json_decode($server_output, true) ;
            $sessionId = $json['session']['id'] ;
            return view('billing.info',
            [
                'studentInvoice' => $studentInvoice,
                'billingInfo' => $calculations,
                'session_id' => $sessionId,
                'orderId' => $orderId,
                'otp' => $request->query('otp'),
            ]
        );
        }
    }

    public function PaymentSuccess(Request $request)
    {
        //dd($request->all());
        $invoice_record = Billing::where('invoice_id',$request->invoice_id)->first();
        //dd($invoice_record['order_id']);
        return view('billing.success',
        [
            'orderId' => $invoice_record['order_id'],
            'amount' => $invoice_record['billing_amount'].' PKR',
            'invoice_id' => $invoice_record['invoice_id'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billing $billing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        //
    }
}
