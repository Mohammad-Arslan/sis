<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\City;
use App\Models\ComClass;
use App\Models\Source;
use App\Models\StudentInvoice;
use App\Models\Town;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function listCities(Request $request)
    {
        try {
            if (isset($request->state_id))
                $data = City::where('state_id', $request->state_id)->get();
            else
                $data = City::all();

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $data]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }

    public function listSources()
    {
        try {
            $data = Source::all();

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $data]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }

    public function listTowns(Request $request)
    {
        try {
            $data = Town::where('city_id', $request->city_id)->get();

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $data]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }

    public function getTownBranches(Request $request){
        try {

            $data = Branch::whereHas('contact_information' , function($q) use ($request){
                $q->where('town_id',$request->town_id);
            })->get();

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $data]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }

    public function getClasses(Request $request){
        try {
            $data = ComClass::all();

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $data]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }

    public function getBranchClasses(Request $request){
        try {
            $branch_class_ids = get_branch_class_ids($request->branch_id);
            $data = ComClass::whereIn('id',$branch_class_ids)->get();

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $data]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }

    public function royaltyComputationReport(Request $request){

        try {

            $per_page = $request->get('per_page',0);
            $page_no = $request->get('page_no',1);
            $start = $per_page ? (($page_no - 1) * $per_page) : 0;

            $request->filters = $request->all();
            $request->filters['payment_status'] = $request->get('payment_status','paid');
            if (!empty($request->filters['branch_code']) && $branch_id = get_branch_id_from_code($request->filters['branch_code']))
                $request->filters['branch_id'] = $branch_id;

            $invoiceFilters = [];
            if (!empty($request->filters['payment_status']))
                $invoiceFilters['bank_payment_status'] = $request->filters['payment_status'];

            if (!empty($request->filters['fee_period_id']))
                $invoiceFilters['fee_period_id'] = $request->filters['fee_period_id'];

            $classFilters = [];
            if (!empty($request->filters['class_id']))
                $classFilters['com_class_id'] = $request->filters['class_id'];
            if (!empty($request->filters['section_id']))
                $classFilters['section_id'] = $request->filters['section_id'];


            $students_data = StudentInvoice::where($invoiceFilters)->whereHas('student', function ($query) use ($request) {
                if (!empty($request->filters['branch_id']))
                    $query->where('branch_id', $request->filters['branch_id']);
            })->whereHas('student_fee_package', function ($query) use ($classFilters) {
                $query->where($classFilters);
            })->with([
                'student_fee_package.fee_package.fee_packages_fee_charges.fee_charges',
                'student.branch.class_group',
                'student.branch.bank_accounts',
                'student.branch.contact_information.state',
                'student.city',
                'student_fee_package.fee_package',
                'student_fee_package.fee_concession.fee_concession_type',
                'student_fee_package.academic_year',
                'student_fee_package.com_class',
                'student_fee_package.section',
                'student_invoice_items.fee_charges.fee_charges_type',
                'invoice_type',
                'payment_source',
                'promo.promo_type',
                'fee_period',
                'student' => function ($query) use ($request) {
                    if (!empty($request->filters['branch_id']))
                        $query->where('branch_id', $request->filters['branch_id']);
                }
            ]);

            if (isset($request->filters['from_date']) && !empty($request->filters['from_date']))
                $students_data->whereDate('paid_date', '>=', $request->filters['from_date']);

            if (isset($request->filters['to_date']) && !empty($request->filters['to_date']))
                $students_data->whereDate('paid_date', '<=', $request->filters['to_date']);

            if ($per_page)
                $students_data = $students_data->limit($per_page);

            if ($start)
                $students_data = $students_data->offset($start);

            $students_data = $students_data->get();

            $mapper = array();
            foreach ($students_data as $data){
                $nestedData['branch_code'] = $data['student']['branch']['branch_code'];
                $nestedData['branch_name'] = $data['student']['branch']['br_name'];
                $nestedData['city_name'] = isset($data['student']['branch']['contact_information']['city']) ? $data['student']['branch']['contact_information']['city']['city_name'] : '';;
                $nestedData['state_name'] = isset($data['student']['branch']['contact_information']['state']) ? $data['student']['branch']['contact_information']['state']['state_name'] : '';
                $nestedData['student_name'] = $data['student']['first_name'] . ' ' . $data['student']['last_name'];
                $nestedData['student_code'] = $data['student']['roll_no'];
                $nestedData['invoice_no'] = $data['invoice_no'];
                $nestedData['fee_period'] = get_month_name($data['fee_period']['from_date']) == get_month_name($data['fee_period']['to_date']) ? get_month_name($data['fee_period']['from_date']) : get_month_name($data['fee_period']['from_date']) . ' - ' . get_month_name($data['fee_period']['to_date']);
                $nestedData['class_name'] = $data['student_fee_package']['com_class']['class_name'];
                $nestedData['section_name'] = $data['student_fee_package']['section']['section_name'];
                $nestedData['payment_status'] = ucwords($data['bank_payment_status']);
                $nestedData['payment_date'] = parse_date($data['paid_date'],'Y-m-d');

                $fees_calc = calculate_total_price_by_invoice($data);

                $admission_fees = $fees_calc['invoices_charges']['AF'];
                $nestedData['admission_fees'] = round($admission_fees);

                $tution_fees = $fees_calc['invoices_charges']['TF'];
                $nestedData['tuition_fees'] = round($tution_fees);

                $security_fees = $fees_calc['invoices_charges']['SD'];
                $nestedData['security_deposit'] = round($security_fees);

                $total = $fees_calc['total'];
                $nestedData['total'] = round($total);

                $nestedData['royalty'] = $fees_calc['royalty_amount'];
                $nestedData['nwa_amount'] = $fees_calc['total_after_royalty'];

                $mapper[] = $nestedData;
            }

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $mapper]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }
}
