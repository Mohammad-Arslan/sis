<?php

use App\Models\Student;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Subject;
use App\Models\ComClass;
use App\Models\Employee;
use App\Models\PromoClass;
use App\Models\AcademicYear;
use App\Models\ClassStudent;
use App\Models\ClassTeacher;
use App\Models\StudentLedger;
use App\Models\StudentInvoice;
use App\Models\branch_security;
use App\Models\GradingCriteria;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LeaveApplication;
use App\Models\StudentAttendance;
use App\Models\BranchAcademicYear;
use App\Models\BranchClassSection;
use App\Models\BranchWorkingShift;
use App\Models\EmployeeAttendance;
use App\Models\HomeWorkDiaryDetial;
use App\Models\StudentLedgerInvoice;
use App\Models\AcademicYearWorkingDays;
use App\Models\HomeWorkDiaryAttachment;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Facades\Session;
use App\Models\FrachiseApplicationRemark;
use App\Models\FranchiseApplicationsAttachment;

function default_image()
{
    return asset('uploads/employees/user-dummy-img.jpg');
}

function getApplicationTypes()
{
    return \App\Models\ApplicationType::all();
}

function strToTimeDateFormat($date)
{
    return date('Y-m-d', strtotime($date));
}

function calculateTimeDifference($time1, $time2)
{
    return floor((strtotime($time2) - strtotime($time1)) / 60);
}


function getCurrentMiliSec()
{
    return (int) floor(microtime(true) * 1000);
}

function get_set_NWABranchId($branch_id = 0)
{

    $user = auth()->user();

    //check whether user is NWA or not
    if ($user && $user->hasRole('network_associate') && $user['networkAssociates'] && $user['networkAssociates']['branches']->isNotEmpty()) {

        $nwa_branch_ids = $user['networkAssociates']['branches']->pluck('id')->toArray();
        //check whether selected branch is available in NWA's branches or not, if not then don't set
        if ($branch_id && in_array($branch_id, $nwa_branch_ids)) {
            Session::put('nwa_branch_id', $branch_id);
            return $branch_id;
        } else if (Session::has('nwa_branch_id')) { //if already set then return the branch id
            return Session::get('nwa_branch_id');
        } else { //if not set yet then set the first branch and return the branch id
            Session::put('nwa_branch_id', $user['networkAssociates']['branches'][0]['id']);
            return $user['networkAssociates']['branches'][0]['id'];
        }
    }

    return 0;
}

function get_NWABranchCode()
{

    $user = auth()->user();
    //check whether user is NWA or not
    if ($user && $user->hasRole('network_associate') && $user['networkAssociates'] && $user['networkAssociates']['branches']->isNotEmpty()) {
        return Session::get('nwa_branch_id');
    }

    return 0;
}

function get_current_acad_year_by_branch_id($branchId)
{
    return BranchAcademicYear::where('branch_id', $branchId)->whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>', Carbon::now())->latest('created_at')->first();
}

function get_active_acad_year_by_branch_id($branchId, $s_academic_year_id)
{
    return BranchAcademicYear::where('branch_id', $branchId)->where('academic_year_id', $s_academic_year_id)->first();
}

function get_file_from_s3($path, $file_name = 'dummy.png')
{
    if (!$file_name) {
        return default_image();
    }
    
    try {
        // Check if S3 is configured and accessible
        if (config('filesystems.disks.s3.key') && Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->url($path);
        }
    } catch (\Exception $e) {
        // S3 not available, fall back to local storage
    }
    
    // Fall back to local storage or default image
    if (Storage::disk('public')->exists($path)) {
        return Storage::disk('public')->url($path);
    }
    
    // If file doesn't exist in local storage, return default image
    return default_image();
}

function get_student_security($branch, $admission_year_id)
{
    $security = branch_security::where('branch_id', $branch)->where('academic_year_id', $admission_year_id)->first();
    if (isset($security->amount)) {
        return $security->amount;
    }

    return '';
}

function number_to_words($number)
{
    $formatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
    return ucwords($formatter->format($number));
}

/**
 * @description function to get all leave types
 */
function leaveTypes()
{
    return \App\Models\LeaveType::all();
}

/**
 * @description function to get all designations
 * */
function designations()
{
    return \App\Models\Designation::all();
}

function get_branch_royalty($branch_id)
{
    $today = \Carbon\Carbon::today()->format('Y-m-d');
    $branch_royalty = \App\Models\BranchRoyalty::where([['branch_id', $branch_id], ['with_effect_from', '<=', $today], ['closing_date', '>=', $today]])->first();

    return !empty($branch_royalty) ? $branch_royalty->royalty_rate : 0;
}

function get_lesson_plan_hierarchy($branch_id = 0, $academic_year_id = 0, $class_id = 0, $subject_id = 0, $type = '')
{

    $branch_id = $branch_id ? $branch_id : (isset(auth()->user()['employee']) ? auth()->user()['employee']['branch_id'] : $branch_id);

    if ($type == 'branch') {
        $branch_ids = \App\Models\LessonPlan::pluck('branch_id')->toArray();
        return \App\Models\Branch::whereIn('id', $branch_ids)->get();
    } else if ($type == 'academic_year') {
        $academic_year_ids = \App\Models\LessonPlan::where('branch_id', $branch_id)->pluck('academic_year_id')->toArray();
        return \App\Models\AcademicYear::whereIn('id', $academic_year_ids)->get();
    }

    $lesson_plans = \App\Models\LessonPlan::with([
        'com_class',
        'subject',
        'term',
        'week'
    ])->where([['branch_id', $branch_id], ['academic_year_id', $academic_year_id]]);

    if ($class_id)
        $lesson_plans = $lesson_plans->where('com_class_id', $class_id);

    if ($subject_id)
        $lesson_plans = $lesson_plans->where('subject_id', $subject_id);

    $lesson_plans = $lesson_plans->get();

    if ($type == 'class')
        $lesson_plans = $lesson_plans->unique('com_class_id');
    if ($type == 'subject')
        $lesson_plans = $lesson_plans->unique('subject_id');

    return $lesson_plans;
}

function get_teacher_classes($user_id = 0)
{

    if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('network_associate'))
        return ComClass::whereIn('id', array())->get();

    if ($user_id)
        $user = \App\Models\User::find($user_id);
    else
        $user = auth()->user();

    $class_teachers = $user['employee']['class_teachers'];
    $teachers_class_ids = array();
    if (!empty($class_teachers)) {
        foreach ($class_teachers as $class_teacher) {
            array_push($teachers_class_ids, $class_teacher['branch_class_section']['class_id']);
        }
    }
    return ComClass::whereIn('id', $teachers_class_ids)->get();
}

function get_teacher_subjects($user_id = 0)
{

    if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('network_associate'))
        return Subject::whereIn('id', array())->get();

    if ($user_id)
        $user = \App\Models\User::find($user_id);
    else
        $user = auth()->user();

    $class_teachers = $user['employee']['class_teachers'];
    return Subject::whereIn('id', $class_teachers->pluck('subject_id')->toArray())->get();
}

/* This function get class subjects from class subjects table */
function getClassSubjects($class_id, $branch_id = 0)
{

    $subjects = \App\Models\ClassSubject::where('class_id', $class_id);

    if ($branch_id) {
        $state_id = get_branch_state_id($branch_id);
        $subjects = $subjects->where(function ($q) use ($state_id) {
            $q->where('state_id', $state_id);
            $q->orWhereNull('state_id');
        });
        $subjects = $subjects->where(function ($q) use ($branch_id) {
            $q->where('branch_id', $branch_id);
            $q->orWhereNull('branch_id');
        });
    }

    $subject_ids = $subjects->pluck('subject_id')->toArray();

    return Subject::whereIn('id', $subject_ids)->get();
}

/* This function get class subjects from, which teachers are being taught in branch table */
function listClassSubjects($class_id)
{
    // if login user has employee id in class teachers then get his/her own subjects of given class
    // otherwise get all subjects of class
    // if nwa then get classes of particular branch

    $user = auth()->user();

    if ($user->hasRole('network_associate'))
        $branch_class_section_ids = BranchClassSection::where([['class_id', $class_id], ['branch_id', get_set_NWABranchId()]])->pluck('id')->toArray();
    else
        $branch_class_section_ids = BranchClassSection::where('class_id', $class_id)->pluck('id')->toArray();

    if ($user['employee'] && $user['employee']['class_teachers']->isNotEmpty())
        $subject_ids = ClassTeacher::whereIn('branch_class_section_id', $branch_class_section_ids)->where('employee_id', $user['employee']['id'])->pluck('subject_id')->toArray();
    else
        $subject_ids = ClassTeacher::whereIn('branch_class_section_id', $branch_class_section_ids)->pluck('subject_id')->toArray();

    return Subject::whereIn('id', $subject_ids)->get();
}

function get_branch_schedule_term_count($staff_id, $term_name)
{
    $count_rows = BranchWorkingShift::where('staff_id', $staff_id)->where('name', $term_name)->count();
    return ($count_rows / 5);
}

function ReportingTo()
{
    $reporting_user = Employee::where('user_id', auth()->user()->id)->get('reporting_to');
    $reporting_manager = User::where('id', $reporting_user[0]->reporting_to)->get(['id', 'name']);
    //dd($reporting_manager);
    return $reporting_manager;
}

// function calculate_total_price_by_invoice($studentInvoice, $type = null)
// {
//     $totalPrice = 0;
//     $non_refundable_charges = 0;
//     $discountable_charges = 0;
//     $invoices_charges = [
//         'AF' => 0,
//         'TF' => 0,
//         'SD' => 0
//     ];

//     // dump($studentInvoice->student_concessions->toArray());

//     if (isset($studentInvoice['student_invoice_items'])) {
//         foreach ($studentInvoice['student_invoice_items'] as $student_invoice_item) {
//             $amount = isset($student_invoice_item['debit']) ? $student_invoice_item['debit'] : $student_invoice_item['credit'];


//             // Siblings Concession comparison with Student Concession for Admission Charge (AF)
//             if ($studentInvoice['invoice_frequency'] == 'Admission' && $student_invoice_item['fee_charges']['fee_charges_type']['abbreviation'] == 'AF' && $type != 'for_arrears' && isset($studentInvoice['student']['sibling_info'])) {
//                 $sibiling_discount_val = false;
//                 foreach ($studentInvoice->student->sibling_info->family->children as $child) {
//                     if ($child->student->status == 'on_roll') {
//                         $sibiling_discount_val = true;
//                     }
//                 }
//                 // dd($sibiling_discount_val);
//                 if ($sibiling_discount_val == true) {
//                     if (in_array((int) $studentInvoice->student->sibling_info->sibling_no, [2, 3])) {
//                         $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 50;
//                         $max_concession = isset($student_invoice_item['concession']) ? ($student_invoice_item['concession'] > 50 ? $student_invoice_item['concession'] : 50) : 50;
//                         $student_item_amount = $amount - (($amount / 100) * $max_concession);
//                     } else if ($studentInvoice->student->sibling_info->sibling_no > 3) {
//                         $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 100;
//                         $student_item_amount = $amount - $amount;
//                     } else {
//                         $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
//                         //$student_item_amount =  $amount;
//                         $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
//                     }
//                 } else {
//                     $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
//                     //$student_item_amount =  $amount;
//                     $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
//                 }
//                 $invoices_charges[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = $student_item_amount;
//                 $totalPrice = $totalPrice + $student_item_amount;
//             } else {
//                 $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
//                 $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
//                 // dump($student_item_amount);
//                 $invoices_charges[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = $student_item_amount;
//                 $totalPrice = $totalPrice + $student_item_amount;
//             }
//             $non_refundable_charges = !$student_invoice_item['fee_charges']['is_refundable'] ? $non_refundable_charges + $student_item_amount : $non_refundable_charges + 0;
//             $discountable_charges = $student_invoice_item['fee_charges']['is_discountable'] ? $discountable_charges + $student_item_amount : $discountable_charges + 0;
//         }
//     }

//     // dd($totalPrice);

//     $data = [
//         'sub_total' => $totalPrice,
//         'discountable_charges' => $discountable_charges,
//         'non_refundable_charges' => $non_refundable_charges,
//         'sibling_discount_percentage' => isset($sibling_discount['AF']) ? $sibling_discount['AF'] : 0,
//         'invoices_charges' => $invoices_charges
//     ];

//     if ($type != 'for_arrears')
//         $data['charges_concessions'] = 0;


//     // Fee Concession Calculation
//     if (isset($studentInvoice['student_fee_package']['fee_concession']) && !empty($studentInvoice['student_fee_package']['fee_concession'])) {
//         $fee_concession = $studentInvoice['student_fee_package']['fee_concession'];
//         $concessionDiscount = ($discountable_charges / 100) * $fee_concession['concession_percentage'];

//         $data['concession_type'] = $fee_concession['fee_concession_type']['name'];
//         $data['concession_percentage'] = $fee_concession['concession_percentage'];
//         $data['concession_discount'] = $concessionDiscount;
//     } else {
//         $concessionDiscount = 0;
//         $data['concession_type'] = '';
//         $data['concession_percentage'] = 0;
//         $data['concession_discount'] = 0;
//     }

//     // Promo Discount Calculation
//     if (isset($studentInvoice->promo) && !empty($studentInvoice->promo)) {
//         $promoClasses = PromoClass::where('promo_id', $studentInvoice->promo->id)->get();
//         // dd($promoClasses->toArray());

//         $promoApplicable = false;
//         // dd($promoClasses->toArray());

//         foreach ($promoClasses as $key => $value) {
//             if ($value->class_id == $studentInvoice->student_fee_package->com_class->id) {
//                 $promoApplicable = true;
//                 break;
//             }
//         }

//         if ($promoApplicable) {
//             $promoDiscount = $studentInvoice->promo->promo_unit == 'percentage' ? ($totalPrice / 100) * $studentInvoice->promo->promo_amount : $studentInvoice->promo->promo_amount;

//             $data['promo_name'] = $studentInvoice->promo->name;
//             $data['promo_unit'] = $studentInvoice->promo->promo_unit;
//             $data['promo_amount'] = $studentInvoice->promo->promo_amount;
//             $data['promo_discount'] = $promoDiscount;
//         } else {
//             $promoDiscount = 0;
//         }
//     } else {
//         $promoDiscount = 0;
//     }

//     //Get branch ID if not found in student
//     if (isset($studentInvoice['student']['branch_id'])) {
//         $branchId = $studentInvoice['student']['branch_id'];
//     } else {
//         $studentInfo = \App\Models\Student::where('id', $studentInvoice['student_id'])->first();
//         $branchId = $studentInfo->branch_id;
//     }

//     $data['royalty_percentage'] = get_branch_royalty($branchId);
//     /*//Comment above get_branch_royalty line and uncomment this line If want to apply invoice royalty percentage to invoice, Not uncommenting now because Bank payment integration in process
//     //$data['royalty_percentage'] = $studentInvoice['royalty_percentage'];*/
//     $non_refundable_royalty = $non_refundable_charges * ($data['royalty_percentage'] / 100);
//     $concession_royalty = $concessionDiscount * ($data['royalty_percentage'] / 100);
//     $data['royalty_amount'] = $non_refundable_royalty - $concession_royalty;

//     $data['total'] = $totalPrice - ($concessionDiscount + $promoDiscount);
//     $data['total_after_royalty'] = $totalPrice - ($concessionDiscount + $promoDiscount) - $data['royalty_amount'];

//     $diff_month = 1;
//     if (isset($studentInvoice['fee_period']) && !empty($studentInvoice['fee_period']['to_date']) && !empty($studentInvoice['fee_period']['from_date'])) {
//         $to_date = Carbon::parse($studentInvoice['fee_period']['to_date'])->addDays(2);
//         $diff_month = Carbon::parse($studentInvoice['fee_period']['from_date'])->diffInMonths($to_date);
//     }

//     //multiplication based on no of months invoice
//     $data['sub_total'] = $data['sub_total'] * $diff_month;
//     //$data['non_refund_non_discountable_charges'] = $data['non_refund_non_discountable_charges'] * $diff_month;
//     $data['concession_discount'] = $data['concession_discount'] * $diff_month;
//     $data['royalty_amount'] = $data['royalty_amount'] * $diff_month;
//     $data['total'] = $data['total'] * $diff_month;
//     $data['diff_month'] = $diff_month;

//     // Arrears & Fine Calculation -- Start
//     $data['late_submission'] = false;

//     $arrears = 0;
//     $royalty_amount_arrears = 0;
//     $total_after_royalty_arrears = 0;
//     if ($type != 'for_arrears' && $studentInvoice['bank_payment_status'] != 'cancelled') {
//         $calculate_arrears = calculate_arrears($studentInvoice);
//         $arrears = $calculate_arrears['arrears'];
//         $royalty_amount_arrears = $calculate_arrears['royalty_amount'];
//         $total_after_royalty_arrears = $calculate_arrears['total_after_royalty'];
//         // dd(Carbon::now(), ' ', Carbon::parse($studentInvoice['due_date']));
//         if ($arrears && Carbon::now() > Carbon::parse($studentInvoice['due_date']) && $studentInvoice['bank_payment_status'] == 'unpaid') {
//             $data['late_submission'] = true;
//         }
//     }

//     $data['arrears'] = $arrears;
//     $data['after_due_date'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['total'] * 0.025) + $data['total']) + $arrears : $data['total'] + $arrears;
//     $data['total'] += $arrears;

//     $data['royalty_amount'] += $royalty_amount_arrears;
//     $data['after_dd_royalty_amount'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['royalty_amount'] * 0.025) + $data['royalty_amount']) + $royalty_amount_arrears : $data['royalty_amount'] + $royalty_amount_arrears;

//     $data['total_after_royalty'] += $total_after_royalty_arrears;
//     $data['after_dd_total_after_royalty'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['total_after_royalty'] * 0.025) + $data['total_after_royalty']) + $total_after_royalty_arrears : $data['total_after_royalty'] + $total_after_royalty_arrears;

//     // Arrears & Fine Calculation -- End

//     // Concessions on Fee Charges -- Start
//     if (isset($studentInvoice->student_concessions) && $type != 'for_arrears') {
//         $charges_concessions = array();
//         // foreach ($studentInvoice->student_concessions as $concession) {
//         //     $charges_concessions[$concession->fee_charge_id] = $concession->fee_concession->concession_percentage;
//         // }
//         // dd($charges_concessions);
//         $data['charges_concessions'] = $charges_concessions;
//     }
//     // Concessions on Fee Charges -- End

//     return $data;
// }

// function calculate_total_price_by_invoice_index($studentInvoice, $type = null)
// {
//     $totalPrice = 0;
//     $non_refundable_charges = 0;
//     $discountable_charges = 0;
//     $invoices_charges = [
//         'AF' => 0,
//         'TF' => 0,
//         'SD' => 0
//     ];

//     // dump($studentInvoice->student_concessions->toArray());

//     if (isset($studentInvoice['student_invoice_items'])) {
//         foreach ($studentInvoice['student_invoice_items'] as $student_invoice_item) {
//             $amount = isset($student_invoice_item['debit']) ? $student_invoice_item['debit'] : $student_invoice_item['credit'];


//             // Siblings Concession comparison with Student Concession for Admission Charge (AF)
//             if ($studentInvoice['invoice_frequency'] == 'Admission' && $student_invoice_item['fee_charges']['fee_charges_type']['abbreviation'] == 'AF' && $type != 'for_arrears' && isset($studentInvoice['student']['sibling_info'])) {
//                 $sibiling_discount_val = false;
//                 foreach ($studentInvoice->student->sibling_info->family->children as $child) {
//                     if ($child->student->status == 'on_roll') {
//                         $sibiling_discount_val = true;
//                     }
//                 }
//                 // dd($sibiling_discount_val);
//                 if ($sibiling_discount_val == true) {
//                     if (in_array((int) $studentInvoice->student->sibling_info->sibling_no, [2, 3])) {
//                         $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 50;
//                         $max_concession = isset($student_invoice_item['concession']) ? ($student_invoice_item['concession'] > 50 ? $student_invoice_item['concession'] : 50) : 50;
//                         $student_item_amount = $amount - (($amount / 100) * $max_concession);
//                     } else if ($studentInvoice->student->sibling_info->sibling_no > 3) {
//                         $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 100;
//                         $student_item_amount = $amount - $amount;
//                     } else {
//                         $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
//                         //$student_item_amount =  $amount;
//                         $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
//                     }
//                 } else {
//                     $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
//                     //$student_item_amount =  $amount;
//                     $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
//                 }
//                 $invoices_charges[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = $student_item_amount;
//                 $totalPrice = $totalPrice + $student_item_amount;
//             } else {
//                 $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
//                 $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
//                 // dump($student_item_amount);
//                 $invoices_charges[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = $student_item_amount;
//                 $totalPrice = $totalPrice + $student_item_amount;
//             }
//             $non_refundable_charges = !$student_invoice_item['fee_charges']['is_refundable'] ? $non_refundable_charges + $student_item_amount : $non_refundable_charges + 0;
//             $discountable_charges = $student_invoice_item['fee_charges']['is_discountable'] ? $discountable_charges + $student_item_amount : $discountable_charges + 0;
//         }
//     }

//     // dd($totalPrice);

//     $data = [
//         'sub_total' => $totalPrice,
//         'discountable_charges' => $discountable_charges,
//         'non_refundable_charges' => $non_refundable_charges,
//         'sibling_discount_percentage' => isset($sibling_discount['AF']) ? $sibling_discount['AF'] : 0,
//         'invoices_charges' => $invoices_charges
//     ];

//     if ($type != 'for_arrears')
//         $data['charges_concessions'] = 0;


//     // Fee Concession Calculation
//     if (isset($studentInvoice['student_fee_package']['fee_concession']) && !empty($studentInvoice['student_fee_package']['fee_concession'])) {
//         $fee_concession = $studentInvoice['student_fee_package']['fee_concession'];
//         $concessionDiscount = ($discountable_charges / 100) * $fee_concession['concession_percentage'];

//         $data['concession_type'] = $fee_concession['fee_concession_type']['name'];
//         $data['concession_percentage'] = $fee_concession['concession_percentage'];
//         $data['concession_discount'] = $concessionDiscount;
//     } else {
//         $concessionDiscount = 0;
//         $data['concession_type'] = '';
//         $data['concession_percentage'] = 0;
//         $data['concession_discount'] = 0;
//     }

//     // Promo Discount Calculation
//     if (isset($studentInvoice->promo) && !empty($studentInvoice->promo)) {
//         $promoClasses = PromoClass::where('promo_id', $studentInvoice->promo->id)->get();
//         // dd($promoClasses->toArray());

//         $promoApplicable = false;
//         // dd($promoClasses->toArray());

//         foreach ($promoClasses as $key => $value) {
//             if ($value->class_id == $studentInvoice->student_fee_package->com_class->id) {
//                 $promoApplicable = true;
//                 break;
//             }
//         }

//         if ($promoApplicable) {
//             $promoDiscount = $studentInvoice->promo->promo_unit == 'percentage' ? ($totalPrice / 100) * $studentInvoice->promo->promo_amount : $studentInvoice->promo->promo_amount;

//             $data['promo_name'] = $studentInvoice->promo->name;
//             $data['promo_unit'] = $studentInvoice->promo->promo_unit;
//             $data['promo_amount'] = $studentInvoice->promo->promo_amount;
//             $data['promo_discount'] = $promoDiscount;
//         } else {
//             $promoDiscount = 0;
//         }
//     } else {
//         $promoDiscount = 0;
//     }

//     $data['royalty_percentage'] = get_branch_royalty($studentInvoice['student']['branch_id']);
//     /*//Comment above get_branch_royalty line and uncomment this line If want to apply invoice royalty percentage to invoice, Not uncommenting now because Bank payment integration in process
//     //$data['royalty_percentage'] = $studentInvoice['royalty_percentage'];*/
//     $non_refundable_royalty = $non_refundable_charges * ($data['royalty_percentage'] / 100);
//     $concession_royalty = $concessionDiscount * ($data['royalty_percentage'] / 100);
//     $data['royalty_amount'] = $non_refundable_royalty - $concession_royalty;

//     $data['total'] = $totalPrice - ($concessionDiscount + $promoDiscount);
//     $data['total_after_royalty'] = $totalPrice - ($concessionDiscount + $promoDiscount) - $data['royalty_amount'];

//     $diff_month = 1;
//     // if (isset($studentInvoice['fee_period']) && !empty($studentInvoice['fee_period']['to_date']) && !empty($studentInvoice['fee_period']['from_date'])) {
//     //     $to_date = Carbon::parse($studentInvoice['fee_period']['to_date']);
//     //     $diff_month = Carbon::parse($studentInvoice['fee_period']['from_date'])->diffInMonths($to_date);
//     // }

//     //multiplication based on no of months invoice
//     $data['sub_total'] = $data['sub_total'] * $diff_month;
//     //$data['non_refund_non_discountable_charges'] = $data['non_refund_non_discountable_charges'] * $diff_month;
//     $data['concession_discount'] = $data['concession_discount'] * $diff_month;
//     $data['royalty_amount'] = $data['royalty_amount'] * $diff_month;
//     $data['total'] = $data['total'] * $diff_month;
//     $data['diff_month'] = $diff_month;

//     // Arrears & Fine Calculation -- Start
//     $data['late_submission'] = false;

//     $arrears = 0;
//     $royalty_amount_arrears = 0;
//     $total_after_royalty_arrears = 0;
//     if ($type != 'for_arrears' && $studentInvoice['bank_payment_status'] != 'cancelled') {
//         $calculate_arrears = calculate_arrears($studentInvoice);
//         $arrears = $calculate_arrears['arrears'];
//         $royalty_amount_arrears = $calculate_arrears['royalty_amount'];
//         $total_after_royalty_arrears = $calculate_arrears['total_after_royalty'];
//         // dd(Carbon::now(), ' ', Carbon::parse($studentInvoice['due_date']));
//         if ($arrears && Carbon::now() > Carbon::parse($studentInvoice['due_date']) && $studentInvoice['bank_payment_status'] == 'unpaid') {
//             $data['late_submission'] = true;
//         }
//     }

//     $data['arrears'] = $arrears;
//     $data['after_due_date'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['total'] * 0.025) + $data['total']) + $arrears : $data['total'] + $arrears;
//     $data['total'] += $arrears;

//     $data['royalty_amount'] += $royalty_amount_arrears;
//     $data['after_dd_royalty_amount'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['royalty_amount'] * 0.025) + $data['royalty_amount']) + $royalty_amount_arrears : $data['royalty_amount'] + $royalty_amount_arrears;

//     $data['total_after_royalty'] += $total_after_royalty_arrears;
//     $data['after_dd_total_after_royalty'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['total_after_royalty'] * 0.025) + $data['total_after_royalty']) + $total_after_royalty_arrears : $data['total_after_royalty'] + $total_after_royalty_arrears;

//     // Arrears & Fine Calculation -- End

//     // Concessions on Fee Charges -- Start
//     if (isset($studentInvoice->student_concessions) && $type != 'for_arrears') {
//         $charges_concessions = array();
//         // foreach ($studentInvoice->student_concessions as $concession) {
//         //     $charges_concessions[$concession->fee_charge_id] = $concession->fee_concession->concession_percentage;
//         // }
//         // dd($charges_concessions);
//         $data['charges_concessions'] = $charges_concessions;
//     }
//     // Concessions on Fee Charges -- End
//     // dump($data);
//     return $data;
// }

// function calculate_arrears($studentInvoice)
// {
//     // if($studentInvoice['id'] == 93) dd(isset($studentInvoice['student']['active_class']));

//     if (!isset($studentInvoice['student']['active_class']))
//         return ['arrears' => 0, 'royalty_amount' => 0, 'total_after_royalty' => 0];

//     // $student_active_class = $studentInvoice['student']['active_class'];
//     // $student_ledger = StudentLedger::where(['class_student_id' => $student_active_class['id']])->first();


//     $current_invoice_entry = StudentLedgerInvoice::where(['student_invoice_id' => $studentInvoice['id']])->first();
//     // dump($current_invoice_entry->ledger->toArray());
//     $student_ledger = $current_invoice_entry->ledger;
//     // $current_invoice_entry = $student_ledger->ledger_invoices()->where(['student_invoice_id' => $studentInvoice['id']])->first();
//     //$current_invoice_entry = $student_ledger->ledger_invoices()->first();

//     $unpaid_invoices = $student_ledger->ledger_invoices()->where('sort', '<', $current_invoice_entry->sort)->whereNotNull('student_invoice_id')->whereHas('invoice', function ($query) {
//         $query->where(['bank_payment_status' => 'unpaid', 'is_paid' => 0]);
//     })->with([
//             'invoice.student',
//             'invoice.student.active_class',
//             'invoice.student_fee_package.fee_package',
//             'invoice.student_fee_package.fee_concession.fee_concession_type',
//             'invoice.student_fee_package.academic_year',
//             'invoice.student_fee_package.com_class',
//             'invoice.student_fee_package.section',
//             'invoice.student_invoice_items.fee_charges.fee_charges_type',
//             'invoice.invoice_type',
//             'invoice.payment_source',
//             'invoice.promo.promo_type',
//             'invoice.fee_period'
//         ])->get();

//     $arrears = 0;
//     $royalty_amount = 0;
//     $total_after_royalty = 0;

//     if (empty($unpaid_invoices))
//         return $arrears;
//     foreach ($unpaid_invoices as $unpaid_invoice) {
//         // dump('This is index of: ' . $studentInvoice['invoice_no'] . ' for ' . $key);
//         $inv_data = $unpaid_invoice->toArray()['invoice'];
//         // dd($inv_data);
//         if (get_month_name($inv_data['fee_period']['from_date']) == 'February') {
//             $calculation = calculate_total_price_by_invoice_index($inv_data, 'for_arrears');
//         } else {
//             $calculation = calculate_total_price_by_invoice($inv_data, 'for_arrears');
//         }
//         // $calculation = calculate_total_price_by_invoice($inv_data, 'for_arrears');
//         if ($studentInvoice['arrears_fine']) {
//             $arrears += ($calculation['total'] * 0.05) + $calculation['total'];
//             $royalty_amount += ($calculation['royalty_amount'] * 0.05) + $calculation['royalty_amount'];
//             $total_after_royalty += ($calculation['total_after_royalty'] * 0.05) + $calculation['total_after_royalty'];
//         } else {
//             $arrears += $calculation['total'];
//             $royalty_amount += $calculation['royalty_amount'];
//             $total_after_royalty += $calculation['total_after_royalty'];
//         }


//         // dump('Total Price: ' . $inv_data['invoice_no'] . ' ' . $calculation['total']);
//     }

//     return ['arrears' => $arrears, 'royalty_amount' => $royalty_amount, 'total_after_royalty' => $total_after_royalty];
// }

function get_branch_class_ids($branch_id)
{
    $branch_class_ids = \App\Models\BranchClass::where('branch_id', $branch_id)->pluck('class_id')->toArray();

    return $branch_class_ids;
}

function get_branch_classes($branch_id)
{
    $branch_class_ids = get_branch_class_ids($branch_id);
    $branch_classes = ComClass::whereIn('id', $branch_class_ids)->get();

    return $branch_classes;
}

function parse_date($date, $format)
{
    return Carbon::parse($date)->format($format);
}

function get_month_diff($from, $to)
{
    $to_date = Carbon::parse($to)->addDays(2);
    return Carbon::parse($from)->diffInMonths($to_date);
}

function get_month_name($date)
{
    return Carbon::parse($date)->format('F');
}

function get_user_detail($id)
{
    $user = User::where('id', $id)->first('name');
    return $user->name;
}

function get_login_user_role()
{
    $user_role = User::where('id', auth()->user()->id)->with('roles')->first();
    return $user_role['roles'][0]->name;
}

function get_user_role($id)
{
    $user_role = User::where('id', $id)->with('roles')->first();
    return $user_role['roles'][0]->name;
}

function get_active_student_count($branch_id = 0)
{
    $count = \App\Models\Student::where(function ($q) use ($branch_id) {
        if ($branch_id)
            $q->where('branch_id', $branch_id);
    })->where('status', 'on_roll')->count();

    return $count;
}

function show_documents($franchise_application_id)
{
    //dd($franchise_application_id);
    $details = FranchiseApplicationsAttachment::with(['user', 'attachment_type'])->where(['franchise_application_id' => $franchise_application_id]);
    //dd($details->get()->toArray());
    if (!auth()->user())
        $details = $details->whereNull('uploaded_by');

    $documents = $details->get();

    return $documents;

}

function get_new_register_student_count($branch_id = 0)
{
    $academic_year_id = AcademicYear::where('active', '1')->pluck('id');
    $count = \App\Models\Student::where(function ($q) use ($branch_id) {
        if ($branch_id)
            $q->where('branch_id', $branch_id);
    })->where('status', 'registered')->where('admission_year_id', $academic_year_id)->count();

    return $count;
}
function get_register_student_count($academic_year_id, $branch_id = 0)
{
    $count = \App\Models\Student::where(function ($q) use ($branch_id) {
        if ($branch_id)
            $q->where('branch_id', $branch_id);
    })->where('admission_year_id', $academic_year_id)->count();
    return $count;
}
function get_branch_id_from_code($branch_code)
{
    $branch = Branch::where('branch_code', $branch_code)->first();
    return $branch ? $branch->id : 0;
}

function get_branch_id_for_employee()
{
    if (auth()->user()->hasRole('network_assciate'))
        return 0;
    return auth()->user()->employee ? auth()->user()->employee->branch_id : 0;
}

function get_branch_code_for_employee()
{
    if (auth()->user()->hasRole('network_assciate'))
        return 0;
    return isset(auth()->user()->employee->branch) ? auth()->user()->employee->branch->branch_code : 0;
}

function get_branch_id()
{
    if ($nwa_branch_id = get_set_NWABranchId())
        return $nwa_branch_id;
    elseif ($emp_branch_id = get_branch_id_for_employee())
        return $emp_branch_id;
    else
        return 0;
}
function get_region_id()
{
    $user = auth()->user();
    $region_id = $user['employee'] ? $user['employee']['region_id'] : 0;
    return number_format($region_id);
}

function get_branch_code()
{
    if ($nwa_branch_code = get_NWABranchCode())
        return $nwa_branch_code;
    elseif ($emp_branch_code = get_branch_code_for_employee())
        return $emp_branch_code;
    else
        return 0;
}

function get_state_id()
{
    $user = auth()->user();
    $state_id = 0;
    if (auth()->user()->hasRole('network_associate')) {
        $branch_id = get_set_NWABranchId();
        $branch = Branch::with(['contact_information'])->where('id', $branch_id)->first();
        $state_id = isset($branch['contact_information']) ? $branch['contact_information']['state_id'] : 0;
    } else {
        $state_id = $user['employee'] ? $user['employee']['region_id'] : 0;
    }

    return $state_id;
}
function get_branch_state_id($branchId = 0)
{

    $branch_id = $branchId ? $branchId : get_branch_id();
    $branch = Branch::with(['contact_information'])->where('id', $branch_id)->first();
    $state_id = isset($branch['contact_information']) ? $branch['contact_information']['state_id'] : 0;

    return $state_id;
}

function generate_challans_pdf($invoiceIds)
{

    $invoices = [];

    if (is_array($invoiceIds)) {
        foreach ($invoiceIds as $invoiceId) {
            $studentInvoice = StudentInvoice::where(
                'id',
                $invoiceId
            )->with([
                        'student.branch.class_group',
                        'student.branch.bank_accounts',
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
                        'student_address'
                    ])->first();
            $data['studentInvoice'] = $studentInvoice;
            // $data['calculations'] = calculate_total_price_by_invoice($studentInvoice);
            // dd($data['calculations']);
            if (get_month_name($data['studentInvoice']['fee_period']['from_date']) == 'February') {
                $data['calculations'] = calculate_total_price_by_invoice_index($studentInvoice);
                $invoices = array_merge($invoices, [['studentInvoice' => $studentInvoice, 'calculations' => calculate_total_price_by_invoice_index($studentInvoice)]]);
            } else {
                $data['calculations'] = calculate_total_price_by_invoice($studentInvoice);
                $invoices = array_merge($invoices, [['studentInvoice' => $studentInvoice, 'calculations' => calculate_total_price_by_invoice($studentInvoice)]]);
            }
        }
    } else {
    }

    return $invoices;
    // $pdf = Pdf::loadView('students.student_challan', ['invoices' => $invoices])->setPaper('a4', 'portrait');
    // // return $pdf->download($data['studentInvoice']['student']['last_name'] . '_challan.pdf');
    // return $pdf->stream($data['studentInvoice']['student']['last_name'] . ' Challan.pdf');
}

function getUniqueFileName($filename_data, $counter = 1)
{

    $filename = $filename_data['filename'] . '.' . $filename_data['extension'];
    if (in_array($filename, $filename_data['filenames_arr'])) {
        $filename_data['default_filename'] = isset($filename_data['default_filename']) ? $filename_data['default_filename'] : $filename_data['filename'];
        $filename_data['filename'] = $filename_data['default_filename'] . ' (' . date('d-m-Y') . time() . ')';

        return getUniqueFileName($filename_data, $counter + 1);
    } else {
        return $filename;
    }
}

function str_ordinal($value, $superscript = false)
{
    $number = abs($value);

    $indicators = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

    $suffix = $superscript ? '<sup>' . $indicators[$number % 10] . '</sup>' : $indicators[$number % 10];
    if ($number % 100 >= 11 && $number % 100 <= 13) {
        $suffix = $superscript ? '<sup>th</sup>' : 'th';
    }

    return number_format($number) . $suffix;
}

function calculate_total_price_by_invoice_royalty($studentInvoice, $type = null)
{
    $totalPrice = 0;
    $non_refundable_charges = 0;
    $discountable_charges = 0;
    $invoices_charges = [
        'AF' => 0,
        'TF' => 0,
        'SD' => 0
    ];

    //dd($studentInvoice->toArray());

    if (isset($studentInvoice[0]->student_invoice_items)) {
        foreach ($studentInvoice['student_invoice_items'] as $student_invoice_item) {
            $amount = isset($student_invoice_item['debit']) ? $student_invoice_item['debit'] : $student_invoice_item['credit'];


            // Siblings Concession comparison with Student Concession for Admission Charge (AF)
            if ($studentInvoice['invoice_frequency'] == 'Admission' && $student_invoice_item['fee_charges']['fee_charges_type']['abbreviation'] == 'AF' && $type != 'for_arrears' && isset($studentInvoice['student']['sibling_info'])) {
                $sibiling_discount_val = false;
                foreach ($studentInvoice->student->sibling_info->family->children as $child) {
                    if (isset($child->student->status) && $child->student->status == 'on_roll') {
                        $sibiling_discount_val = true;
                    }
                }
                // dd($sibiling_discount_val);
                if ($sibiling_discount_val == true) {
                    if (in_array((int) $studentInvoice->student->sibling_info->sibling_no, [2, 3])) {
                        $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 50;
                        $max_concession = isset($student_invoice_item['concession']) ? ($student_invoice_item['concession'] > 50 ? $student_invoice_item['concession'] : 50) : 50;
                        $student_item_amount = $amount - (($amount / 100) * $max_concession);
                    } else if ($studentInvoice->student->sibling_info->sibling_no > 3) {
                        $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 100;
                        $student_item_amount = $amount - $amount;
                    } else {
                        $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
                        //$student_item_amount =  $amount;
                        $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
                    }
                } else {
                    $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
                    //$student_item_amount =  $amount;
                    $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
                }
                $invoices_charges[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = $student_item_amount;
                $totalPrice = $totalPrice + $student_item_amount;
            } else {
                $sibling_discount[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = 0;
                $student_item_amount = isset($student_invoice_item['concession']) ? $amount - (($amount / 100) * $student_invoice_item['concession']) : $amount;
                // dump($student_item_amount);
                $invoices_charges[$student_invoice_item['fee_charges']['fee_charges_type']['abbreviation']] = $student_item_amount;
                $totalPrice = $totalPrice + $student_item_amount;
            }
            $non_refundable_charges = !$student_invoice_item['fee_charges']['is_refundable'] ? $non_refundable_charges + $student_item_amount : $non_refundable_charges + 0;
            $discountable_charges = $student_invoice_item['fee_charges']['is_discountable'] ? $discountable_charges + $student_item_amount : $discountable_charges + 0;
        }
    }

    // dd($totalPrice);

    $data = [
        'sub_total' => $totalPrice,
        'discountable_charges' => $discountable_charges,
        'non_refundable_charges' => $non_refundable_charges,
        'sibling_discount_percentage' => isset($sibling_discount['AF']) ? $sibling_discount['AF'] : 0,
        'invoices_charges' => $invoices_charges
    ];

    if ($type != 'for_arrears')
        $data['charges_concessions'] = 0;


    // Fee Concession Calculation
    if (isset($studentInvoice['student_fee_package']['fee_concession']) && !empty($studentInvoice['student_fee_package']['fee_concession'])) {
        $fee_concession = $studentInvoice['student_fee_package']['fee_concession'];
        $concessionDiscount = ($discountable_charges / 100) * $fee_concession['concession_percentage'];

        $data['concession_type'] = $fee_concession['fee_concession_type']['name'];
        $data['concession_percentage'] = $fee_concession['concession_percentage'];
        $data['concession_discount'] = $concessionDiscount;
    } else {
        $concessionDiscount = 0;
        $data['concession_type'] = '';
        $data['concession_percentage'] = 0;
        $data['concession_discount'] = 0;
    }

    // Promo Discount Calculation
    if (isset($studentInvoice->promo) && !empty($studentInvoice->promo)) {
        $promoClasses = PromoClass::where('promo_id', $studentInvoice->promo->id)->get();
        // dd($promoClasses->toArray());

        $promoApplicable = false;
        // dd($promoClasses->toArray());

        foreach ($promoClasses as $key => $value) {
            if ($value->class_id == $studentInvoice->student_fee_package->com_class->id) {
                $promoApplicable = true;
                break;
            }
        }

        if ($promoApplicable) {
            $promoDiscount = $studentInvoice->promo->promo_unit == 'percentage' ? ($totalPrice / 100) * $studentInvoice->promo->promo_amount : $studentInvoice->promo->promo_amount;

            $data['promo_name'] = $studentInvoice->promo->name;
            $data['promo_unit'] = $studentInvoice->promo->promo_unit;
            $data['promo_amount'] = $studentInvoice->promo->promo_amount;
            $data['promo_discount'] = $promoDiscount;
        } else {
            $promoDiscount = 0;
        }
    } else {
        $promoDiscount = 0;
    }

    // dd($studentInvoice[0]->student_id);
    //Get branch ID if not found in student
    if (isset($studentInvoice['student']['branch_id'])) {
        $branchId = $studentInvoice['student']['branch_id'];
    } else {
        $studentInfo = \App\Models\Student::where('id', $studentInvoice[0]->student_id)->first();
        $branchId = $studentInfo[0]->branch_id;
    }

    $data['royalty_percentage'] = get_branch_royalty($branchId);
    /*//Comment above get_branch_royalty line and uncomment this line If want to apply invoice royalty percentage to invoice, Not uncommenting now because Bank payment integration in process
    //$data['royalty_percentage'] = $studentInvoice['royalty_percentage'];*/
    $non_refundable_royalty = $non_refundable_charges * ($data['royalty_percentage'] / 100);
    $concession_royalty = $concessionDiscount * ($data['royalty_percentage'] / 100);
    $data['royalty_amount'] = $non_refundable_royalty - $concession_royalty;

    $data['total'] = $totalPrice - ($concessionDiscount + $promoDiscount);
    $data['total_after_royalty'] = $totalPrice - ($concessionDiscount + $promoDiscount) - $data['royalty_amount'];

    $diff_month = 1;
    if (isset($studentInvoice['fee_period']) && !empty($studentInvoice['fee_period']['to_date']) && !empty($studentInvoice['fee_period']['from_date'])) {
        $to_date = Carbon::parse($studentInvoice['fee_period']['to_date'])->addDays(2);
        $diff_month = Carbon::parse($studentInvoice['fee_period']['from_date'])->diffInMonths($to_date);
    }

    //multiplication based on no of months invoice
    $data['sub_total'] = $data['sub_total'] * $diff_month;
    //$data['non_refund_non_discountable_charges'] = $data['non_refund_non_discountable_charges'] * $diff_month;
    $data['concession_discount'] = $data['concession_discount'] * $diff_month;
    $data['royalty_amount'] = $data['royalty_amount'] * $diff_month;
    $data['total'] = $data['total'] * $diff_month;
    $data['diff_month'] = $diff_month;

    // Arrears & Fine Calculation -- Start
    $data['late_submission'] = false;

    $arrears = 0;
    $royalty_amount_arrears = 0;
    $total_after_royalty_arrears = 0;
    // dd($studentInvoice);
    if ($type != 'for_arrears' && $studentInvoice[0]->bank_payment_status != 'cancelled') {
        $calculate_invoice_arrears = calculate_arrears($studentInvoice);
        $arrears = $calculate_invoice_arrears['arrears'];
        $royalty_amount_arrears = $calculate_invoice_arrears['royalty_amount'];
        $total_after_royalty_arrears = $calculate_invoice_arrears['total_after_royalty'];
        // dd(Carbon::now(), ' ', Carbon::parse($studentInvoice['due_date']));
        if ($arrears && Carbon::now() > Carbon::parse($studentInvoice['due_date']) && $studentInvoice['bank_payment_status'] == 'unpaid') {
            $data['late_submission'] = true;
        }
    }


    $data['arrears'] = $arrears;
    $data['after_due_date'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['total'] * 0.025) + $data['total']) + $arrears : $data['total'] + $arrears;
    $data['total'] += $arrears;

    $data['royalty_amount'] += $royalty_amount_arrears;
    $data['after_dd_royalty_amount'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['royalty_amount'] * 0.025) + $data['royalty_amount']) + $royalty_amount_arrears : $data['royalty_amount'] + $royalty_amount_arrears;

    $data['total_after_royalty'] += $total_after_royalty_arrears;
    $data['after_dd_total_after_royalty'] = isset($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name']) && $studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] == 'Monthly' && $studentInvoice['due_date_fine'] ? (($data['total_after_royalty'] * 0.025) + $data['total_after_royalty']) + $total_after_royalty_arrears : $data['total_after_royalty'] + $total_after_royalty_arrears;

    // Arrears & Fine Calculation -- End

    // Concessions on Fee Charges -- Start
    if (isset($studentInvoice->student_concessions) && $type != 'for_arrears') {
        $charges_concessions = array();
        // foreach ($studentInvoice->student_concessions as $concession) {
        //     $charges_concessions[$concession->fee_charge_id] = $concession->fee_concession->concession_percentage;
        // }
        // dd($charges_concessions);
        $data['charges_concessions'] = $charges_concessions;
    }
    // Concessions on Fee Charges -- End

    return $data;
}

function calculate_total_price_by_invoice($studentInvoice, $type = null)
{
    $totalPrice = 0;
    $nonRefundableCharges = 0;
    $discountableCharges = 0;
    $siblingDiscount = [];
    $invoicesCharges = ['AF' => 0, 'TF' => 0, 'SD' => 0];

    // Handle both array and object data structures
    $invoiceItems = [];
    if (is_object($studentInvoice) && method_exists($studentInvoice, 'items')) {
        // New normalized structure - load relationships if needed
        if (!$studentInvoice->relationLoaded('items')) {
            $studentInvoice->load(['items.fee_charges.fee_charges_type', 'payments', 'arrears_carried_from']);
        }
        $invoiceItems = $studentInvoice->items;
    } else {
        // Old array structure
        $invoiceItems = $studentInvoice['student_invoice_items'] ?? [];
    }

    foreach ($invoiceItems as $item) {
        // Handle both object and array access
        $amount = is_object($item) ? ($item->debit ?? $item->credit ?? 0) : ($item['debit'] ?? $item['credit'] ?? 0);
        $abbr = is_object($item) ? ($item->fee_charges->fee_charges_type->abbreviation ?? null) : ($item['fee_charges']['fee_charges_type']['abbreviation'] ?? null);
        $concession = is_object($item) ? ($item->concession ?? 0) : ($item['concession'] ?? 0);

        $discount = $concession;
        $invoiceFrequency = is_object($studentInvoice) ? $studentInvoice->invoice_frequency : ($studentInvoice['invoice_frequency'] ?? '');
        $studentData = is_object($studentInvoice) ? $studentInvoice->student : ($studentInvoice['student'] ?? []);
        
        if (
            $invoiceFrequency === 'Admission' &&
            $abbr === 'AF' &&
            $type !== 'for_arrears' &&
            isset($studentData['sibling_info'])
        ) {
            $siblings = $studentData['sibling_info']['family']['children'] ?? [];
            $onRollSibling = collect($siblings)->contains(fn($child) => ($child['student']['status'] ?? null) === 'on_roll');
            $siblingNo = (int) ($studentData['sibling_info']['sibling_no'] ?? 0);
            if ($onRollSibling) {
                $discount = ($siblingNo > 3) ? 100 : (($siblingNo >= 2) ? max($concession, 50) : $concession);
            }
        }

        $studentItemAmount = $amount - (($amount / 100) * $discount);
        $siblingDiscount[$abbr] = $abbr === 'AF' ? $discount : 0;
        $invoicesCharges[$abbr] = $studentItemAmount;
        $totalPrice += $studentItemAmount;

        $isRefundable = is_object($item) ? ($item->fee_charges->is_refundable ?? true) : ($item['fee_charges']['is_refundable'] ?? true);
        $isDiscountable = is_object($item) ? ($item->fee_charges->is_discountable ?? false) : ($item['fee_charges']['is_discountable'] ?? false);

        if (!$isRefundable) {
            $nonRefundableCharges += $studentItemAmount;
        }
        if ($isDiscountable) {
            $discountableCharges += $studentItemAmount;
        }
    }

    // Handle fee concession data
    $feeConcession = [];
    if (is_object($studentInvoice)) {
        $feeConcession = $studentInvoice->student_fee_package->fee_concession ?? [];
    } else {
        $feeConcession = $studentInvoice['student_fee_package']['fee_concession'] ?? [];
    }
    
    $concessionPercentage = is_object($feeConcession) ? ($feeConcession->concession_percentage ?? 0) : ($feeConcession['concession_percentage'] ?? 0);
    $concessionDiscount = ($concessionPercentage * $discountableCharges) / 100;

    $promoDiscount = 0;
    $promo = is_object($studentInvoice) ? $studentInvoice->promo : ($studentInvoice['promo'] ?? null);
    if (!empty($promo)) {
        $promoId = is_object($promo) ? $promo->id : $promo['id'];
        $classId = is_object($studentInvoice) ? 
            ($studentInvoice->student_fee_package->com_class->id ?? null) : 
            ($studentInvoice['student_fee_package']['com_class']['id'] ?? null);
            
        $promoClassExists = PromoClass::where('promo_id', $promoId)
            ->where('class_id', $classId)
            ->exists();
        if ($promoClassExists) {
            $promoUnit = is_object($promo) ? $promo->promo_unit : ($promo['promo_unit'] ?? '');
            $promoAmount = is_object($promo) ? $promo->promo_amount : ($promo['promo_amount'] ?? 0);
            $promoDiscount = $promoUnit === 'percentage'
                ? ($totalPrice * $promoAmount) / 100
                : $promoAmount;
        }
    }

    $netTotal = $totalPrice - $concessionDiscount - $promoDiscount;
    $branchId = is_object($studentInvoice) ? 
        ($studentInvoice->student->branch_id ?? optional(Student::find($studentInvoice->student_id))->branch_id) :
        ($studentInvoice['student']['branch_id'] ?? optional(Student::find($studentInvoice['student_id']))->branch_id);
    $royaltyPercentage = get_branch_royalty($branchId);
    $royaltyAmount = ($nonRefundableCharges - $concessionDiscount) * ($royaltyPercentage / 100);

    $diffMonth = 1;
    $feePeriod = is_object($studentInvoice) ? $studentInvoice->fee_period : ($studentInvoice['fee_period'] ?? []);
    if (!empty($feePeriod)) {
        $fromDate = is_object($feePeriod) ? $feePeriod->from_date : ($feePeriod['from_date'] ?? null);
        $toDate = is_object($feePeriod) ? $feePeriod->to_date : ($feePeriod['to_date'] ?? null);
        if ($fromDate && $toDate) {
            $diffMonth = Carbon::parse($fromDate)
                ->diffInMonths(Carbon::parse($toDate)->addDays(2));
        }
    }

    // Define manually before multiplying
    $sub_total = $totalPrice;
    $concession_discount = $concessionDiscount;
    $total = $netTotal;
    $royalty_amount = $royaltyAmount;

    $sub_total *= $diffMonth;
    $concession_discount *= $diffMonth;
    $total *= $diffMonth;
    $royalty_amount *= $diffMonth;

    // Get arrears from new normalized structure if available, otherwise fall back to old method
    $arrearData = ['arrears' => 0, 'royalty_amount' => 0, 'total_after_royalty' => 0, 'arrear_months' => []];
    
    $bankPaymentStatus = is_object($studentInvoice) ? $studentInvoice->bank_payment_status : ($studentInvoice['bank_payment_status'] ?? null);
    if ($type !== 'for_arrears' && $bankPaymentStatus !== 'cancelled') {
        // Check if this is a model instance with relationships (new structure)
        if (is_object($studentInvoice) && method_exists($studentInvoice, 'arrears_carried_from')) {
            $arrearsAmount = $studentInvoice->arrears_carried_from->sum('amount');
            $arrearMonths = $studentInvoice->arrears_carried_from->pluck('from_invoice_id')
                ->map(function($invoiceId) {
                    $invoice = StudentInvoice::find($invoiceId);
                    return $invoice && $invoice->fee_period ? 
                        Carbon::parse($invoice->fee_period->from_date)->format('F') : null;
                })
                ->filter()
                ->unique()
                ->values()
                ->toArray();
            
            $arrearData = [
                'arrears' => $arrearsAmount,
                'royalty_amount' => $arrearsAmount * ($royaltyPercentage / 100),
                'total_after_royalty' => $arrearsAmount - ($arrearsAmount * ($royaltyPercentage / 100)),
                'arrear_months' => $arrearMonths
            ];
        } else {
            // Fall back to old method for array data
            $arrearData = calculate_arrears($studentInvoice);
        }
    }

    $total += $arrearData['arrears'];
    $royalty_amount += $arrearData['royalty_amount'];

    $feePackageType = is_object($studentInvoice) ? 
        ($studentInvoice->student_fee_package->fee_package->fee_package_type->name ?? '') :
        ($studentInvoice['student_fee_package']['fee_package']['fee_package_type']['name'] ?? '');
    $isMonthly = $feePackageType === 'Monthly';
    $fineApplicable = is_object($studentInvoice) ? $studentInvoice->due_date_fine : ($studentInvoice['due_date_fine'] ?? false);
    $fineAmount = ($isMonthly && $fineApplicable) ? ($sub_total * 0.025) : 0;

    $concessionType = is_object($feeConcession) ? ($feeConcession->fee_concession_type->name ?? '') : ($feeConcession['fee_concession_type']['name'] ?? '');

    return [
        'sub_total' => $sub_total,
        'discountable_charges' => $discountableCharges,
        'non_refundable_charges' => $nonRefundableCharges,
        'sibling_discount_percentage' => $siblingDiscount['AF'] ?? 0,
        'invoices_charges' => $invoicesCharges,
        'concession_type' => $concessionType,
        'concession_percentage' => $concessionPercentage,
        'concession_discount' => $concession_discount,
        'promo_discount' => $promoDiscount,
        'royalty_percentage' => $royaltyPercentage,
        'royalty_amount' => $royalty_amount,
        'arrears' => $arrearData['arrears'],
        'arrear_months' => $arrearData['arrear_months'],
        'total' => $total,
        'total_after_royalty' => $total - $royalty_amount,
        'due_date_fine' => $fineAmount,
        'after_due_date' => $total + $fineAmount,
        'after_dd_royalty_amount' => $royalty_amount + ($fineAmount * ($royaltyPercentage / 100)),
        'after_dd_total_after_royalty' => ($total - $royalty_amount) + $fineAmount,
        'diff_month' => $diffMonth
    ];
}


function calculate_total_price_by_invoice_index($studentInvoice, $type = null, $promoClassesCache = [])
{
    $totalPrice = 0;
    $nonRefundableCharges = 0;
    $discountableCharges = 0;
    $invoiceCharges = ['AF' => 0, 'TF' => 0, 'SD' => 0];
    $siblingDiscounts = [];

    // Handle both array and object data structures
    $invoiceItems = [];
    if (is_object($studentInvoice) && method_exists($studentInvoice, 'items')) {
        // New normalized structure - load relationships if needed
        if (!$studentInvoice->relationLoaded('items')) {
            $studentInvoice->load(['items.fee_charges.fee_charges_type', 'payments', 'arrears_carried_from']);
        }
        $invoiceItems = $studentInvoice->items;
    } else {
        // Old array structure
        $invoiceItems = $studentInvoice['student_invoice_items'] ?? [];
    }

    foreach ($invoiceItems as $item) {
        // Handle both object and array access
        $chargeType = is_object($item) ? ($item->fee_charges->fee_charges_type->abbreviation ?? 'GEN') : ($item['fee_charges']['fee_charges_type']['abbreviation'] ?? 'GEN');
        $amount = is_object($item) ? ($item->debit ?? $item->credit ?? 0) : ($item['debit'] ?? $item['credit'] ?? 0);
        $concession = (float) (is_object($item) ? ($item->concession ?? 0) : ($item['concession'] ?? 0));
        $isRefundable = (bool) (is_object($item) ? ($item->fee_charges->is_refundable ?? false) : ($item['fee_charges']['is_refundable'] ?? false));
        $isDiscountable = (bool) (is_object($item) ? ($item->fee_charges->is_discountable ?? false) : ($item['fee_charges']['is_discountable'] ?? false));

        $studentAmount = $amount;
        $invoiceFrequency = is_object($studentInvoice) ? $studentInvoice->invoice_frequency : ($studentInvoice['invoice_frequency'] ?? '');
        $studentData = is_object($studentInvoice) ? $studentInvoice->student : ($studentInvoice['student'] ?? []);
        
        if ($invoiceFrequency === 'Admission' && $chargeType === 'AF' && $type !== 'for_arrears') {
            $siblings = $studentData['sibling_info']['family']['children'] ?? [];
            $onRollSiblings = collect($siblings)->filter(fn($c) => optional($c['student'])['status'] === 'on_roll')->count();
            $siblingNo = $studentData['sibling_info']['sibling_no'] ?? 1;

            if ($onRollSiblings > 0) {
                $siblingDiscount = match (true) {
                    $siblingNo === 2 || $siblingNo === 3 => 50,
                    $siblingNo > 3 => 100,
                    default => 0,
                };
                $maxConcession = max($concession, $siblingDiscount);
                $studentAmount -= ($amount * $maxConcession / 100);
            } else {
                $studentAmount -= ($amount * $concession / 100);
            }
        } else {
            $studentAmount -= ($amount * $concession / 100);
        }

        $siblingDiscounts[$chargeType] = $chargeType === 'AF' ? ($siblingDiscount ?? 0) : 0;
        $invoiceCharges[$chargeType] += $studentAmount;
        $totalPrice += $studentAmount;

        if (!$isRefundable)
            $nonRefundableCharges += $studentAmount;
        if ($isDiscountable)
            $discountableCharges += $studentAmount;
    }

    // Handle fee concession data
    $feeConcession = [];
    if (is_object($studentInvoice)) {
        $feeConcession = $studentInvoice->student_fee_package->fee_concession ?? [];
    } else {
        $feeConcession = $studentInvoice['student_fee_package']['fee_concession'] ?? [];
    }
    
    $concessionPercentage = (float) (is_object($feeConcession) ? ($feeConcession->concession_percentage ?? 0) : ($feeConcession['concession_percentage'] ?? 0));
    $concessionDiscount = ($discountableCharges * $concessionPercentage) / 100;

    $promoDiscount = 0;
    $promo = is_object($studentInvoice) ? $studentInvoice->promo : ($studentInvoice['promo'] ?? null);
    if (!empty($promo)) {
        $promoId = is_object($promo) ? $promo->id : $promo['id'];
        $classId = is_object($studentInvoice) ? 
            ($studentInvoice->student_fee_package->com_class->id ?? null) : 
            ($studentInvoice['student_fee_package']['com_class']['id'] ?? null);

        if (!isset($promoClassesCache[$promoId])) {
            $promoClassesCache[$promoId] = PromoClass::where('promo_id', $promoId)->pluck('class_id')->toArray();
        }

        if (in_array($classId, $promoClassesCache[$promoId])) {
            $promoUnit = is_object($promo) ? $promo->promo_unit : ($promo['promo_unit'] ?? '');
            $promoAmount = is_object($promo) ? $promo->promo_amount : ($promo['promo_amount'] ?? 0);
            $promoDiscount = $promoUnit === 'percentage'
                ? ($totalPrice * $promoAmount) / 100
                : $promoAmount;
        }
    }

    $netTotal = $totalPrice - $concessionDiscount - $promoDiscount;
    $studentBranchId = is_object($studentInvoice) ? 
        (optional($studentInvoice->student)->branch_id) :
        (optional($studentInvoice['student'])['branch_id']);
    $royaltyPercentage = get_branch_royalty($studentBranchId);
    $royaltyAmount = ($nonRefundableCharges - $concessionDiscount) * ($royaltyPercentage / 100);

    $diffMonth = 1;
    $feePeriod = is_object($studentInvoice) ? $studentInvoice->fee_period : ($studentInvoice['fee_period'] ?? []);
    if (!empty($feePeriod)) {
        $fromDate = is_object($feePeriod) ? $feePeriod->from_date : ($feePeriod['from_date'] ?? null);
        $toDate = is_object($feePeriod) ? $feePeriod->to_date : ($feePeriod['to_date'] ?? null);
        if ($fromDate && $toDate) {
            $from = Carbon::parse($fromDate);
            $to = Carbon::parse($toDate);
            $diffMonth = max($from->diffInMonths($to) + 1, 1);
        }
    }

    // Assign before multiplying
    $sub_total = $totalPrice;
    $concession_discount = $concessionDiscount;
    $total = $netTotal;
    $royalty_amount = $royaltyAmount;

    $sub_total *= $diffMonth;
    $concession_discount *= $diffMonth;
    $total *= $diffMonth;
    $royalty_amount *= $diffMonth;

    // Get arrears from new normalized structure if available, otherwise fall back to old method
    $arrearsData = ['arrears' => 0, 'royalty_amount' => 0, 'total_after_royalty' => 0];
    
    $bankPaymentStatus = is_object($studentInvoice) ? $studentInvoice->bank_payment_status : ($studentInvoice['bank_payment_status'] ?? '');
    if ($type !== 'for_arrears' && $bankPaymentStatus !== 'cancelled') {
        // Check if this is a model instance with relationships (new structure)
        if (is_object($studentInvoice) && method_exists($studentInvoice, 'arrears_carried_from')) {
            $arrearsAmount = $studentInvoice->arrears_carried_from->sum('amount');
            $arrearsData = [
                'arrears' => $arrearsAmount,
                'royalty_amount' => $arrearsAmount * ($royaltyPercentage / 100),
                'total_after_royalty' => $arrearsAmount - ($arrearsAmount * ($royaltyPercentage / 100))
            ];
        } else {
            // Fall back to old method for array data
            $arrearsData = calculate_arrears($studentInvoice);
        }
    }

    $feePackageType = is_object($studentInvoice) ? 
        (optional($studentInvoice->student_fee_package->fee_package->fee_package_type)->name) :
        (optional($studentInvoice['student_fee_package']['fee_package']['fee_package_type'])['name']);
    $isMonthly = $feePackageType === 'Monthly';
    $hasFine = (bool) (is_object($studentInvoice) ? $studentInvoice->due_date_fine : ($studentInvoice['due_date_fine'] ?? false));

    $concessionType = is_object($feeConcession) ? ($feeConcession->fee_concession_type->name ?? '') : ($feeConcession['fee_concession_type']['name'] ?? '');

    return [
        'sub_total' => $sub_total,
        'discountable_charges' => $discountableCharges,
        'non_refundable_charges' => $nonRefundableCharges,
        'sibling_discount_percentage' => $siblingDiscounts['AF'] ?? 0,
        'invoices_charges' => $invoiceCharges,
        'concession_type' => $concessionType,
        'concession_percentage' => $concessionPercentage,
        'concession_discount' => $concession_discount,
        'promo_discount' => $promoDiscount,
        'royalty_percentage' => $royaltyPercentage,
        'royalty_amount' => $royalty_amount + $arrearsData['royalty_amount'],
        'arrears' => $arrearsData['arrears'],
        'total' => $total + $arrearsData['arrears'],
        'total_after_royalty' => ($total + $arrearsData['arrears']) - ($royalty_amount + $arrearsData['royalty_amount']),
        'after_due_date' => ($isMonthly && $hasFine)
            ? ($total + $arrearsData['arrears']) * 1.025
            : ($total + $arrearsData['arrears']),
        'after_dd_royalty_amount' => ($isMonthly && $hasFine)
            ? ($royalty_amount + $arrearsData['royalty_amount']) * 1.025
            : ($royalty_amount + $arrearsData['royalty_amount']),
        'after_dd_total_after_royalty' => ($isMonthly && $hasFine)
            ? (($total + $arrearsData['arrears']) - ($royalty_amount + $arrearsData['royalty_amount'])) * 1.025
            : (($total + $arrearsData['arrears']) - ($royalty_amount + $arrearsData['royalty_amount'])),
        'diff_month' => $diffMonth
    ];
}

// function calculate_arrears($studentInvoice)
// {
//     $studentId = $studentInvoice['student']['id'] ?? null;
//     $currentInvoiceId = $studentInvoice['id'] ?? null;
//     $currentPeriod = Carbon::parse(optional($studentInvoice['fee_period'])['from_date']);
//     $invoiceCreatedAt = Carbon::parse($studentInvoice['created_at'] ?? now());

//     if (!$studentId || !$currentInvoiceId || !$currentPeriod) {
//         return ['arrears' => 0, 'royalty_amount' => 0, 'total_after_royalty' => 0, 'arrear_months' => []];
//     }

//     $arrears = $royaltyAmount = $totalAfterRoyalty = 0;
//     $arrearMonths = [];

//     $previousInvoices = StudentInvoice::where('student_id', $studentId)
//         ->where('id', '!=', $currentInvoiceId)
//         ->whereHas('fee_period', function ($query) use ($currentPeriod) {
//             $query->where('from_date', '<', $currentPeriod);
//         })
//         ->with(['fee_period'])
//         ->get();

//     foreach ($previousInvoices as $inv) {
//         $paymentDate = $inv->paid_date ? Carbon::parse($inv->paid_date) : null;
//         $status = $inv->bank_payment_status;

//         $shouldIncludeAsArrear =
//             $status === 'unpaid' ||
//             $status === 'adjusted' ||
//             ($status === 'paid' && $paymentDate && $paymentDate->greaterThan($invoiceCreatedAt));

//         if ($shouldIncludeAsArrear) {
//             $data = calculate_total_price_by_invoice($inv, 'for_arrears');

//             // add base values
//             $arrears += $data['total'] ?? 0;
//             $royaltyAmount += $data['royalty_amount'] ?? 0;
//             $totalAfterRoyalty += $data['total_after_royalty'] ?? 0;

//             // manually include fine if applicable
//             $fine = $data['due_date_fine'] ?? 0;
//             if ($fine > 0) {
//                 $arrears += $fine;
//                 $royaltyAmount += ($fine * ($data['royalty_percentage'] ?? 0)) / 100;
//                 $totalAfterRoyalty += $fine;
//             }

//             $month = optional($inv->fee_period)->from_date;
//             if ($month) {
//                 $arrearMonths[] = Carbon::parse($month)->format('F');
//             }
//         }
//     }

//     return [
//         'arrears' => $arrears,
//         'royalty_amount' => $royaltyAmount,
//         'total_after_royalty' => $totalAfterRoyalty,
//         'arrear_months' => array_unique($arrearMonths)
//     ];
// }

function calculate_arrears($studentInvoice)
{
    // Handle both object and array data structures
    $studentId = null;
    $currentInvoiceId = null;
    $currentPeriod = null;
    $invoiceCreatedAt = null;
    $royaltyPercentage = 0;

    if (is_object($studentInvoice)) {
        // New normalized structure
        $studentId = $studentInvoice->student->id ?? null;
        $currentInvoiceId = $studentInvoice->id ?? null;
        $currentPeriod = optional($studentInvoice->fee_period)->from_date;
        $invoiceCreatedAt = Carbon::parse($studentInvoice->created_at ?? now());
        $royaltyPercentage = get_branch_royalty($studentInvoice->student->branch_id ?? null);
        
        // If this is a model instance with arrears_carried_from relationship, use the new structure
        if (method_exists($studentInvoice, 'arrears_carried_from')) {
            $arrearsAmount = $studentInvoice->arrears_carried_from->sum('amount');
            $arrearMonths = $studentInvoice->arrears_carried_from->pluck('from_invoice_id')
                ->map(function($invoiceId) {
                    $invoice = StudentInvoice::find($invoiceId);
                    return $invoice && $invoice->fee_period ? 
                        Carbon::parse($invoice->fee_period->from_date)->format('F') : null;
                })
                ->filter()
                ->unique()
                ->values()
                ->toArray();
            
            return [
                'arrears' => $arrearsAmount,
                'royalty_amount' => $arrearsAmount * ($royaltyPercentage / 100),
                'total_after_royalty' => $arrearsAmount - ($arrearsAmount * ($royaltyPercentage / 100)),
                'arrear_months' => $arrearMonths
            ];
        }
    } else {
        // Old array structure
        $studentId = $studentInvoice['student']['id'] ?? null;
        $currentInvoiceId = $studentInvoice['id'] ?? null;
        $currentPeriod = optional($studentInvoice['fee_period'])->from_date;
        $invoiceCreatedAt = Carbon::parse($studentInvoice['created_at'] ?? now());
        $royaltyPercentage = get_branch_royalty($studentInvoice['student']['branch_id'] ?? null);
    }

    if (!$studentId || !$currentInvoiceId || !$currentPeriod) {
        return [
            'arrears' => 0,
            'royalty_amount' => 0,
            'total_after_royalty' => 0,
            'arrear_months' => [],
        ];
    }

    $currentPeriodDate = Carbon::parse($currentPeriod);
    $arrears = $royaltyAmount = $totalAfterRoyalty = 0;
    $arrearMonths = [];

    $previousInvoices = StudentInvoice::where('student_id', $studentId)
        ->where('id', '!=', $currentInvoiceId)
        ->whereHas('fee_period', function ($query) use ($currentPeriodDate) {
            $query->where('from_date', '<', $currentPeriodDate);
        })
        ->with('fee_period')
        ->get()
        ->sortBy(fn($inv) => optional($inv->fee_period)->from_date ?? now());

    foreach ($previousInvoices as $inv) {
        $invPeriod = optional($inv->fee_period)->from_date;

        // Extra safeguard: skip invoices with future or same months accidentally present
        if (!$invPeriod || Carbon::parse($invPeriod)->gte($currentPeriodDate)) {
            continue;
        }

        $paymentDate = $inv->paid_date ? Carbon::parse($inv->paid_date) : null;
        $status = $inv->bank_payment_status;

        $shouldIncludeAsArrear =
            $status === 'unpaid' ||
            $status === 'adjusted' ||
            ($status === 'paid' && $paymentDate && $paymentDate->gt($invoiceCreatedAt));

        if ($shouldIncludeAsArrear) {
            $data = calculate_total_price_by_invoice($inv, 'for_arrears');

            $arrears += $data['total'] ?? 0;
            $royaltyAmount += $data['royalty_amount'] ?? 0;
            $totalAfterRoyalty += $data['total_after_royalty'] ?? 0;

            $fine = $data['due_date_fine'] ?? 0;
            if ($fine > 0) {
                $arrears += $fine;
                $royaltyAmount += ($fine * ($data['royalty_percentage'] ?? 0)) / 100;
                $totalAfterRoyalty += $fine;
            }

            $month = optional($inv->fee_period)->from_date;
            if ($month) {
                $arrearMonths[] = Carbon::parse($month)->format('F');
            }
        }
    }

    return [
        'arrears' => $arrears,
        'royalty_amount' => $royaltyAmount,
        'total_after_royalty' => $totalAfterRoyalty,
        'arrear_months' => array_values(array_unique($arrearMonths)),
    ];
}

function get_ledger_unpaid_invoices($student_ledger, $current_invoice_sort = null)
{
    $unpaidInvoicesQuery = $student_ledger->ledger_invoices();

    $relations = [
        'invoice.student',
        'invoice.student.active_class',
        'invoice.student_fee_package.fee_package',
        'invoice.student_fee_package.fee_concession.fee_concession_type',
        'invoice.student_fee_package.academic_year',
        'invoice.student_fee_package.com_class',
        'invoice.student_fee_package.section',
        'invoice.student_invoice_items.fee_charges.fee_charges_type',
        'invoice.invoice_type',
        'invoice.payment_source',
        'invoice.promo.promo_type',
        'invoice.fee_period'
    ];

    $unpaidInvoicesQuery = $unpaidInvoicesQuery->whereNotNull('student_invoice_id')
        ->whereHas('invoice', function ($query) {
            $query->whereIn('bank_payment_status', ['unpaid', 'adjusted']);
        });

    if (!is_null($current_invoice_sort)) {
        $unpaidInvoicesQuery = $unpaidInvoicesQuery->where('sort', '<', $current_invoice_sort);
    }

    return $unpaidInvoicesQuery->with($relations)->get();
}

function get_arrears_royalty_total($unpaid_invoices, $studentInvoice, $max_paid_id)
{
    $arrears = 0;
    $royalty_amount = 0;
    $total_after_royalty = 0;

    foreach ($unpaid_invoices as $unpaid_invoice) {
        $inv_data = $unpaid_invoice->toArray()['invoice'];
        if ($inv_data['bank_payment_status'] == 'unpaid') {

            if (get_month_name($inv_data['fee_period']['from_date']) == 'February') {
                $calculation = calculate_total_price_by_invoice_index($inv_data, 'for_arrears');
            } else {
                $calculation = calculate_total_price_by_invoice($inv_data, 'for_arrears');
            }
            if ($studentInvoice['arrears_fine']) {
                $arrears += ($calculation['total'] * 0.05) + $calculation['total'];
                $royalty_amount += ($calculation['royalty_amount'] * 0.05) + $calculation['royalty_amount'];
                $total_after_royalty += ($calculation['total_after_royalty'] * 0.05) + $calculation['total_after_royalty'];
            } else {
                $arrears += $calculation['total'];
                $royalty_amount += $calculation['royalty_amount'];
                $total_after_royalty += $calculation['total_after_royalty'];
            }
        } else if ($inv_data['bank_payment_status'] == 'adjusted' && $inv_data['id'] > $max_paid_id) {
            if (get_month_name($inv_data['fee_period']['from_date']) == 'February') {
                $calculation = calculate_total_price_by_invoice_index($inv_data, 'for_arrears');
            } else {
                $calculation = calculate_total_price_by_invoice($inv_data, 'for_arrears');
            }
            if ($studentInvoice['arrears_fine']) {
                $arrears += ($calculation['total'] * 0.05) + $calculation['total'];
                $royalty_amount += ($calculation['royalty_amount'] * 0.05) + $calculation['royalty_amount'];
                $total_after_royalty += ($calculation['total_after_royalty'] * 0.05) + $calculation['total_after_royalty'];
            } else {
                $arrears += $calculation['total'];
                $royalty_amount += $calculation['royalty_amount'];
                $total_after_royalty += $calculation['total_after_royalty'];
            }
        }
    }

    return ['arrears' => $arrears, 'royalty_amount' => $royalty_amount, 'total_after_royalty' => $total_after_royalty];
}



function all_the_charges_of_student($student, $fee_charge)
{
}

function isHeadOfficeEmp()
{
    return get_branch_code() == 5013 ? 1 : 0;
}

function isSuperAdmin()
{
    return auth()->user()->hasRole('super_admin');
}

function inUse($model, $id)
{
    if ($model == 'HomeWorkDiary') {
        if (HomeWorkDiaryDetial::where('diary_id', $id)->first()) {
            return 1;
        }

        return 0;
    }
    if ($model == 'HomeWorkDiaryDetial') {
        if (HomeWorkDiaryAttachment::where('diary_detail_id', $id)->first()) {
            return 1;
        }

        return 0;
    }
}

function getHomeWorkDiaryAttachments($diary_detail_id, $type)
{
    $Files = [];
    $attachments = null;
    $i = 0;
    $attachments = HomeWorkDiaryAttachment::where('diary_detail_id', $diary_detail_id)->get(['id', 'attachment']);
    if (isset($attachments[0])) {
        foreach ($attachments as $attachment) {
            if (Storage::disk('s3')->exists('images/' . $attachment->attachment)) {
                $Files[$i]['id'] = $attachment->id;
                $Files[$i]['file_name'] = $attachment->attachment;
                $i++;
            }
        }
        if ($type == 'VIEW') {
            return view('homeworkdiary.attachments', compact('Files'));
        } else {
            return view('homeworkdiary.edit_attachments', compact('Files'));
        }

    }
    return '';

}

function getBranch($id)
{
    $user = auth()->user();

    if ($user->hasRole('network_associate')) {
        $branch_id = get_set_NWABranchId();
        return $branch_id;
    } else {
        $branch = Employee::where('user_id', $id)->first('branch_id');
        return $branch->branch_id;
    }
}

function getUserByDepartmentAndBranchID($department_id, $branch_id)
{
    return Employee::where('department_id', '=', $department_id)->where('branch_id', '=', $branch_id)->with('user')->get()->toArray();

}

function getEmployeeAttendance($employee_id, $dated, $acd_year_id = null)
{
    $academic_year = AcademicYear::where('active', 1)->first();
    $academic_year_id = $academic_year->id;
    if (is_numeric($acd_year_id)) {
        $academic_year_id = $acd_year_id;
    }
    $data = array();
    $date = Carbon::parse($dated)->format('Y-m-d');


    $att_type = $leave_applied = $leave_status = $color = $leave_chk = NULL;
    $attendance = EmployeeAttendance::where('employee_id', $employee_id)->where('academic_year_id', $academic_year_id)->whereDate('created_at', $date)->get();
    if (isset($attendance[0])) {
        $data[0]['id'] = $attendance[0]['id'];
        $data[0]['attendance_type'] = $attendance[0]['attendance_type'];
        $data[0]['academic_year_id'] = $attendance[0]['academic_year_id'];
        $data[0]['status'] = $attendance[0]['status'];
        $data[0]['created_at'] = $attendance[0]['created_at'];
        $data[0]['time_in'] = $attendance[0]['time_in'];
        $data[0]['time_in_color'] = 'bg-success';
        $data[0]['time_in_text_color'] = 'text-success';
        $data[0]['time_out'] = $attendance[0]['time_out'];
        $data[0]['time_out_color'] = 'bg-success';
        $data[0]['time_out_text_color'] = 'text-success';
        $data[0]['leave_color'] = '';
        $data[0]['leave_status'] = '';
        $data[0]['leave_name'] = '';
        $data[0]['attendance_day'] = '';
        $data[0]['attendance_day_color'] = '';
        $checkInDayName = date('l', strtotime($attendance[0]['created_at']));
        if ($attendance[0]['attendance_type'] == 0) {

            $employee = Employee::select('employees.id', 'eld.working_day_id', 'eld.official_leave_id', 'wd.abbreviation', 'wd.name', 'ld.start_time', 'ld.end_time', 'ld.status')
                ->join('employee_official_leave_days as eld', 'employees.id', '=', 'eld.employee_id')
                ->join('working_days as wd', 'eld.working_day_id', '=', 'wd.id')
                ->join('official_leave_days as ld', 'eld.official_leave_id', '=', 'ld.id')
                ->where('employees.id', $employee_id)
                ->where('wd.name', $checkInDayName)
                ->first();
            $att_type = 0;
        }
        if ($attendance[0]['attendance_type'] == 1) {
            $employee = Employee::select('employees.id', 'ewd.working_day_id', 'ewd.working_shift_id', 'wd.abbreviation', 'wd.name', 'ws.start_time', 'ws.end_time', 'ws.status')
                ->join('employee_working_days as ewd', 'employees.id', '=', 'ewd.employee_id')
                ->join('working_days as wd', 'ewd.working_day_id', '=', 'wd.id')
                ->join('working_shifts as ws', 'ewd.working_shift_id', '=', 'ws.id')
                ->where('employees.id', $employee_id)
                ->where('wd.name', $checkInDayName)
                ->first();
            $att_type = 1;
        }
        if ($att_type == 1) {
            if (isset($employee->status) == 1) {
                /*find checkin time difference*/
                $checkInTimeDiff = calculateTimeDifference($employee->start_time, $attendance[0]['time_in']);


                if ($checkInTimeDiff > 10) {
                    $data[0]['time_in_color'] = 'bg-danger';
                    $data[0]['time_in_text_color'] = 'text-danger';
                    $leave_applied = LeaveApplication::where('employee_id', '=', $employee_id)->where('application_type_id', 4)->where('from_date', '=', $attendance[0]['created_at']->toDateString())->with('leaveApplicationType')->first();
                    if ($leave_applied) {
                        if ($leave_applied->status == '0') {
                            $data[0]['leave_status'] = 'Pending';
                            $data[0]['leave_color'] = 'outline-warning';
                        } else if ($leave_applied->status == '1') {
                            $data[0]['leave_status'] = 'Approved';
                            $data[0]['leave_color'] = 'outline-success';
                        }
                        $data[0]['leave_name'] = $leave_applied->leaveApplicationType->name;
                    }
                }


                if (!is_null($attendance[0]['time_out'])) {
                    /*find checkout time difference*/
                    $checkOutTimeDiff = calculateTimeDifference($employee->end_time, $attendance[0]['time_out']);
                    if ($checkOutTimeDiff < -10) {
                        $data[0]['time_out_color'] = 'bg-danger';
                        $data[0]['time_out_text_color'] = 'text-danger';
                        $leave_applied = LeaveApplication::where('employee_id', '=', $employee_id)->where('application_type_id', 5)->where('from_date', '=', $attendance[0]['created_at']->toDateString())->with('leaveApplicationType')->first();
                        if ($leave_applied) {
                            if ($leave_applied->status == '0') {
                                $data[0]['leave_status'] = 'Pending';
                                $data[0]['leave_color'] = 'outline-warning';
                            } else if ($leave_applied->status == '1') {
                                $data[0]['leave_status'] = 'Approved';
                                $data[0]['leave_color'] = 'outline-success';
                            }
                            $data[0]['leave_name'] = $leave_applied->leaveApplicationType->name;
                        }
                    }

                }
            } else if (isset($employee->status) == 0) {
                $data[0]['attendance_day'] = 'Off Day';
                $data[0]['attendance_day_color'] = 'outline-info';
            }

        } else {
            if ($employee->status == 1) {
                $data[0]['time_in'] = '';
                $data[0]['time_in_color'] = 'bg-success';
                $data[0]['time_out'] = '';
                $data[0]['time_out_color'] = 'bg-success';
                $data[0]['attendance_day'] = 'Eid al-Fitar';
                $data[0]['attendance_day_color'] = 'outline-info';
            } else if ($employee->status == 2) {
                $data[0]['time_in'] = '';
                $data[0]['time_in_color'] = 'bg-success';
                $data[0]['time_out'] = '';
                $data[0]['time_out_color'] = 'bg-success';
                $data[0]['attendance_day'] = 'Eid al-Adha';
                $data[0]['attendance_day_color'] = 'outline-info';
            } else if ($employee->status == 3) {
                $data[0]['time_in'] = '';
                $data[0]['time_in_color'] = 'bg-success';
                $data[0]['time_out'] = '';
                $data[0]['time_out_color'] = 'bg-success';
                $data[0]['attendance_day'] = 'Pakistan Day';
                $data[0]['attendance_day_color'] = 'outline-info';
            } else if ($employee->status == 4) {
                $data[0]['time_in'] = '';
                $data[0]['time_in_color'] = 'bg-success';
                $data[0]['time_out'] = '';
                $data[0]['time_out_color'] = 'bg-success';
                $data[0]['attendance_day'] = 'Independence Day';
                $data[0]['attendance_day_color'] = 'outline-info';
            } else if ($employee->status == 5) {
                $data[0]['time_in'] = '';
                $data[0]['time_in_color'] = 'bg-success';
                $data[0]['time_out'] = '';
                $data[0]['time_out_color'] = 'bg-success';
                $data[0]['attendance_day'] = 'Quaid-e-Azam Day';
                $data[0]['attendance_day_color'] = 'outline-info';
            } else if ($employee->status == 6) {
                $data[0]['time_in'] = '';
                $data[0]['time_in_color'] = 'bg-success';
                $data[0]['time_out'] = '';
                $data[0]['time_out_color'] = 'bg-success';
                $data[0]['attendance_day'] = 'Labour Day';
                $data[0]['attendance_day_color'] = 'outline-info';
            } else if ($employee->status == 7) {
                $data[0]['time_in'] = '';
                $data[0]['time_in_color'] = 'bg-success';
                $data[0]['time_out'] = '';
                $data[0]['time_out_color'] = 'bg-success';
                $data[0]['attendance_day'] = 'Muharram';
                $data[0]['attendance_day_color'] = 'outline-info';
            } else {
                //nothing todo.
            } /*  end schedule shifts status check. */


        }

        // check user applied leaves.
        // if($date == '2023-09-26'){
        //     dd($date);
        // }


    } else {
        $data[0]['attendance_type'] = '';
        $data[0]['academic_year_id'] = '';
        $data[0]['status'] = '';
        $data[0]['created_at'] = '';
        $data[0]['time_in'] = '';
        $data[0]['time_in_color'] = 'bg-danger';
        $data[0]['time_in_text_color'] = 'text-danger';
        $data[0]['time_out'] = '';
        $data[0]['time_out_color'] = 'bg-danger';
        $data[0]['time_out_text_color'] = 'text-danger';
        $data[0]['leave_color'] = '';
        $data[0]['leave_status'] = '';
        $data[0]['leave_name'] = '';
        $leave_applied = LeaveApplication::where('employee_id', '=', $employee_id)->whereNotIn('application_type_id', [4, 5])->whereDate('application_date', $date)->with(['leaveApplicationType', 'leaveType'])->get();
        if ($leave_applied) {
            foreach ($leave_applied as $leaves) {
                if ($leaves['status'] == '0') {
                    $data[0]['leave_color'] = 'outline-warning';
                } else {
                    $data[0]['leave_color'] = 'outline-success';
                }
                if (isset($leaves->leaveApplicationType->name) && $leaves->leaveApplicationType->name == 'Attendance not Marked') {
                    $start = $leaves['attendance_not_marked_date']->toDateString();
                    $end = '';
                }
                if (isset($leaves->leaveApplicationType->name) && ($leaves->leaveApplicationType->name == 'Leave' || $leaves->leaveApplicationType->name == 'Out Station')) //$leaves->leaveApplicationType->name == 'Late Arrival' || $leaves->leaveApplicationType->name == 'Early Leaving'
                {
                    $start = $leaves['from_date']->toDateString();
                    if (isset($leaves['end_date'])) {
                        $end = $leaves['end_date']->toDateString();
                    } else {
                        $end = '';
                    }
                }
                if (isset($leaves->leaveType->name)) {
                    $data[0]['leave_name'] = $leaves->leaveType->name;
                } else if (isset($leaves->leaveApplicationType->name)) {
                    $data[0]['leave_name'] = $leaves->leaveApplicationType->name;
                }
            }//end foreach
        }//end if
    }
    if (isset($data)) {
        return $data;
    }

    return '';
}

function checkHomeWorkDiarySubject($subject_id, $diary_id)
{
    $subject = null;
    $subject = HomeWorkDiaryDetial::where('subject_id', $subject_id)->where('diary_id', $diary_id)->first();
    if (isset($subject)) {
        return 0;
    }
    return 1;
}

function getMonthListFromDate(Carbon $start, Carbon $end)
{
    $interval = DateInterval::createFromDateString('1 month'); // 1 month interval
    $period = new DatePeriod($start, $interval, $end); // Get a set of date beetween the 2 period

    $months = array();

    foreach ($period as $dt) {
        $months[] = $dt->format("F Y");
    }

    return $months;
}

function get_class_level($class_id)
{

    $class_obj = ComClass::find($class_id);
    $class_name = strtolower($class_obj['class_name']);

    if (str_contains($class_name, 'kg') || str_contains($class_name, 'nursery')) //not applied seperate check for pre-nursery and nursery because both contains nursery
        return 'EY';
    else if (str_contains($class_name, '1') || str_contains($class_name, 'one') || str_contains($class_name, '2') || str_contains($class_name, 'two'))
        return 'LP';
    else if (
        str_contains($class_name, '3') || str_contains($class_name, 'three')
        || str_contains($class_name, '4') || str_contains($class_name, 'four')
        || str_contains($class_name, '5') || str_contains($class_name, 'five')
        || str_contains($class_name, '6') || str_contains($class_name, 'six')
        || str_contains($class_name, '7') || str_contains($class_name, 'seven')
    )
        return 'UP';
}

function calculate_age($date)
{
    //$date = Y-m-d
    return Carbon::parse($date)->age;
}

function calculate_class_average_age($branch_id, $class_id, $section_id): float
{
    $branch_class_section_id = BranchClassSection::where('branch_id', $branch_id)->where('class_id', $class_id)->where('section_id', $section_id)->first()->id;
    $class_students_dobs = ClassStudent::where('branch_class_section_id', $branch_class_section_id)->with([
        'students' => function ($q) {
            $q->select('id', 'date_of_birth');
        }
    ])->get()->pluck('students')->pluck('date_of_birth');

    $sum_age = null;
    foreach ($class_students_dobs as $date) {
        $sum_age += calculate_age($date);
    }
    return round($sum_age / count($class_students_dobs));
}

function number_of_working_days($from, $to)
{
    $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
    $holidayDays = ['*-02-05', '*-03-23', '*,05,01', '*,07,14', '*,08,24', '*,09,09', '*,12,25']; # variable and fixed national holidays

    $from = new DateTime($from);
    $to = new DateTime($to);
    $to->modify('+1 day');
    $interval = new DateInterval('P1D');
    $periods = new DatePeriod($from, $interval, $to);

    $days = 0;
    foreach ($periods as $period) {
        if (!in_array($period->format('N'), $workingDays))
            continue;
        if (in_array($period->format('Y-m-d'), $holidayDays))
            continue;
        if (in_array($period->format('*-m-d'), $holidayDays))
            continue;
        $days++;
    }
    return $days;
}

function getBranchWorkingDays($branch_id, $term_id, $academic_year_id)
{
    $working_days = AcademicYearWorkingDays::where([['branch_id', $branch_id], ['term_id', $term_id], ['academic_year_id', $academic_year_id]])->first();
    // dump($branch_id, $term_id, $academic_year_id);
    // if ($working_days) {
    return $working_days->working_days;
    // }
}

function getPresentStudentAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date)
{
    return StudentAttendance::where([
        'academic_year_id' => $academic_year_id,
        'branch_class_section_id' => $branch_class_section_id,
        'student_id' => $student_id,
    ])
        ->whereHas('attendance_status', function ($q) {
            $q->where('name', 'Present');
        })
        ->whereBetween('attendance_date', [$term_start_date, $term_end_date])
        ->count();
}

function getStudentExemptedAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date)
{
    return StudentAttendance::where([
        'academic_year_id' => $academic_year_id,
        'branch_class_section_id' => $branch_class_section_id,
        'student_id' => $student_id,
    ])
        ->whereHas('attendance_status', function ($q) {
            $q->where('name', 'Exempted');
        })
        ->whereBetween('attendance_date', [$term_start_date, $term_end_date])
        ->count();
}

function getStudentTardyAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date)
{
    $tardy_attendances = StudentAttendance::where([
        'academic_year_id' => $academic_year_id,
        'branch_class_section_id' => $branch_class_section_id,
        'student_id' => $student_id,
    ])
        ->whereHas('attendance_status', function ($q) {
            $q->where('name', 'Tardy');
        })
        ->whereBetween('attendance_date', [$term_start_date, $term_end_date])
        ->count();
    return $tardy_attendances;
}
function getStudentAbsentAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date)
{
    return StudentAttendance::where([
        'academic_year_id' => $academic_year_id,
        'branch_class_section_id' => $branch_class_section_id,
        'student_id' => $student_id,
    ])
        ->whereHas('attendance_status', function ($q) {
            $q->where('name', 'Absent');
        })
        ->whereBetween('attendance_date', [$term_start_date, $term_end_date])
        ->count();
}

function get_student_grade($class_id, $marks)
{

    $grading_key = GradingCriteria::whereHas('classes', function ($q) use ($class_id) {
        $q->where('com_classes.id', $class_id);
    })->where(function ($query) use ($marks) {
        $query->where('starting_percentage', '<=', $marks);
        $query->where('ending_percentage', '>=', $marks);
    })->first();

    return !empty($grading_key) ? $grading_key->grading_key : 'N/A';
}

function sendOTPCode($message, $mobile = NULL)
{
    Log::info('Log created at ' . Carbon::now() . '. Here is the message ' . $message);

    $type = "xml";
    $id = "cd1094beacon";
    $pass = "system231";
    $lang = "English";
    $mask = "1";

    if ($mobile == NULL) {
        /*$mobile = $this->phone;*/
        $mobile = '03001111111';
    }

    $mobile = preg_replace('/\D+/', '', $mobile);
    // dd($mobile);
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

function log_activity(string $event, ?\Illuminate\Database\Eloquent\Model $model = null, ?array $old = null, ?array $new = null): \App\Models\ActivityLog
{
    return app(\App\Services\ActivityLoggerService::class)->log($event, $model, $old, $new);
}