<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankInquiryValidation;
use App\Http\Requests\BankPaymentValidation;
use App\Models\Student;
use App\Models\StudentInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function getInquiry(BankInquiryValidation $request)
    {
        try {

            if (!Auth::guard()->attempt($request->only('email', 'password')))
                return response(['code' => 401, 'status' => 'failure', 'message' => 'Invalid email or password', 'data' => new \stdClass()]);

            $invoice = StudentInvoice::with([
                'student' => function ($q) {
                    $q->select('id', 'branch_id', 'first_name', 'last_name', 'gender', 'email', 'date_of_birth', 'admission_wef', 'birth_place')->where('status', '!=', 'left');
                    $q->with([
                        'branch' => function ($q2) {
                            $q2->select('id', 'br_name', 'abbreviation', 'branch_code');
                            $q2->with('nwa.default_bank_account');
                        },
                        'active_class' => function ($q1) {
                            $q1->with([
                                'academic_years:id,title',
                                'branch_class_sections' => function ($q2) {
                                    $q2->select('id', 'class_id', 'section_id');
                                    $q2->with(['sections:id,section_name', 'com_classes:id,class_name']);
                                },
                            ]);
                            $q1->select('id', 'academic_year_id', 'branch_class_section_id', 'student_id');
                        },
                    ]);
                },
                'student_fee_package.fee_package',
                'student_fee_package.fee_concession.fee_concession_type',
                'student_fee_package.academic_year',
                'student_fee_package.com_class',
                'student_fee_package.section',
                'student_invoice_items.fee_charges.fee_charges_type',
                'fee_period',
            ])
                ->select('id', 'student_id', 'student_fee_package_id', 'issue_date', 'due_date', 'validity_date', 'arrears_date', 'arrears_amount', 'invoice_no', 'is_paid', 'paid_date', 'royalty_percentage', 'royalty_amount', 'bank_payment_status', 'invoice_type_id', 'invoice_frequency', 'fee_period_id', 'arrears_fine', 'due_date_fine')
                ->where('invoice_no', $request->invoice_code)->first();

            if (empty($invoice) || $invoice['bank_payment_status'] == 'cancelled' || $invoice['validity_date'] < date('Y-m-d'))
                return response(['code' => 404, 'status' => 'failure', 'message' => 'No Record Found', 'data' => new \stdClass()]);

            if (get_month_name($invoice['fee_period']['from_date']) == 'February') {
                $get_total_price = calculate_total_price_by_invoice_index($invoice);
            } else {
                $get_total_price = calculate_total_price_by_invoice($invoice);
            }
            // $get_total_price = calculate_total_price_by_invoice($invoice);
            $invoice['royalty_amount'] = round($invoice['due_date'] < date('Y-m-d') ? $get_total_price['after_dd_royalty_amount'] : $get_total_price['royalty_amount']);
            $invoice['royalty_percentage'] = 0;
            $invoice['nwa_amount'] = round($invoice['due_date'] < date('Y-m-d') ? $get_total_price['after_dd_total_after_royalty'] : $get_total_price['total_after_royalty']);
            $invoice['total'] = round($invoice['due_date'] < date('Y-m-d') ? $get_total_price['after_due_date'] : $get_total_price['total']);

            $invoice['student']['section'] = new \stdClass();
            $invoice['student']['student_class'] = new \stdClass();
            $invoice['student']['academic_year'] = new \stdClass();
            if (isset($invoice['student']['active_class']['branch_class_sections'])) {
                $invoice['student']['section'] = $invoice['student']['active_class']['branch_class_sections']['sections'];
                $invoice['student']['student_class'] = $invoice['student']['active_class']['branch_class_sections']['com_classes'];
                $invoice['student']['academic_year'] = $invoice['student']['active_class']['academic_years'];
                unset($invoice['student']['active_class']);
            }

            $invoice['bank_detail'] = new \stdClass();
            if (isset($invoice['student']['branch']['default_bank_account'])) {
                $nwa_bank_account = $invoice['student']['branch']['default_bank_account'];
                $invoice['bank_detail']->id = $nwa_bank_account['id'];
                $invoice['bank_detail']->bank_name = $nwa_bank_account['bank_name'];
                $invoice['bank_detail']->account_title = $nwa_bank_account['account_title'];
                $invoice['bank_detail']->account_no = $nwa_bank_account['account_no'];
                $invoice['bank_detail']->IBAN = $nwa_bank_account['IBAN'];
            }

            //apply full name in first name and branch code in branch_id, uncomment below code of receive email from BANK
            if (isset($invoice['student'])) {
                $invoice['student']['first_name'] = $invoice['student']['first_name'] . ' ' . $invoice['student']['last_name'];

                if (isset($invoice['student']['branch'])) {
                    $invoice['student']['branch_id'] = $invoice['student']['branch']['branch_code'];
                    $invoice['student']['branch']['id'] = $invoice['student']['branch']['branch_code'];
                }
            }

            unset($invoice['promo'], $invoice['fee_period'], $invoice['student']['branch']['nwa'], $invoice['bank_payment_status']);

            if ($invoice->is_paid)
                return response(['code' => 204, 'status' => 'failure', 'message' => 'Already paid', 'data' => new \stdClass()]);

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Challan fetched successfully', 'data' => $invoice]);
        } catch (\Exception $exception) {
            return response(['code' => 500, 'status' => 'failure', 'message' => $exception->getMessage(), 'data' => new \stdClass()]);
        }
    }

    public function updatePayment(BankPaymentValidation $request)
    {

        try {

            //\Log::info('request from function');
            //\Log::info($request->all());

            if (!Auth::guard()->attempt($request->only('email', 'password')))
                return response(['code' => 401, 'status' => 'failure', 'message' => 'Invalid email or password', 'data' => new \stdClass()]);

            $invoice = StudentInvoice::select('id', 'student_id', 'student_fee_package_id', 'issue_date', 'due_date', 'validity_date', 'arrears_date', 'arrears_amount', 'invoice_no', 'is_paid', 'paid_date', 'royalty_percentage', 'royalty_amount', 'bank_payment_status', 'invoice_type_id', 'invoice_frequency', 'fee_period_id', 'arrears_fine', 'due_date_fine')->where('invoice_no', $request->invoice_code)->first();
            //\Log::info('check 404 TRUE/False');
            if (empty($invoice) || $invoice['bank_payment_status'] == 'cancelled' || $invoice['validity_date'] < date('Y-m-d')) {
                //\Log::info('404 TRUE');
                return response(['code' => 404, 'status' => 'failure', 'message' => 'No Record Found', 'data' => new \stdClass()]);
            }

            if (!$invoice->is_paid) {
                //\Log::info('IN IS PAID');
                $invoice->update([
                    'is_paid' => 1,
                    'paid_date' => $request->get('payment_received_date', null),
                    'bank_payment_status' => $request->payment_status,
                    'bank_received_amount' => $request->get('amount_received', null)
                ]);

                if (strtolower($request->payment_status) == 'paid' && $invoice->invoice_frequency == 'Admission') {
                    Student::update_student_id($invoice->student_id);
                    Student::update_roll_no($invoice->student_id);
                    StudentInvoice::apply_monthly_package($invoice->student_id);
                }

                //\Log::info('After Set Monthly Package');
            } else
                return response(['code' => 204, 'status' => 'failure', 'message' => 'Already paid', 'data' => new \stdClass()]);

            //\Log::info('End of API');

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Updated Successfully', 'data' => new \stdClass()]);
        } catch (\Exception $exception) {
            return response(['code' => 500, 'status' => 'failure', 'message' => $exception->getMessage(), 'data' => new \stdClass()]);
        }
    }
}
