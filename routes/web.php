<?php

use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\EmploymentLetterRequestController;
use App\Http\Controllers\ExtraCurriculumController;
use App\Http\Controllers\GoodsReceivedNoteController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\TransferRequestController;
use App\Http\Controllers\PTMController;
use App\Http\Controllers\Settings\DeductionTypeController;
use App\Http\Controllers\Settings\IncomeTaxSlabController;
use App\Http\Controllers\Settings\ProvidentFundDefinitionController;
use App\Http\Controllers\SupplierController;
use App\Models\EmployeeDependent;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FeeTierController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaxTypeController;
use App\Http\Controllers\BooklistController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComClassController;
use App\Http\Controllers\CrmBoardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LanguageController;
use \App\Http\Controllers\GuardianController;
use App\Http\Controllers\FeeChargeController;
use App\Http\Controllers\FeePeriodController;
use App\Http\Controllers\GradeBookController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PaperTypeController;
use App\Http\Controllers\StaffTypeController;
use App\Http\Controllers\ClassGroupController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FeePackageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UcsReportsController;
use App\Http\Controllers\WorkingDayController;
use \App\Http\Controllers\LessonPlanController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BeamsChalanController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ParentQueryController;
use App\Http\Controllers\VisitDetailController;
use \App\Http\Controllers\TeacherTypeController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\BeamsChallanController;
use App\Http\Controllers\BuildingTypeController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\FollowUpTypeController;
use App\Http\Controllers\SchemeOfWorkController;
use App\Http\Controllers\SubjectGroupController;
use App\Http\Controllers\SystemModuleController;
use App\Http\Controllers\WorkingShiftController;
use \App\Http\Controllers\ClassStudentController;
use \App\Http\Controllers\ClassTeacherController;
use \App\Http\Controllers\ConstituencyController;
use \App\Http\Controllers\SupportQueryController;
use App\Http\Controllers\FeeConcessionController;
use App\Http\Controllers\HomeWorkDiaryController;
use App\Http\Controllers\InquiriesTypeController;
use App\Http\Controllers\StudentLedgerController;
use App\Http\Controllers\SubjectRemarkController;
use \App\Http\Controllers\AcademicClassController;
use \App\Http\Controllers\BranchRoyaltyController;
use App\Http\Controllers\BranchSecurityController;
use App\Http\Controllers\FeeChargesTypeController;
use App\Http\Controllers\StudentAddressController;
use App\Http\Controllers\StudentInvoiceController;
use \App\Http\Controllers\AdmissionQueryController;
use App\Http\Controllers\ApplicationTypeController;
use App\Http\Controllers\AssessmentEntryController;
use App\Http\Controllers\AssessmentLevelController;
use App\Http\Controllers\GradingCriteriaController;
use \App\Http\Controllers\GeneralDocumentController;
use App\Http\Controllers\CampusOfficeTypeController;
use App\Http\Controllers\FranchiseInquiryController;
use App\Http\Controllers\GeneralBehaviourController;
use App\Http\Controllers\GradeBookHistoryController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\NetworkAssociateController;
use App\Http\Controllers\OfficialLeaveDayController;
use App\Http\Controllers\PromotionRequestController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\WithdrawalReasonController;
use \App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AdmissionFollowUpController;
use App\Http\Controllers\EmployeeDependentController;
use App\Http\Controllers\FamilyInformationController;
use App\Http\Controllers\FeeConcessionTypeController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentConcessionController;
use App\Http\Controllers\StudentFeePackageController;
use App\Http\Controllers\StudentWithdrawalController;
use App\Http\Controllers\SubjectMarksSetupController;
use \App\Http\Controllers\BrandingMarketingController;
use App\Http\Controllers\BranchAcademicYearController;
use App\Http\Controllers\BranchWorkingShiftController;
use App\Http\Controllers\ContactInformationController;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\EmployeeLeaveQuotaController;
use App\Http\Controllers\EmployeeWorkingDayController;
use App\Http\Controllers\GuardianInfoUpdateController;
use App\Http\Controllers\SiblingInformationController;
use App\Http\Controllers\SystemNotificationController;
use \App\Http\Controllers\BranchClassSectionController;
use App\Http\Controllers\AccountantDashboardController;
use App\Http\Controllers\ClassStudentSubjectController;
use App\Http\Controllers\FranchiseInquiryOldController;
use App\Http\Controllers\HomeWorkDiaryDetialController;
use App\Http\Controllers\StudentTransferCaseController;
use App\Http\Controllers\FranchiseApplicationController;
use App\Http\Controllers\DesignationLeaveQuotaController;
use App\Http\Controllers\FeePackagesFeeChargesController;
use App\Http\Controllers\StudentBehaviourSkillController;
use App\Http\Controllers\StudentPreviousSchoolController;
use App\Http\Controllers\StudentTransferReasonController;
use App\Http\Controllers\FranchiseApplicationQaController;
use App\Http\Controllers\NetworkAssociateBranchController;
use App\Http\Controllers\AcademicYearWorkingDaysController;
use App\Http\Controllers\DeputyDirectorDashboardController;
use App\Http\Controllers\EmployeeOfficialLeaveDayController;
use App\Http\Controllers\FrachiseApplicationRemarkController;
use App\Http\Controllers\FranchiseApplicationBdVisitController;
use App\Http\Controllers\WithdrawalCancellationReasonController;
use App\Http\Controllers\FranchiseApplicationDdResponseController;
use App\Http\Controllers\FranchiseApplicationLaResponseController;
use \App\Http\Controllers\FranchiseApplicationTorsResponseController;
use \App\Http\Controllers\TeacherObservationController;
use \App\Http\Controllers\QuestionDimensionController;
use \App\Http\Controllers\AnswerDimensionController;
use App\Http\Controllers\TimetableController;

use \App\Http\Controllers\ObservationDetailController;
use App\Http\Controllers\LessonPlanGenerateController;
use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\File;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/fee_structure/create', [SchoolController::class, 'create'])->name('fee_structure.create');
// Route::post('fee_structure/create', [SchoolController::class, 'store'])->name('fee_structure.store');
Route::get('/fee-structures', [SchoolController::class, 'index'])->name('fee_structure.index');
Route::get('/fee-structures/create', [SchoolController::class, 'create'])->name('fee_structure.create');
Route::post('/fee-structures', [SchoolController::class, 'store'])->name('fee_structure.store');
Route::get('/fee-structures/{id}/edit', [SchoolController::class, 'edit'])->name('fee_structure.edit');


Route::get('/fee-structures/{id}/index-edit', [SchoolController::class, 'index_edit'])->name('fee_structure.index_edit');

Route::get('/fee-structures/{id}/complete-edit', [SchoolController::class, 'complete_edit'])->name('fee_structure.complete_edit');
Route::put('/fee-structures/{id}', [SchoolController::class, 'complete_update'])->name('fee_structure.complete_update');

Route::put('/fee-structures/{id}', [SchoolController::class, 'update'])->name('fee_structure.update');
Route::put('/fee-structure/{id}', [SchoolController::class, 'update_fee'])->name('fee_structure.update_fee');
Route::delete('/fee-structures/{id}', [SchoolController::class, 'destroy'])->name('fee_structure.destroy');
Route::delete('/new-school-fee-structure/{id}', [SchoolController::class, 'destroyParent'])->name('new-school-fee-structure.destroy');

Route::post('/fee-structures/change-status', [SchoolController::class, 'changeStatus'])->name('fee_structure.change.status');
// Route::post('/fee-structures/view-detail', [SchoolController::class, 'viewDetail'])->name('fee_structure.view.detail');
// Route::get('/filter-academic-year', [SchoolController::class, 'filterAcademicYear']);
// Route::get('list-cities', 'SchoolController@listCities')->name('list-cities');
// Route::get('/list-cities', [SchoolController::class, 'listCities'])->name('list-cities');
// Route::get('filter-fee-structure', 'SchoolController@filterFeeStructure')->name('filter-fee-structure');
// Route::get('filter-fee-structure', 'SchoolController@filterFeeStructure')->name('filter-fee-structure');
Route::get('/filter-fee-structure', [SchoolController::class, 'filterFeeStructure'])->name('filter-fee-structure');







Route::get('franchises-inquiry/campaign', [FranchiseInquiryController::class, 'create'])->name('franchise-inquiry.campaign');

Route::resources(['ipg-billing' => BillingController::class]);
Route::get('/ipg-online-billing', [BillingController::class, 'online'])->name('ipg-online-billing');
Route::get('/ipg-billing-billing', [BillingController::class, 'billing'])->name('ipg-billing-billing');
Route::post('/get-gaurdian-details', [BillingController::class, 'getGaurdianDetails'])->name('get-gaurdian-details');
Route::post('/send-billing-otp', [BillingController::class, 'SendOTP'])->name('send-billing-otp');
Route::get('/verify-billing-otp', [BillingController::class, 'VerifyOTP'])->name('verify-billing');
Route::post('/add-billing-details', [BillingController::class, 'AddPaidDetails'])->name('add-billing-details');
Route::get('/payment-success', [BillingController::class, 'PaymentSuccess'])->name('payment-success');

Route::get('/student-info', [StudentController::class, 'certificateVerification'])->name('student-info');

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole('teacher')) {
            return redirect()->route('teacher-dashboard');
        } else if (Auth::user()->hasRole('deputy-director')) {
            return redirect()->route('dd-dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    } else {
        return view('auth.login');
    }
});

Route::patch('/franchises/{franchise}', [FranchiseInquiryOldController::class, 'update'])->name('franchises.update');
Route::get('/franchises/edit/{guid}', [FranchiseInquiryOldController::class, 'guestEditFranchiseApplication'])->name('guest-franchises.edit');
//Franchise Application
Route::patch('/franchise-applications/{franchise_application}', [FranchiseApplicationController::class, 'update'])->name('franchise-applications.update');
Route::get('/franchise-applications/edit/{guid}', [FranchiseApplicationController::class, 'guestEditFranchiseApplication'])->name('guest-franchise-applications.edit');
Route::get('/franchise-applications/franchise_application_iasf_form/{id}', [FranchiseApplicationController::class, 'franchiseApplicationInquiryAssessmentSurvey'])->name('franchise-application-iasf-form');
Route::get('franchise-applications/create_iasf_pdf/{id}', [FranchiseApplicationController::class, 'create_iasf_pdf'])->name('franchise-application-iasf.create_iasf_pdf');
// Route::get('franchise-application/bd-visit', [FranchiseApplicationController::class, 'bdVisitFormView'])->name('franchise-applications.bdVisitFormView');

Route::get('/load-qualification-data', [FranchiseInquiryOldController::class, 'loadInquiryQualification'])->name('load-qualification-data');
Route::post('/save-inquiry-qualification', [FranchiseInquiryOldController::class, 'saveInquiryQualification'])->name('save-inquiry-qualification');
Route::get('/get-inquiry-qualification', [FranchiseInquiryOldController::class, 'getInquiryQualification'])->name('get-inquiry-qualification');
Route::delete('/remove-inquiry-qualification/{franchise_qualification}', [FranchiseInquiryOldController::class, 'removeInquiryQualification'])->name('remove-inquiry-qualification');
//Franchise Application
Route::get('/load-application-qualifications', [FranchiseApplicationController::class, 'loadApplicationQualifications'])->name('load-application-qualifications');
Route::post('/save-application-qualification', [FranchiseApplicationController::class, 'saveApplicationQualification'])->name('save-application-qualification');
Route::get('/get-application-qualification', [FranchiseApplicationController::class, 'getApplicationQualification'])->name('get-application-qualification');
Route::delete('/remove-application-qualification/{application_qualification}', [FranchiseApplicationController::class, 'removeApplicationQualification'])->name('remove-application-qualification');

Route::get('/load-inquiry-service-data', [FranchiseInquiryOldController::class, 'loadInquiryServiceData'])->name('load-inquiry-service-data');
Route::post('/save-inquiry-service', [FranchiseInquiryOldController::class, 'saveInquiryService'])->name('save-inquiry-service');
Route::get('/get-inquiry-service', [FranchiseInquiryOldController::class, 'getInquiryService'])->name('get-inquiry-service');
Route::delete('/remove-inquiry-service/{franchise_service}', [FranchiseInquiryOldController::class, 'removeInquiryService'])->name('remove-inquiry-service');
//Franchise Application
Route::get('/load-application-services', [FranchiseApplicationController::class, 'loadApplicationServices'])->name('load-application-services');
Route::post('/save-application-service', [FranchiseApplicationController::class, 'saveApplicationService'])->name('save-application-service');
Route::get('/get-application-service', [FranchiseApplicationController::class, 'getApplicationService'])->name('get-application-service');
Route::delete('/remove-application-service/{application_service}', [FranchiseApplicationController::class, 'removeApplicationService'])->name('remove-application-service');

Route::get('/load-other-information-data', [FranchiseInquiryOldController::class, 'loadInquiryOtherInformation'])->name('load-other-information-data');
Route::post('/save-inquiry-other-information', [FranchiseInquiryOldController::class, 'saveInquiryOtherInformation'])->name('save-inquiry-other-information');
Route::get('/get-inquiry-other-information', [FranchiseInquiryOldController::class, 'getInquiryOtherInformation'])->name('get-inquiry-other-information');
Route::delete('/remove-inquiry-other-information/{franchise_other_information}', [FranchiseInquiryOldController::class, 'removeInquiryOtherInformation'])->name('remove-inquiry-other-information');
//Franchise Application
Route::get('/load-application-other-informations', [FranchiseApplicationController::class, 'loadApplicationOtherInformation'])->name('load-application-other-informations');
Route::post('/save-application-other-information', [FranchiseApplicationController::class, 'saveApplicationOtherInformation'])->name('save-application-other-information');
Route::get('/get-application-other-information', [FranchiseApplicationController::class, 'getApplicationOtherInformation'])->name('get-application-other-information');
Route::delete('/remove-application-other-information/{application_other_information}', [FranchiseApplicationController::class, 'removeApplicationOtherInformation'])->name('remove-application-other-information');

Route::get('/load-posses-site-data', [FranchiseInquiryOldController::class, 'loadPossesSiteData'])->name('load-posses-site-data');
Route::post('/save-possess-site-date', [FranchiseInquiryOldController::class, 'savePossesSiteData'])->name('save-possess-site-date');
Route::get('/get-possess-site-data', [FranchiseInquiryOldController::class, 'getPossesSiteData'])->name('get-possess-site-data');
Route::delete('/remove-possess-site-data/{franchise_possess_site}', [FranchiseInquiryOldController::class, 'removePossesSiteData'])->name('remove-possess-site-data');
//Franchise Application
Route::get('/load-application-posses-sites', [FranchiseApplicationController::class, 'loadApplicationPossesSites'])->name('load-application-posses-sites');
Route::post('/save-application-possess-site', [FranchiseApplicationController::class, 'saveApplicationPossesSite'])->name('save-application-possess-site');
Route::get('/get-application-possess-site', [FranchiseApplicationController::class, 'getApplicationPossesSite'])->name('get-application-possess-site');
Route::delete('/remove-application-possess-site/{application_possess_site}', [FranchiseApplicationController::class, 'removeApplicationPossesSite'])->name('remove-application-possess-site');

Route::get('/list-cities', [CommonController::class, 'listCities'])->name('list-cities');

//Franchise Application
Route::get('/get-franchise-application-docs', [FranchiseApplicationController::class, 'getFranchiseApplicationDocs'])->name('get-franchise-application-docs');
Route::get('/franchise-application-docs', [DeputyDirectorDashboardController::class, 'franchiseApplicationDocs'])->name('franchise-application-docs');
Route::post('/save-franchise-application-docs', [FranchiseApplicationController::class, 'saveFranchiseApplicationDocs'])->name('save-franchise-application-docs');
Route::delete('/remove-franchise-application-docs/{franchise_application_doc}', [FranchiseApplicationController::class, 'removeFranchiseApplicationDocs'])->name('remove-franchise-application-docs');


Route::group(['middleware' => ['auth']], function () {

    // CRM Routes Starts Here
    Route::get('/dashboard', [CrmBoardController::class, 'index'])->name('dashboard');
    Route::get('/teachers/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher-dashboard');
    Route::get('/dashboard/$2y$10$3HBCIwBV8OnAFYRdsBOQZOcTRWy5oqVHfCdYmYwJJ4mzj1Wj43mkW', [DeputyDirectorDashboardController::class, 'index'])->name('dd-dashboard');
    // Route::resources(['onboarding-status' => DeputyDirectorDashboardController::class]);
    Route::get('onboarding-status', [DeputyDirectorDashboardController::class, 'show'])->name('onboarding-status');
    Route::get('student_details_card', [DeputyDirectorDashboardController::class, 'student_details_card'])->name('student-details-card');
    Route::get('graph_data', [DeputyDirectorDashboardController::class, 'graph_data'])->name('graph-data');
    Route::get('on_boarding_list', [DeputyDirectorDashboardController::class, 'on_boarding_list'])->name('on-boarding-list');
    //Route::get('/fee_structure_dd', [DeputyDirectorDashboardController::class, 'fee_structure_dd'])->name('fee-structure-dd');
    Route::get('/fee-structure-filter', [DeputyDirectorDashboardController::class, 'fee_structure_filter'])->name('fee-structure-filter');
    Route::get('/dd-visit-request', [DeputyDirectorDashboardController::class, 'dd_visit_request'])->name('dd-visit-request');
    Route::post('/mark-attendance-in', [EmployeeAttendanceController::class, 'store'])->name('mark-attendance-in');
    Route::post('/mark-attendance-out', [EmployeeAttendanceController::class, 'update'])->name('mark-attendance-out');

    Route::get('/edit-attendance', [EmployeeAttendanceController::class, 'edit'])->name('edit-attendance');
    Route::get('/attendance-sheet', [EmployeeAttendanceController::class, 'attendance_sheet'])->name('attendance-sheet');
    Route::post('/attendance-mark', [EmployeeAttendanceController::class, 'attendance_mark'])->name('attendance-mark');
    Route::post('/update-marked-attendance', [EmployeeAttendanceController::class, 'update_marked_attendance'])->name('update-marked-attendance');

    Route::get('/board', [CrmBoardController::class, 'index'])->name('board');
    Route::get('/get-card-value', [CrmBoardController::class, 'cardValue'])->name('get-card-value');
    Route::post('/change-task-status', [CrmBoardController::class, 'changeTaskStatus'])->name('change-task-status');
    Route::get('/task-detail', [CrmBoardController::class, 'taskDetail'])->name('task-detail');
    Route::post('/add-task', [CrmBoardController::class, 'changeTaskStatus'])->name('add-task');
    Route::post('/add-task-member', [CrmBoardController::class, 'addTaskMember'])->name('add-task-member');
    Route::post('/add-project-member', [CrmBoardController::class, 'addProjectMember'])->name('add-project-member');
    Route::get('/show-project-members-modal', [CrmBoardController::class, 'showProjectMembersList'])->name('show-project-members-modal');
    // Route::post('/change-task-status', [CrmBoardController::class, 'changeTaskStatus'])->name('change-task-status');
    Route::post('/toggle-task-member', [CrmBoardController::class, 'toggleTaskMember'])->name('toggle-task-member');
    Route::get('/own-visit', [CrmBoardController::class, 'own_visit'])->name('own-visit');
    Route::get('/branch-revenue-data', [CrmBoardController::class, 'getBranchRevenueData'])->name('branch-revenue-data');
    Route::get('/student-growth-data', [CrmBoardController::class, 'getStudentGrowthData'])->name('student-growth-data');
    Route::get('/dashboard-metrics', [CrmBoardController::class, 'getDashboardMetrics'])->name('dashboard-metrics');
    Route::get('/branches-performance-data', [CrmBoardController::class, 'getBranchesPerformanceData'])->name('branches-performance-data');
    Route::get('/branch-wise-staff-students-data', [CrmBoardController::class, 'getBranchWiseStaffStudentsData'])->name('branch-wise-staff-students-data');
    Route::get('/student-trends-data', [CrmBoardController::class, 'getStudentTrendsData'])->name('student-trends-data');
    // CRM Routes Ends Here


    Route::resource('franchises-inquiry', FranchiseInquiryController::class);


    // Inquiry routes starts from here
    Route::resource('franchises', FranchiseInquiryOldController::class, ['except' => ['update']]);
    Route::resource('franchise-applications', FranchiseApplicationController::class, ['except' => ['update']]);
    Route::get('/application/{name?}', [FranchiseInquiryOldController::class, 'applications'])->name('applications');

    Route::get('/get-franchise-applications-docs', [FranchiseInquiryOldController::class, 'getFranchiseApplicationsDocs'])->name('get-franchise-applications-docs');
    Route::post('/save-franchise-applications-docs', [FranchiseInquiryOldController::class, 'saveFranchiseApplicationsDocs'])->name('save-franchise-applications-docs');

    Route::resource('franchise-application-la', FranchiseApplicationLaResponseController::class, ['except' => ['create']]);
    Route::get('franchise-application-la/create/{franchise_application_id}', [FranchiseApplicationLaResponseController::class, 'create'])->name('franchise-application-la.create');
    Route::get('/franchise-application-la/documents/{franchise_application_id}', [FranchiseApplicationLaResponseController::class, 'applicationDocuments'])->name('franchise-application-la.documents');
    Route::resource('franchise-application-qa', FranchiseApplicationQaController::class, ['except' => ['create']]);
    Route::get('franchise-application-qa/list/{franchise_application_id}', [FranchiseApplicationQaController::class, 'list'])->name('franchise-application-qa.list');
    Route::get('franchise-application-qa/create/{franchise_application_id}', [FranchiseApplicationQaController::class, 'create'])->name('franchise-application-qa.create');
    Route::get('franchise-application-qa/view_pdf/{franchise_application_qa_id}', [FranchiseApplicationQaController::class, 'view_pdf'])->name('franchise-application-qa.view_report');
    Route::get('franchise-application-qa/create_pdf/{franchise_application_qa_id}', [FranchiseApplicationQaController::class, 'create_pdf'])->name('franchise-application-qa.create_pdf');
    Route::resource('franchise-application-dd', FranchiseApplicationDdResponseController::class, ['except' => ['create']]);
    Route::get('franchise-application-dd/create/{franchise_application_id}', [FranchiseApplicationDdResponseController::class, 'create'])->name('franchise-application-dd.create');
    Route::resource('franchise-application-bd', FranchiseApplicationBdVisitController::class, ['except' => ['create']]);
    Route::get('franchise-application-bd/create/{franchise_application_id}', [FranchiseApplicationBdVisitController::class, 'create'])->name('franchise-application-bd.create');
    Route::resource('franchise-application-tors', FranchiseApplicationTorsResponseController::class, ['except' => ['create']]);
    Route::get('franchise-application-tors/create/{franchise_application_id}', [FranchiseApplicationTorsResponseController::class, 'create'])->name('franchise-application-tors.create');
    Route::resource('frachiseApplicationRemark', FrachiseApplicationRemarkController::class);
    Route::get('application_observations', [FrachiseApplicationRemarkController::class, 'index'])->name('application_observations');

    Route::post('/inquiry-personal-detail-update', [FranchiseInquiryOldController::class, 'inquiryPersonalDetailupdate'])->name('inquiry-personal-detail-update');
    Route::post('/inquiry-educational-organization', [FranchiseInquiryOldController::class, 'inquiryEducationalOrganization'])->name('inquiry-educational-organization');
    Route::post('/inquiry-led-franchise', [FranchiseInquiryOldController::class, 'inquiryLedFranchise'])->name('inquiry-led-franchise');
    Route::post('/load-inquiry-led-franchise', [FranchiseInquiryOldController::class, 'loadInquiryLedFranchise'])->name('load-inquiry-led-franchise');
    Route::post('/inquiry-school-building', [FranchiseInquiryOldController::class, 'inquirySchoolBuilding'])->name('inquiry-school-building');
    Route::post('/load-inquiry-school-building', [FranchiseInquiryOldController::class, 'loadInquirySchoolBuilding'])->name('load-inquiry-school-building');
    Route::post('/inquiry-existing-building', [FranchiseInquiryOldController::class, 'inquiryExistingBuilding'])->name('inquiry-existing-building');
    Route::post('/inquiry-property-details', [FranchiseInquiryOldController::class, 'inquiryPropertyDetails'])->name('inquiry-property-details');


    Route::get('/get-students/{branch_class_section_id}/{subject_id?}/{date?}', [StudentAttendanceController::class, 'getStudents'])->name('getStudents');
    Route::get('/view-attendance-calendar/{branch_class_section_id}/{subject_id}', [StudentAttendanceController::class, 'viewAttendanceCalendar'])->name('viewAttendanceCalendar');
    Route::resources(['student-attendances' => StudentAttendanceController::class]);
    Route::resource('branch-royalty', BranchRoyaltyController::class);
    // End Inquiry routes starts from here

    Route::resource('student-transfer-case', StudentTransferCaseController::class);
    Route::get('student-transfer-form-create', [StudentTransferCaseController::class, 'printTransferCase'])->name('students-transfer-form-create');
    Route::get('student-transfer-cancellation-form', [StudentTransferCaseController::class, 'createTransferCancellation'])->name('student-transfer-cancellation-form');
    Route::post('student-transfer-cancellation-store', [StudentTransferCaseController::class, 'storeTransferCancellation'])->name('student-transfer-cancellation-store');
    //transfer case approval section
    Route::get('student-transfer-approval-form', [StudentTransferCaseController::class, 'createTransferApproval'])->name('student-transfer-approval-form');
    Route::post('student-transfer-approval-store', [StudentTransferCaseController::class, 'storeTransferApproval'])->name('student-transfer-approval-store');

    Route::resource('student-withdrawal', StudentWithdrawalController::class);
    Route::get('student-withdrawal-form-create', [StudentWithdrawalController::class, 'createWithdrawalForm'])->name('students-withdrawal-form-create');
    Route::get('student-withdrawal-cancellation-form', [StudentWithdrawalController::class, 'createWithdrawalCancellation'])->name('student-withdrawal-cancellation-form');
    Route::post('student-withdrawal-cancellation-store', [StudentWithdrawalController::class, 'storeWithdrawalCancellation'])->name('student-withdrawal-cancellation-store');
    //Withdrawal approval section
    Route::get('student-withdrawal-approval-form', [StudentWithdrawalController::class, 'createWithdrawalApproval'])->name('student-withdrawal-approval-form');
    Route::post('student-withdrawal-approval-store', [StudentWithdrawalController::class, 'storeWithdrawalApproval'])->name('student-withdrawal-approval-store');
    //Withdrawal Refund section
    Route::get('student-withdrawal-refund-form', [StudentWithdrawalController::class, 'createWithdrawalRefund'])->name('student-withdrawal-refund-form');
    Route::post('student-withdrawal-refund-store', [StudentWithdrawalController::class, 'storeWithdrawalRefund'])->name('student-withdrawal-refund-store');
    Route::get('student-withdrawal-requests', [StudentWithdrawalController::class, 'withdrawalRequests'])->name('students-withdrawal-requests');
    Route::delete('student-withdrawal-requests-delete', [StudentWithdrawalController::class, 'withdrawalRequestsDelete'])->name('students-withdrawal-requests-delete');


    Route::get('auto-withdrawal', [StudentWithdrawalController::class, 'auto_withdrawal'])->name('auto-withdrawal');
    Route::post('update-auto-withdraw-students', [StudentWithdrawalController::class, 'updateAutoWithdrawalStudents'])->name('update-auto-withdraw-students');



    Route::resource('lesson-plans', LessonPlanController::class);
    Route::resource('class-subjects', ClassSubjectController::class);
    Route::post('add-slo-row', [LessonPlanController::class, 'add_slo_row'])->name('lesson-plans.add-slo-row');
    Route::post('add-activity', [LessonPlanController::class, 'add_activity'])->name('lesson-plans.add-activity');
    Route::delete('remove-lessonplan-attachment/{attachment}', [LessonPlanController::class, 'remove_attachment'])->name('lesson-plans.remove_attachment');
    Route::delete('lesson-plans/remove-activity/{activity}', [LessonPlanController::class, 'remove_activity'])->name('lesson-plans.remove_activity');
    Route::delete('lesson-plans/remove-slo/{studentLearningOutcome}', [LessonPlanController::class, 'remove_slo'])->name('lesson-plans.remove_slo');
    Route::get('lesson-plans/get-week-days/{lessonPlan}', [LessonPlanController::class, 'get_week_days'])->name('lesson-plans.get-week-days');
    Route::post('lesson-plans/update-status', [LessonPlanController::class, 'update_status'])->name('lesson-plans.update-status');
    Route::get('lesson-plans/taught-date/{lessonPlan}', [LessonPlanController::class, 'taught_date'])->name('lesson-plans.taught-date');
    Route::get('lesson-plans/list-attachments/{lessonPlan}', [LessonPlanController::class, 'list_attachments'])->name('lesson-plans.list-attachments');
    Route::get('lesson-plans-calendar', [LessonPlanController::class, 'lessonPlanCalendar'])->name('lesson-plans.calendar');
    Route::get('duplicate-lesson-plan/{id}', [LessonPlanController::class, 'duplicateLessonPlan'])->name('lesson-plans.duplicate');
    Route::post('/duplicate-selected-lesson-plan', [LessonPlanController::class, 'duplicateSelectedLessonPlan'])->name('duplicate-selected-lesson-plan');

    Route::resources(['branch-securities' => BranchSecurityController::class]);

    Route::resources(['campusOfficeType' => CampusOfficeTypeController::class]);

    Route::resources(['homeWorkDiary' => HomeWorkDiaryController::class]);
    Route::resources(['homeWorkDiaryDetial' => HomeWorkDiaryDetialController::class]);
    Route::get('/add-homework-detail/{id}', [HomeWorkDiaryDetialController::class, 'add_homework_detail'])->name('add-homework-detail');
    Route::get('/view-homework-detail/{id}', [HomeWorkDiaryDetialController::class, 'view_homework_detail'])->name('view-homework-detail');
    Route::get('/delete-homework-detail-attachment/{id}', [HomeWorkDiaryDetialController::class, 'delete_attachment'])->name('delete-homework-detail-attachment');

    // HOMEWORK API


    Route::get('teacher_evaluation', [TeacherObservationController::class, 'index'])->name('teacher_evaluation.index');


    Route::get('/teacher_evaluation/create', [TeacherObservationController::class, 'create'])->name('teacher_evaluation.create');
    Route::post('/teacher_evaluation', [TeacherObservationController::class, 'store'])->name('teacher_evaluation.store');
    Route::get('/teacher_evaluation/teachers/{branchId}', [TeacherObservationController::class, 'getTeachersByBranch'])->name('teacher_evaluation.teachers');


    Route::get('/observation_details/create', [ObservationDetailController::class, 'create'])->name('observation_details.create');
    Route::post('/observation_details', [ObservationDetailController::class, 'store'])->name('observation_details.store');
    Route::get('/getSections/{classId}', [ObservationDetailController::class, 'getSections']);


    Route::get('/observation_details/competency/{id}', [ObservationDetailController::class, 'getRatingView'])
        ->name('observation_details.competency');


    Route::post('/save-ratings', [ObservationDetailController::class, 'saveDimensionRatings'])->name('save-dimension-ratings');

    Route::get('/teacher_evaluation/show/{id}', [TeacherObservationController::class, 'show'])->name('teacher_evaluation.show');
    Route::get('answer-dimensions', [AnswerDimensionController::class, 'index'])->name('answer-dimensions.index');
    Route::get('answer-dimensions/create', [AnswerDimensionController::class, 'create'])->name('answer-dimensions.create');
    Route::post('answer-dimensions', [AnswerDimensionController::class, 'store'])->name('answer-dimensions.store');
    Route::get('answer-dimensions/{answerDimension}', [AnswerDimensionController::class, 'show'])->name('answer-dimensions.show');
    Route::get('answer-dimensions/{answerDimension}/edit', [AnswerDimensionController::class, 'edit'])->name('answer-dimensions.edit');
    Route::put('answer-dimensions/{answerDimension}', [AnswerDimensionController::class, 'update'])->name('answer-dimensions.update');
    Route::delete('answer-dimensions/{answerDimension}', [AnswerDimensionController::class, 'destroy'])->name('answer-dimensions.destroy');



    Route::get('question-dimensions', [QuestionDimensionController::class, 'index'])->name('question-dimensions.index');
    Route::get('question-dimensions/create', [QuestionDimensionController::class, 'create'])->name('question-dimensions.create');
    Route::post('question-dimensions', [QuestionDimensionController::class, 'store'])->name('question-dimensions.store');
    Route::get('question-dimensions/{questionDimension}', [QuestionDimensionController::class, 'show'])->name('question-dimensions.show');

    Route::get('question-dimensions/{questionDimension}/edit', [QuestionDimensionController::class, 'edit'])->name('question-dimensions.edit');

    Route::put('question-dimensions/{questionDimension}', [QuestionDimensionController::class, 'update'])->name('question-dimensions.update');
    Route::delete('question-dimensions/{questionDimension}', [QuestionDimensionController::class, 'destroy'])->name('question-dimensions.destroy');


    // Route to display the form for creating a new timetable entry
    Route::get('/timetables/create', [TimetableController::class, 'create'])->name('timetables.create');
    Route::get('/timetables', [TimetableController::class, 'index'])->name('timetables.index');
    Route::get('timetables/{id}/edit', [TimetableController::class, 'edit'])->name('timetables.edit');
    Route::put('timetables/{id}', [TimetableController::class, 'update'])->name('timetables.update');
    Route::delete('timetables/{id}', [TimetableController::class, 'destroy'])->name('timetables.destroy');
    Route::get('get-teacher-by-branches/{branch}', [TimetableController::class, 'getEmployeesByBranch']);



    // Route to store the new timetable entry
    Route::post('/timetables', [TimetableController::class, 'store'])->name('timetables.store');
    // Route::get('/get-teachers/{branch}', [TimetableController::class, 'getTeachersByBranch']);
    Route::post('/get-classes-by-teacher', [TimetableController::class, 'getClassesByTeacher'])->name('getClassesByTeacher');
    Route::post('/get-section-by-classes', [TimetableController::class, 'getSectionbyClasses'])->name('getSectionbyClasses');
    Route::post('/get-subject-by-classes', [TimetableController::class, 'getSubjectbyClasses'])->name('getSubjectbyClasses');





    // Route::get('/observation_details/competency', [ObservationDetailController::class, 'competency'])->name('observation_details.competency');


    Route::resources(['visitDetail' => VisitDetailController::class]);
    Route::get('/edit-visit-status/{id}', [VisitDetailController::class, 'edit_visit_status'])->name('edit-visit-status');
    Route::post('/update-visit-status', [VisitDetailController::class, 'update_visit_status'])->name('update-visit-status');

    Route::resources(['fee-concessions' => FeeConcessionController::class]);
    Route::resources(['fee-concessions-type' => FeeConcessionTypeController::class]);
    Route::resources(['fee-charges' => FeeChargeController::class]);
    Route::post('fee-charges/bulk-store', [FeeChargeController::class, 'bulkStore'])->name('fee-charges.bulk-store');
    Route::resources(['fee-charges-type' => FeeChargesTypeController::class]);
    Route::resources(['fee-packages' => FeePackageController::class]);
    Route::resources(['fee-packages-fee-charges' => FeePackagesFeeChargesController::class]);
    Route::resources(['fee-period' => FeePeriodController::class]);
    Route::resources(['fee-tier' => FeeTierController::class]);
    Route::resources(['promotion-requests' => PromotionRequestController::class]);
    Route::post('promotion-requests/bulk-store', [PromotionRequestController::class, 'bulkStore'])->name('promotion-requests.bulk-store');
    Route::post('promotion-requests/bulk-update', [PromotionRequestController::class, 'bulkUpdate'])->name('promotion-requests.bulk-update');
    Route::post('promotion-requests/list/{id}', [PromotionRequestController::class, 'listPromotionRequest'])->name('promotion-requests.list-promotion-request');
    Route::get('promotion-requests-student-list', [PromotionRequestController::class, 'showStudentsList'])->name('promotion-requests.student-list');
    Route::get('promotion-requests-get-filters', [PromotionRequestController::class, 'getFilters'])->name('promotion-requests.get-filter');
    Route::get('promotion-requests-individual', [PromotionRequestController::class, 'showIndividualPromotion'])->name('promotion-requests.individual');
    Route::get('student-promotion-approval-form', [PromotionRequestController::class, 'createRequestApproval'])->name('student-promotion-approval-form');
    Route::post('student-promotion-approval-store', [PromotionRequestController::class, 'storeRequestApproval'])->name('student-promotion-approval-store');
    Route::get('student-promotion-rejection-form', [PromotionRequestController::class, 'createRequestRejection'])->name('student-promotion-rejection-form');
    Route::post('student-promotion-rejection-store', [PromotionRequestController::class, 'storeRequestRejection'])->name('student-promotion-rejection-store');

    Route::get('get-state-constituencies', [ConstituencyController::class, 'get_state_constituencies'])->name('constituencies.get_state_constituencies');
    Route::resources(['teacher-types' => TeacherTypeController::class]);
    Route::resources(['class_students' => ClassStudentController::class]);
    Route::resources(['class-teachers' => ClassTeacherController::class]);
    Route::get('class-teachers-type', [ClassTeacherController::class, 'editTeacherType'])->name('class-teachers-type');
    Route::post('class-teachers-type-update', [ClassTeacherController::class, 'updateTeacherType'])->name('class-teachers-type-update');
    // Route::resources(['class_teacher' => ClassTeacherController::class]);
    Route::get('class-teacher-subjects/{branch_class_section_id}', [ClassTeacherController::class, 'class_teacher_subjects'])->name('class-teacher-subjects');
    Route::get('class-section-teachers', [ClassTeacherController::class, 'class_section_teachers'])->name('class-section-teachers');
    Route::resources(['class-student-subjects' => ClassStudentSubjectController::class]);
    Route::resources(['contact-information' => ContactInformationController::class]);
    Route::resources(['student-addresses' => StudentAddressController::class]);
    Route::resources(['students' => StudentController::class]);
    Route::put('update-student-previous-school', [StudentController::class, 'updatePreviousSchool'])->name('students.update-previous-school');
    Route::get('open-import-modal', [StudentController::class, 'openImportModal'])->name('open-import-modal');
    Route::post('import-students', [StudentController::class, 'importStudent'])->name('import-students');

    Route::get('export-students', [StudentController::class, 'exportStudents'])->name('export-students');
    Route::get('students/search-onroll', [StudentController::class, 'searchOnRollStudents'])->name('students.search.onroll');

    // Previous Data Import Routes
    Route::get('import-previous-data', [StudentInvoiceController::class, 'importPreviousDataView'])->name('import.previous.data');
    Route::post('students/import-previous-arrears', [StudentInvoiceController::class, 'importPreviousArrears'])->name('students.import.previous.arrears');
    Route::post('students/import-previous-advance', [StudentInvoiceController::class, 'importPreviousAdvance'])->name('students.import.previous.advance');
    Route::post('students/bulk-import-arrears', [StudentInvoiceController::class, 'bulkImportPreviousArrears'])->name('students.bulk.import.arrears');
    Route::post('students/bulk-import-advance', [StudentInvoiceController::class, 'bulkImportPreviousAdvance'])->name('students.bulk.import.advance');
    Route::get('students/download-template/{type}', [StudentInvoiceController::class, 'downloadTemplate'])->name('students.download.template');
    Route::get('students-leaving-certificate-create', [StudentController::class, 'printLeavingCase'])->name('students-leaving-certificate-create');
    Route::resources(['guardians' => GuardianController::class]);
    Route::resources(['student-fee-packages' => StudentFeePackageController::class]);
    Route::resources(['admission-query' => AdmissionQueryController::class]);
    Route::get('export-admission-queries', [AdmissionQueryController::class, 'exportAdmissionQueries'])->name('exportAdmissionQueries');
    Route::resources(['inquiry-type' => InquiriesTypeController::class]);
    Route::resources(['followUpType' => FollowUpTypeController::class]);
    Route::resources(['admissionFollowUp' => AdmissionFollowUpController::class]);
    // Route::resources(['class-teachers' => ClassTeacherController::class]);
    // Route::get('class-sections', [BranchClassSectionController::class, 'branch_class_sections'])->name('branch-setup.branch-class-sections');
    Route::resources(['class-students' => ClassStudentController::class]);
    Route::get('class-section-students/{branch_class_section_id}', [ClassStudentController::class, 'class_section_students'])->name('branch-setup.class-section-students');
    Route::get('/{user_type?}/class-sections', [BranchClassSectionController::class, 'branch_class_sections'])->name('branch-setup.branch-class-sections');
    Route::get('class-section-students/{branch_class_section_id}', [ClassStudentController::class, 'class_section_students'])->name('class-section-students');
    Route::resources(['student-invoices' => StudentInvoiceController::class]);
    Route::post('student-invoices/update-payment-status/{studentInvoice}', [StudentInvoiceController::class, 'updatePaymentStatus'])->name('student-invoices.update-payment-status');
    Route::get('student-invoices/download-challan/{studentInvoice}', [StudentInvoiceController::class, 'generateChallan'])->name('download-challan');
    Route::get('students/create_registration_slip/{student_id}/{type}', [StudentController::class, 'create_registration_slip'])->name('students.create_registration_slip');
    Route::get('students/download-security-challan/{student_id}', [StudentController::class, 'generateSecurityChallan'])->name('download-security-challan');
    Route::get('students/attach_monthly_package/{student}', [StudentFeePackageController::class, 'attachMonthlyPackageToStudent'])->name('students.attach_monthly_package');

    Route::resources(['general-document' => GeneralDocumentController::class]);
    Route::get('general-document/class-timetable/index', [GeneralDocumentController::class, 'class_timetable'])->name('general-document.class-timetable.index');
    Route::get('general-document/subject-teacher-timetable/index', [GeneralDocumentController::class, 'subject_teacher_timetable'])->name('general-document.subject-teacher-timetable.index');
    Route::get('general-document/syllabus/index', [GeneralDocumentController::class, 'syllabus'])->name('general-document.syllabus.index');
    Route::get('general-document/assessment-paper/index', [GeneralDocumentController::class, 'assessment_paper'])->name('general-document.assessment-paper.index');
    Route::get('general-document/winter-resource-pack/index', [GeneralDocumentController::class, 'winter_resource_pack'])->name('general-document.winter-resource-pack.index');
    Route::get('general-document/summer-resource-pack/index', [GeneralDocumentController::class, 'summer_resource_pack'])->name('general-document.summer-resource-pack.index');
    Route::get('general-document/certificates/index', [GeneralDocumentController::class, 'certificates'])->name('general-document.certificates.index');
    Route::get('general-document/school-manual/index', [GeneralDocumentController::class, 'school_manual'])->name('general-document.school-manual.index');
    Route::get('general-document/admission-test/index', [GeneralDocumentController::class, 'admission_test'])->name('general-document.admission-test.index');
    Route::get('general-document/infinity-teacher-guide/index', [GeneralDocumentController::class, 'infinity_teacher_guide'])->name('general-document.infinity-teacher-guide.index');
    Route::get('general-document/support-staff-uniform/index', [GeneralDocumentController::class, 'support_staff_uniform'])->name('general-document.support-staff-uniform.index');
    Route::get('branding-marketing/index', [BrandingMarketingController::class, 'index'])->name('branding-marketing.index');
    Route::get('academic-calendar/index', [AcademicCalendarController::class, 'index'])->name('academic-calendar.index');

    Route::resources(['cities' => CityController::class]);
    Route::resources(['towns' => TownController::class]);
    Route::resources(['booklist' => BooklistController::class]);
    Route::resources(['scheme_of_work' => SchemeOfWorkController::class]);

    Route::get('bulk-invoices', [StudentInvoiceController::class, 'bulkInvoiceView'])->name('bulk-invoices');
    Route::get('get-bulk-invoice-data', [StudentInvoiceController::class, 'getDataForAdmin'])->name('get-bulk-invoice-data');
    Route::get('preview-invoices', [StudentInvoiceController::class, 'bulkInvoiceView'])->name('preview-invoices');
    Route::get('super-admin-bulk-invoices', [StudentInvoiceController::class, 'superAdminBulkInvoiceView'])->name('super-admin-bulk-invoices');
    Route::post('generate-bulk-invoices', [StudentInvoiceController::class, 'generateBulkInvoices'])->name('generate-bulk-invoices');
    Route::post('change-bulk-invoices-status', [StudentInvoiceController::class, 'changeBulkInvoiceStatus'])->name('change-bulk-invoices-status');
    Route::post('generate-bulk-challan', [StudentInvoiceController::class, 'generateBulkChallans'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('generate-bulk-challan');
    
    // Enhanced bulk challan generation routes
    Route::post('generate-enhanced-bulk-challans', [StudentInvoiceController::class, 'generateEnhancedBulkChallans'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('generate-enhanced-bulk-challans');
    Route::get('get-students-for-fee-package', [StudentInvoiceController::class, 'getStudentsForFeePackage'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('get-students-for-fee-package');
    
    // API endpoints for enhanced bulk challan generation
    Route::get('get-fee-packages', [StudentInvoiceController::class, 'getFeePackages'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('get-fee-packages');
    Route::get('get-enhanced-fee-periods', [StudentInvoiceController::class, 'getFeePeriods'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('get-enhanced-fee-periods');
    Route::get('get-classes-for-fee-package', [StudentInvoiceController::class, 'getClassesForFeePackage'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('get-classes-for-fee-package');
    Route::get('get-sections-for-class', [StudentInvoiceController::class, 'getSectionsForClass'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('get-sections-for-class');
    
    // Enhanced bulk challan generation view
    Route::get('enhanced-bulk-challans', [StudentInvoiceController::class, 'enhancedBulkChallanView'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('enhanced-bulk-challans');
    
    // Simplified bulk challan generation routes
    Route::post('generate-simple-bulk-challans', [StudentInvoiceController::class, 'generateSimpleBulkChallans'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('generate-simple-bulk-challans');
    Route::get('get-fee-package-charges', [StudentInvoiceController::class, 'getFeePackageCharges'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('get-fee-package-charges');
    
    // Bulk mark as paid routes
    Route::get('bulk-mark-as-paid', [StudentInvoiceController::class, 'bulkMarkAsPaidView'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('bulk-mark-as-paid');
    Route::get('get-unpaid-invoices', [StudentInvoiceController::class, 'getUnpaidInvoices'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('get-unpaid-invoices');
    Route::post('bulk-mark-as-paid', [StudentInvoiceController::class, 'bulkMarkAsPaid'])
        ->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant'])
        ->name('bulk-mark-as-paid-process');
    
    Route::post('royalty-computation-export', [StudentInvoiceController::class, 'royaltyComputationExport'])->name('royalty-computation-export');
    Route::post('/update-selected-invoice', [StudentInvoiceController::class, 'updateUnpaidInvoices'])->name('update-selected-invoice');
    Route::post('/update-adjusted-invoice', [StudentInvoiceController::class, 'updateAdjustedInvoices'])->name('update-adjusted-invoice');

    Route::resources(['student-ledger' => StudentLedgerController::class]);
    Route::resources(['student-concession' => StudentConcessionController::class]);
    Route::resources(['family-info' => FamilyInformationController::class]);
    Route::resources(['sibling-info' => SiblingInformationController::class]);

    Route::get('reports/computation', [StudentInvoiceController::class, 'reportRoyaltyComputation'])->name('reports-computation');
    Route::get('reports/computation-report', [StudentInvoiceController::class, 'reportRoyaltyComputationReport'])->name('reports-computation-report');
    Route::get('reports/reimbursement', [StudentInvoiceController::class, 'reportRoyaltyReimbursement'])->name('reports-reimbursement');
    Route::get('reports/attendance', [StudentAttendanceController::class, 'attendanceReport'])->name('reports-attendance');
    
    // Comprehensive Attendance Report Routes
    Route::get('reports/comprehensive-attendance', [AttendanceReportController::class, 'index'])->name('attendance-report.index');
    Route::get('reports/comprehensive-attendance/pdf', [AttendanceReportController::class, 'generatePDF'])->name('attendance-report.pdf');
    Route::get('reports/comprehensive-attendance/data', [AttendanceReportController::class, 'getReportData'])->name('attendance-report.data');
    
    // Daily Operational Report
    Route::get('reports/daily-operational', [App\Http\Controllers\DailyOperationalReportController::class, 'index'])->name('daily-operational-report.index');

    Route::get('reports/sibling_report', [UcsReportsController::class, 'sibling_report'])->name('sibling-report');
    Route::get('reports/sibling_report/export', [UcsReportsController::class, 'sibling_report_export'])->name('sibling-report-export');
    Route::get('reports/student_concession_report', [UcsReportsController::class, 'student_concession_report'])->name('student-concession-report');
    Route::get('reports/branches_report', [UcsReportsController::class, 'branches_report'])->name('branches-report');

    Route::get('class-section-students/{branch_class_section_id}', [ClassStudentController::class, 'class_section_students'])->name('branch-setup.class-section-students');
    Route::get('class-section-students/{branch_class_section_id}', [ClassStudentController::class, 'class_section_students'])->name('class-section-students');
    Route::resources(['student-invoices' => StudentInvoiceController::class]);
    Route::get('student-invoices/update-payment-status/{studentInvoice}', [StudentInvoiceController::class, 'updatePaymentStatus'])->name('student-invoices.update-payment-status-view');
    Route::get('student-invoices/payment-status-modal/{studentInvoice}', [StudentInvoiceController::class, 'paymentStatusModal'])->name('payment-status-modal');
    Route::get('student-invoices/download-challan/{studentInvoice}', [StudentInvoiceController::class, 'generateChallan'])->name('download-challan');
    Route::get('students/create_registration_slip/{student_id}/{type}', [StudentController::class, 'create_registration_slip'])->name('students.create_registration_slip');
    Route::get('students/attach_monthly_package/{student}', [StudentFeePackageController::class, 'attachMonthlyPackageToStudent'])->name('students.attach_monthly_package');
    Route::resources(['student-transfer-reason' => StudentTransferReasonController::class]);

    // Employee Import Routes (must be before the resource route to avoid conflicts)
    Route::get('/employees/import', [EmployeeController::class, 'showImportForm'])->name('employees.import.form');
    Route::post('/employees/import', [EmployeeController::class, 'importEmployees'])->name('employees.import');
    Route::get('/employees/download-template', [EmployeeController::class, 'downloadTemplate'])->name('employees.download-template');
    Route::get('/employees/import-stats', [EmployeeController::class, 'getImportStats'])->name('employees.import-stats');

    Route::resources(['employees' => EmployeeController::class]);
    Route::get('/edit-employee-password/{id}', [UsersController::class, 'editUserPassword'])->name('edit-employee-password');
    Route::post('/update-employee-password', [UsersController::class, 'updateUserPassword'])->name('update-employee-password');
    Route::get('/edit-employee/{id}', [EmployeeController::class, 'editEmployee'])->name('edit-employee');
    Route::post('/update-employee/{id}', [EmployeeController::class, 'updateEmployee'])->name('update-employee');
    Route::get('/get-employees/{id}', [EmployeeController::class, 'getBranchAdministrativeStaff'])->name('get-employees');
    Route::get('/employee/salary-slip/{id?}', [EmployeeController::class, 'generateEmployeeSalarySlip'])->name('employee.salary-slip');
    Route::get('/employee/salary-slip/{id?}/{month}/{year}', [EmployeeController::class, 'generateEmployeeSalarySlipForPeriod'])->name('employee.salary-slip.period');
    Route::resources(['employee-dependent' => EmployeeDependentController::class]);
    Route::resources(['employee-shift' => EmployeeWorkingDayController::class]);
    Route::resources(['employee-official-leave-day' => EmployeeOfficialLeaveDayController::class]);
    Route::post('/get-reporting-manager', [EmployeeController::class, 'getReportingManager']);
    Route::get('/get-employee-using-emp-id', [EmployeeController::class, 'getEmployeeUsingEmpId'])->name('getEmployeeUsingEmpId');
    Route::get('my-attendance', [EmployeeController::class, 'getEmployeeAttendance'])->name('my-attendance');
    Route::get('/unpaid-students-list', [UcsReportsController::class, 'UnpaidStudentReport'])->name('unpaid-students-list');
    Route::get('/unpaid-students-export', [UcsReportsController::class, 'export_unpaid_student'])->name('unpaid-students-export');
    Route::get('/paid-students-list', [UcsReportsController::class, 'PaidStudentReport'])->name('paid-students-list');
    Route::get('/paid-students-export', [UcsReportsController::class, 'export_paid_student'])->name('paid-students-export');
    Route::get('/students-concession-export', [UcsReportsController::class, 'export_student_concession'])->name('students-concession-export');
    Route::get('/students-relation-list', [UcsReportsController::class, 'StudentsRelationReport'])->name('students-relation-list');
    Route::get('/students-relation-export', [UcsReportsController::class, 'export_student_relation'])->name('students-relation-export');
    // Route::get('/sibling-report', [UcsReportsController::class, 'sibling_report'])->name('report.sibling-report');
    Route::get('/invoice-status-report', [UcsReportsController::class, 'InvoiceStatusReport'])->name('invoice-status-report');
    Route::get('/transfer-in-out', [UcsReportsController::class, 'TransferInOutReport'])->name('transfer-in-out');
    Route::get('/transfer-in-out-export', [UcsReportsController::class, 'export_transfer_in_out'])->name('transfer-in-out-export');
    Route::get('/student-promotion-report', [UcsReportsController::class, 'StudentPromotionsReport'])->name('student-promotion-report');
    Route::get('/student-promortions-export', [UcsReportsController::class, 'StudentPromotionReport'])->name('student-promortions-export');
    Route::get('/employee-report', [UcsReportsController::class, 'EmployeeReport'])->name('employee-report');
    Route::get('/employee-export', [UcsReportsController::class, 'export_employee'])->name('employee-export');
    Route::get('/general-ledger-report', [UcsReportsController::class, 'GeneralLedgerReport'])->name('general-ledger-report');
    Route::get('/general-ledger-export', [UcsReportsController::class, 'export_general_ledger'])->name('general-ledger-export');

    Route::get('/{user_type?}/class-sections', [BranchClassSectionController::class, 'branch_class_sections'])->name('branch-setup.branch-class-sections');
    Route::resources(['branch-class-sections' => BranchClassSectionController::class]);
    Route::resources(['support-query' => SupportQueryController::class]);
    Route::resources(['branches' => BranchController::class]);
    Route::resources(['bank-accounts' => BankAccountController::class]);
    Route::resources(['branch-working-shift' => BranchWorkingShiftController::class]);
    Route::resources(['branch-academic-year' => BranchAcademicYearController::class]);
    Route::resources(['assessment-entry' => AssessmentEntryController::class]);
    Route::resources(['subject-remarks' => SubjectRemarkController::class]);
    Route::resources(['system-notifications' => SystemNotificationController::class]);
    Route::get('nwa_notifcation_card', [SystemNotificationController::class, 'nwa_notifcation_card'])->name('nwa-notifcation-card');
    Route::get('/notification-logs/{id}', [SystemNotificationController::class, 'system_notification_logs'])->name('notification-logs');
    Route::get('/student-assessments-list', [StudentController::class, 'studentAssessments'])->name('student-assessments-list');
    Route::resources(['assessment-level' => AssessmentLevelController::class]);
    Route::resources(['skill' => SkillController::class]);
    Route::resources(['general-behaviour' => GeneralBehaviourController::class]);
    Route::resources(['grading-criteria' => GradingCriteriaController::class]);
    Route::resources(['subject-marks-setup' => SubjectMarksSetupController::class]);
    Route::resources(['student-behaviour-skill' => StudentBehaviourSkillController::class]);
    Route::resources(['grade-book' => GradeBookController::class]);
    Route::get('student-behaviour-skill/skill_modal/{student_id}', [StudentBehaviourSkillController::class, 'skill_modal'])->name('student-behaviour-skill.skill-modal');
    Route::get('gradebook/list-students', [GradeBookController::class, 'list_students'])->name('gradebook.list-students');
    Route::get('gradebook/generate-bulk-reports', [GradeBookController::class, 'generateBulkProgressReports'])->name('gradebook.generate-bulk-progress-reports');
    Route::get('gradebook/get-report/{student_id}/{student_behaviour_skill_id}', [GradeBookController::class, 'get_report'])->name('grade-book.get-report');
    Route::get('student-behaviour-skill/behaviour_modal/{student_id}', [StudentBehaviourSkillController::class, 'behaviour_modal'])->name('student-behaviour-skill.behaviour-modal');
    Route::resources(['academic-year-working-days' => AcademicYearWorkingDaysController::class]);
    Route::get('parent-queries/index', [ParentQueryController::class, 'index'])->name('parent-queries.index');
    Route::resources(['gradebook-history' => GradeBookHistoryController::class]);

    //Resource route to manage curriculam
    Route::resources(['curriculum' => \App\Http\Controllers\CurriculumController::class]);
    Route::get('show-curriculum', [\App\Http\Controllers\CurriculumController::class, 'show'])->name('show-curriculum');
    Route::post('store-curriculum', [\App\Http\Controllers\CurriculumController::class, 'store'])->name('store-curriculum');
    Route::resource('lesson-plan-generate', \App\Http\Controllers\LessonPlanGenerateController::class);
    Route::get('/generate-lesson-plans/create', [LessonPlanGenerateController::class, 'create'])->name('generate_lesson.create');

    // Route for storing a new lesson plan
    Route::post('/generate-lesson-plans', [LessonPlanGenerateController::class, 'store'])->name('generate_lesson.store');

    Route::group(['middleware' => ['role:super_admin|network_associate|teacher']], function () {
        Route::resources(['network-associates' => NetworkAssociateController::class]);
        Route::resources(['network-associates-branches' => NetworkAssociateBranchController::class]);
        Route::resources(['paper-types' => PaperTypeController::class]);

        // Route::resources(['class-teachers' => ClassTeacherController::class]);
        // Route::get('class-sections', [BranchClassSectionController::class, 'branch_class_sections'])->name('branch-setup.branch-class-sections');

        Route::resources(['departments' => DepartmentController::class]);
        Route::resources(['designations' => DesignationController::class]);
        Route::resources(['categories' => CategoryController::class]);
        Route::resources(['academic-classes' => AcademicClassController::class]);

        Route::post('network-associates-branches/set_branch', [NetworkAssociateBranchController::class, 'set_branch'])->name('network-associates-branches.set_branch');
        Route::get('network-associates/profile/{id}', [NetworkAssociateController::class, 'profile'])->name('network-associates.profile');

        Route::resources(['designationLeaveQuota' => DesignationLeaveQuotaController::class]);
        //Route::get('designation/leave-quotas', [DesignationLeaveQuotaController::class, 'index'])->name('designation-leave-quotas.index');
        Route::post('designation/leave-quota', [DesignationLeaveQuotaController::class, 'store'])->name('designation-leave-quota.store');
        Route::get('designation/leave-quota/{id}/edit', [DesignationLeaveQuotaController::class, 'show'])->name('designation-leave-quota.edit');
        Route::post('designation/leave-quota/delete', [DesignationLeaveQuotaController::class, 'delete'])->name('designation-leave-quota.delete');
    });

    Route::group(['middleware' => ['role:super_admin|network_associate|teacher|academic_head']], function () {});

    Route::group(['middleware' => ['role:super_admin|network_associate|finance-manager|accountant']], function () {
        Route::resources(['beams-challans' => BeamsChallanController::class]);
    });

    // Route::group(['middleware' => ['role:teacher']], function () {
    // });

    Route::get('/get-employee-password', [UsersController::class, 'getUserPassword'])->name('get-employee-password');
    Route::post('/change-employee-password', [UsersController::class, 'changeUserPassword'])->name('change-employee-password');
    Route::get('my-leave-qouta/{id}', [EmployeeLeaveQuotaController::class, 'getEmployeeLeaveQouta'])->name('my-leave-qouta');
    Route::get('show-leave-requests/{id}', [LeaveApplicationController::class, 'getLeaveRequests'])->name('show-leave-requests');
    Route::get('leave-application/employee/{id}', [LeaveApplicationController::class, 'index'])->name('leave.application');
    Route::post('leave-application/create', [LeaveApplicationController::class, 'store'])->name('leave.application.create');
    Route::post('leave-application/forms', [LeaveApplicationController::class, 'getFormsView'])->name('leave.applications.forms');
    Route::get('leave-application/employee/{id}/applied-list', [LeaveApplicationController::class, 'leaveAppliedListShow'])->name('leave.application.applied-list');
    Route::post('leave-application/details', [LeaveApplicationController::class, 'details'])->name('leave.application.details');
    Route::post('leave-application/status-update', [LeaveApplicationController::class, 'statusUpdate'])->name('leave.application.status-update');
    Route::post('leave-application/status-approve', [LeaveApplicationController::class, 'statusApprove'])->name('leave.application.status-approve');
    Route::post('leave-application/already-applied', [LeaveApplicationController::class, 'alreadyApplied'])->name('leave.application.already-applied');
    Route::get('/view-branch-schedule', [BranchWorkingShiftController::class, 'viewBranchSchedule'])->name('view-branch-schedule');
    Route::group(['middleware' => ['role:super_admin|human_resource']], function () {
        Route::resources(['classes' => ComClassController::class]);
        Route::resources(['class-groups' => ClassGroupController::class]);
        Route::resources(['companies' => CompanyController::class]);
        Route::resources(['countries' => CountryController::class]);
        Route::resources(['regions' => RegionController::class]);
        Route::resources(['sections' => SectionController::class]);
        Route::resources(['states' => StateController::class]);
        Route::resources(['subject-groups' => SubjectGroupController::class]);
        Route::resources(['subjects' => SubjectController::class]);
        Route::resources(['staff-type' => StaffTypeController::class]);

        Route::resources(['student-previous-school' => StudentPreviousSchoolController::class]);
        Route::get('/school-description', [StudentPreviousSchoolController::class, 'getSchoolDescription'])->name('student.school-description');
        Route::post('make-bank-default', [BankAccountController::class, 'make_bank_default'])->name('make-bank-default');

        Route::resources(['building-type' => BuildingTypeController::class]);
        Route::resources(['language' => LanguageController::class]);
        Route::resources(['academic-year' => AcademicYearController::class]);
        Route::resources(['system-modules' => SystemModuleController::class]);
        Route::resources(['withdrawal-reason' => WithdrawalReasonController::class]);
        Route::resources(['withdrawal-cancellation-reason' => WithdrawalCancellationReasonController::class]);
        Route::resources(['tax' => TaxController::class]);
        Route::resources(['tax-type' => TaxTypeController::class]);

        Route::get('/class-groups-classes', [ClassGroupController::class, 'showClasses'])->name('groups-classes');
        Route::post('/add-class-groups-classes', [ClassGroupController::class, 'addGroupClasses'])->name('add-groups-classes');

        /* Roles & Permissions Routes */
        Route::resources(['users' => UsersController::class]);
        Route::resources(['permissions' => PermissionController::class]);
        Route::resources(['roles' => RoleController::class]);
        //Route::get('/list_employees_roles_permissions', [UsersController::class,'userRolesPermissionList'])->name('roles-permission-assignment-list');
        Route::get('/roles-permission-assignment-list', [UsersController::class, 'userRolesPermissionList'])->name('roles-permission-assignment-list');
        Route::get('edit-with-role-permissions/{id}', [UsersController::class, 'editUserRolesPermissions'])->name('edit-with-role-permissions');
        Route::post('assign-role-permissions/{id}', [UsersController::class, 'updateUserRolesPermissions'])->name('assign-role-permissions');
        Route::resources(['application-type' => ApplicationTypeController::class]);
        Route::resources(['working-day' => WorkingDayController::class]);
        Route::resources(['working-shift' => WorkingShiftController::class]);
        Route::resources(['official-leave-day' => OfficialLeaveDayController::class]);
        Route::get('leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index');
        Route::post('leave-type', [LeaveTypeController::class, 'store'])->name('leave-type.store');
        Route::get('leave-type/{id}/edit', [LeaveTypeController::class, 'show'])->name('leave-type.edit');
        Route::post('leave-type/update', [LeaveTypeController::class, 'update'])->name('leave-type.update');
        Route::post('leave-type/delete', [LeaveTypeController::class, 'delete'])->name('leave-type.delete');
    });

    Route::resources(['/guardian-info-update' => GuardianInfoUpdateController::class]);
    Route::get('/list-states', [CommonController::class, 'listStates'])->name('list-states');
    Route::get('/list-skills', [CommonController::class, 'listSkills'])->name('list-skills');
    Route::get('/list-towns', [CommonController::class, 'listTowns'])->name('list-towns');
    Route::get('/list-branches', [CommonController::class, 'listBranches'])->name('list-branches');

    Route::get('/list-academic-branches', [CommonController::class, 'listAcademicBranches'])->name('list-academic-branches');
    Route::get('/list-academic-branch-classes', [CommonController::class, 'listAcademicBranchClasses'])->name('list-academic-branch-classes');

    Route::get('/list-academic-branch-class-sections', [CommonController::class, 'listAcademicBranchClassSection'])->name('list-academic-branch-class-sections');

    Route::get('/list-branches-by-region', [CommonController::class, 'listBranchesByRegion'])->name('list-branches-by-region');
    Route::get('/list-branches-by-state', [CommonController::class, 'listBranchesByState'])->name('list-branches-by-state');
    Route::get('/list-students', [CommonController::class, 'listStudents'])->name('list-students');
    Route::get('/get-fee-package-period', [CommonController::class, 'getFeePackagePeriod'])->name('get-fee-package-period');
    Route::get('/get-fee-periods', [CommonController::class, 'getFeePeriodByYear'])->name('get-fee-periods');
    Route::get('/get-fee-periods-by-branch', [CommonController::class, 'getFeePeriodsByBranch'])->name('get-fee-periods-by-branch');
    Route::get('/list-invoice-students', [StudentInvoiceController::class, 'getInvoiceStudents'])->name('list-invoice-students');
    Route::get('/list-invoices', [CommonController::class, 'listInvoices'])->name('list-invoices');
    Route::get('/nwa-list-invoices', [CommonController::class, 'NWAlistInvoices'])->name('nwa-list-invoices');
    Route::get('/list-academic-years', [CommonController::class, 'listAcademicYears'])->name('list-academic-years');
    Route::get('/list-network-associates', [CommonController::class, 'listNetworkAssociates'])->name('list-network-associates');
    Route::get('/list-sections/{branch}', [CommonController::class, 'listSection'])->name('list-sections');
    Route::get('/get-class-subjects/{branch_id}', [CommonController::class, 'getClassSubjects'])->name('get-class-subjects');
    Route::get('/get-class-subjects-by-classID', [CommonController::class, 'getClassSubjectsByClassID'])->name('get-class-subjects-by-classID');
    Route::get('/get-class-section-subjects/{branch_id}', [CommonController::class, 'getClassSectionSubjects'])->name('get-class-section-subjects');
    Route::get('/list-branch-classes', [CommonController::class, 'listBranchClasses'])->name('list-branch-classes');
    Route::get('/get-branch-classes', [CommonController::class, 'getBranchClasses'])->name('get-branch-classes');
    Route::get('/list-branch-classes-months', [CommonController::class, 'listBranchClassesMonths'])->name('list-branch-classes-months');
    Route::get('/list-branch-classes-sections', [CommonController::class, 'listBranchClassesSections'])->name('list-branch-classes-sections');
    Route::get('/get-branch-classes-sections', [CommonController::class, 'getBranchClassesSections'])->name('get-branch-classes-sections');
    Route::get('/list-class-sections', [CommonController::class, 'listClassSections'])->name('list-class-sections');
    Route::get('/list-class-section-feeperiod', [CommonController::class, 'listClassSectionFeeperiod'])->name('list-class-section-feeperiod');
    Route::get('/list-class-subjects', [CommonController::class, 'listClassSubjects'])->name('list-class-subjects');

    Route::get('/get-curriculum-attainment-targets', [LessonPlanController::class, 'getCurriculumAttainmentTargets'])
        ->name('get-curriculum-attainment-targets');





    Route::get('/list-branch-terms', [CommonController::class, 'listBranchTerms'])->name('list-branch-terms');
    Route::get('/list-term-weeks', [CommonController::class, 'listTermWeeks'])->name('list-term-weeks');
    Route::get('/get-fee-period', [CommonController::class, 'getFeePeriod'])->name('get-fee-period');
    Route::get('/get-assessment-level-child', [CommonController::class, 'getAssessmentLevelChild'])->name('get-assessment-level-child');
    Route::get('/get-student', [CommonController::class, 'getStudentById'])->name('get-student');
    Route::get('/get-student-relation', [CommonController::class, 'getStudentRelationById'])->name('get-student-relation');
    Route::get('/test-pdf', [CommonController::class, 'testPDF'])->name('test-pdf');
    Route::get('/kg-progress-report', [CommonController::class, 'showKgProgressReport'])->name('kg-progress-report');
    Route::get('/upper-primary-progress-report', [CommonController::class, 'showUpperPrimaryProgressReport'])->name('upper-primary-progress-report');
    Route::get('/lower-primary-progress-report', [CommonController::class, 'showLowerPrimaryProgressReport'])->name('lower-primary-progress-report');
    Route::get('/middle-school-progress-report', [CommonController::class, 'showMiddleSchoolProgressReport'])->name('middle-school-progress-report');
    Route::get('/nursery-progress-report', [CommonController::class, 'showNurseryProgressReport'])->name('nursery-progress-report');
    Route::get('/pre-nursery-progress-report', [CommonController::class, 'showPreNurseryProgressReport'])->name('pre-nursery-progress-report');
    Route::get('/term1-progress-report/{student_id}', [CommonController::class, 'showProgressReport'])->name('term1-progress-report');
});

Route::get('ptm', [PTMController::class, 'index'])->name('ptm.index');
Route::get('ptm/getPTMInfo/{student_id?}', [PTMController::class, 'getPTMInfo'])->name('ptm.getPTMInfo');
Route::get('ptm/create', [PTMController::class, 'create'])->name('ptm.create');
Route::post('ptm', [PTMController::class, 'store'])->name('ptm.store');
Route::get('ptm/{id}/edit', [PTMController::class, 'edit'])->name('ptm.edit');
Route::put('ptm/{id}', [PTMController::class, 'update'])->name('ptm.update');
Route::delete('ptm/{id}', [PTMController::class, 'destroy'])->name('ptm.destroy');

Route::get('extra-curriculum', [ExtraCurriculumController::class, 'index'])->name('extra-curriculum.index');
Route::get('extra-curriculum/getExtraCurriculumInfo/{student_id?}', [ExtraCurriculumController::class, 'getExtraCurriculumInfo'])->name('extra-curriculum.getExtraCurriculumInfo');
Route::get('extra-curriculum/create', [ExtraCurriculumController::class, 'create'])->name('extra-curriculum.create');
Route::post('extra-curriculum', [ExtraCurriculumController::class, 'store'])->name('extra-curriculum.store');
Route::get('extra-curriculum/{id}/edit', [ExtraCurriculumController::class, 'edit'])->name('extra-curriculum.edit');
Route::put('extra-curriculum/{id}', [ExtraCurriculumController::class, 'update'])->name('extra-curriculum.update');
Route::delete('extra-curriculum/{id}', [ExtraCurriculumController::class, 'destroy'])->name('extra-curriculum.destroy');
Route::get('eca', [ExtraCurriculumController::class, 'eca_index'])->name('eca.index');
Route::get('get-student-class-section', [ExtraCurriculumController::class, 'getStudentClassSection'])->name('get.student.class.section');
Route::post('extra-curriculum/storeEca', [ExtraCurriculumController::class, 'storeEca'])->name('extra-curriculum.storeEca');
Route::get('extra-curriculum/getEcaInfo', [ExtraCurriculumController::class, 'getEcaInfo'])->name('extra-curriculum.getEcaInfo');
Route::get('extra-curriculum/getEcaInfoForEdit/{id}', [ExtraCurriculumController::class, 'getEcaInfoForEdit'])->name('extra-curriculum.getEcaInfoForEdit');
Route::put('extra-curriculum/updateEca/{id}', [ExtraCurriculumController::class, 'updateEca'])->name('extra-curriculum.updateEca');


Route::get('test_email/{is_direct?}', [LessonPlanController::class, 'test_email'])->name('test_email');

Route::get('callback', function () {
    dd('asdasd');
});

Route::prefix('settings')->name('settings.')->group(function () {
    Route::resource('provident-fund-definitions', ProvidentFundDefinitionController::class);
    Route::resource('income-tax-slabs', IncomeTaxSlabController::class);
    Route::resource('deduction-types', DeductionTypeController::class);
});

// New Settings Forms Routes
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::resource('hm-campus-round', \App\Http\Controllers\Settings\HmCampusRoundController::class);
    Route::resource('calls-details', \App\Http\Controllers\Settings\CallsDetailsController::class);
    Route::resource('repair-maintenance', \App\Http\Controllers\Settings\RepairMaintenanceController::class);
    Route::resource('petty-cash-details', \App\Http\Controllers\Settings\PettyCashDetailsController::class);
    Route::resource('electricity-meter', \App\Http\Controllers\Settings\ElectricityMeterController::class);
    Route::resource('generator-info', \App\Http\Controllers\Settings\GeneratorInfoController::class);
});

// Salary Management Routes (MUST come before resource routes to avoid conflicts)
Route::prefix('payrolls/salary')->name('payrolls.salary.')->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant|human_resource'])->group(function () {
    Route::get('/', [PayrollController::class, 'salaryIndex'])->name('index');
    Route::get('/data', [PayrollController::class, 'salaryData'])->name('data');
    Route::get('/create', [PayrollController::class, 'salaryCreate'])->name('create');
    Route::post('/store', [PayrollController::class, 'salaryStore'])->name('store');
    Route::get('/{id}/edit', [PayrollController::class, 'salaryEdit'])->name('edit');
    Route::put('/{id}/update', [PayrollController::class, 'salaryUpdate'])->name('update');
    Route::get('/{id}/history', [PayrollController::class, 'salaryHistory'])->name('history');
    Route::get('/{id}/details', [PayrollController::class, 'getEmployeeSalary'])->name('details');
    Route::get('/bulk/create', [PayrollController::class, 'bulkSalaryCreate'])->name('bulk.create');
    Route::post('/bulk/store', [PayrollController::class, 'bulkSalaryStore'])->name('bulk.store');
    Route::get('/tax-slabs', [PayrollController::class, 'getTaxSlabs'])->name('tax-slabs');
    Route::post('/employee-salary-data', [PayrollController::class, 'getEmployeeSalaryData'])->name('employee-salary-data');
});

// Main Payroll Routes (resource routes come last to avoid conflicts)
Route::resource('payrolls', PayrollController::class)->middleware(['auth', 'role:super_admin|network_associate|finance-manager|accountant|human_resource']);
Route::post('payrolls/summary', [PayrollController::class, 'fetchPayrollSummary'])->name('payrolls.summary');
Route::post('payrolls/available-employees', [PayrollController::class, 'getAvailableEmployees'])->name('payrolls.available-employees');

require __DIR__ . '/auth.php';

Route::prefix('fixed-assets')->name('fixed-assets.')->middleware(['auth', 'role:super_admin|human_resource'])->group(function () {
    // Purchase Request Routes
    Route::get('purchase-requests', [PurchaseRequestController::class, 'index'])->name('purchase-requests.index');
    Route::get('purchase-requests/create', [PurchaseRequestController::class, 'create'])->name('purchase-requests.create');
    Route::post('purchase-requests', [PurchaseRequestController::class, 'store'])->name('purchase-requests.store');
    Route::get('purchase-requests/{id}', [PurchaseRequestController::class, 'show'])->name('purchase-requests.show');
    Route::get('purchase-requests/{id}/edit', [PurchaseRequestController::class, 'edit'])->name('purchase-requests.edit');
    Route::put('purchase-requests/{id}', [PurchaseRequestController::class, 'update'])->name('purchase-requests.update');
    Route::delete('purchase-requests/{id}', [PurchaseRequestController::class, 'destroy'])->name('purchase-requests.destroy');
    Route::get('purchase-requests/{id}/approve', [PurchaseRequestController::class, 'approve'])->name('purchase-requests.approve');
    Route::get('purchase-requests/{id}/reject', [PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
    Route::get('get-branch-users', [PurchaseRequestController::class, 'getBranchUsers'])->name('get-branch-users');
    
    // Purchase Order Routes
    Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('purchase-orders/{id}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::get('purchase-orders/{id}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
    Route::put('purchase-orders/{id}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
    Route::delete('purchase-orders/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    Route::get('purchase-orders/{id}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
    Route::get('purchase-orders/{id}/reject', [PurchaseOrderController::class, 'reject'])->name('purchase-orders.reject');
    Route::get('purchase-orders/{id}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    Route::get('purchase-orders/{id}/print', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');
    Route::get('get-purchase-request-details', [PurchaseOrderController::class, 'getPurchaseRequestDetails'])->name('get-purchase-request-details');
    
    // Goods Received Note (GRN) Routes
    Route::get('grn', [GoodsReceivedNoteController::class, 'index'])->name('grn.index');
    Route::get('grn/create', [GoodsReceivedNoteController::class, 'create'])->name('grn.create');
    Route::post('grn', [GoodsReceivedNoteController::class, 'store'])->name('grn.store');
    Route::get('grn/{id}', [GoodsReceivedNoteController::class, 'show'])->name('grn.show');
    Route::get('grn/{id}/edit', [GoodsReceivedNoteController::class, 'edit'])->name('grn.edit');
    Route::put('grn/{id}', [GoodsReceivedNoteController::class, 'update'])->name('grn.update');
    Route::delete('grn/{id}', [GoodsReceivedNoteController::class, 'destroy'])->name('grn.destroy');
    Route::get('grn/{id}/verify', [GoodsReceivedNoteController::class, 'verify'])->name('grn.verify');
    Route::post('grn/{id}/reject', [GoodsReceivedNoteController::class, 'reject'])->name('grn.reject');
    Route::get('grn/{id}/print', [GoodsReceivedNoteController::class, 'print'])->name('grn.print');
    Route::get('get-purchase-order-details', [GoodsReceivedNoteController::class, 'getPurchaseOrderDetails'])->name('get-purchase-order-details');
    
    Route::get('categories', [AssetCategoryController::class, 'index'])->name('categories.index');
    
    // Asset Categories Routes
    Route::get('categories/list', [AssetCategoryController::class, 'getCategories'])->name('categories.list');
    Route::get('categories/generate-code', [AssetCategoryController::class, 'generateCode'])->name('categories.generate-code');
    Route::post('categories', [AssetCategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{id}', [AssetCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [AssetCategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Suppliers Routes
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/generate-code', [SupplierController::class, 'generateCode'])->name('suppliers.generate-code');
    Route::get('suppliers/categories', [SupplierController::class, 'getCategories'])->name('suppliers.categories');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    
    // Assets Routes
               Route::get('assets', [AssetController::class, 'index'])->name('assets.index');
           Route::get('assets/create', [AssetController::class, 'create'])->name('assets.create');
           Route::get('assets/{id}/edit', [AssetController::class, 'edit'])->name('assets.edit');
           Route::get('assets/generate-tag', [AssetController::class, 'generateAssetTag'])->name('assets.generate-tag');
           Route::get('assets/users-by-department-branch', [AssetController::class, 'getUsersByDepartmentBranch'])->name('assets.users-by-department-branch');
           Route::get('assets/export', [AssetController::class, 'export'])->name('assets.export');
           Route::post('assets', [AssetController::class, 'store'])->name('assets.store');
    Route::put('assets/{id}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('assets/{id}', [AssetController::class, 'destroy'])->name('assets.destroy');
    
    // Asset Import Routes
    Route::get('assets/import-modal', [AssetController::class, 'openImportModal'])->name('assets.import-modal');
    Route::post('assets/import', [AssetController::class, 'importAssets'])->name('assets.import');
    Route::get('assets/check-import-status', [AssetController::class, 'checkImportStatus'])->name('assets.check-import-status');
    Route::get('assets/template', [AssetController::class, 'downloadTemplate'])->name('assets.template');
    Route::get('assets/view-import-logs', [AssetController::class, 'viewImportLogs'])->name('assets.view-import-logs');
    Route::get('assets/import-log', [AssetController::class, 'downloadImportLog'])->name('assets.import-log');

    // Transfer Request Routes
    Route::get('transfer-requests', [TransferRequestController::class, 'index'])->name('transfer-requests.index');
    Route::get('transfer-requests/create', [TransferRequestController::class, 'create'])->name('transfer-requests.create');
    Route::post('transfer-requests', [TransferRequestController::class, 'store'])->name('transfer-requests.store');
    Route::get('transfer-requests/{id}', [TransferRequestController::class, 'show'])->name('transfer-requests.show');
    Route::get('transfer-requests/{id}/edit', [TransferRequestController::class, 'edit'])->name('transfer-requests.edit');
    Route::put('transfer-requests/{id}', [TransferRequestController::class, 'update'])->name('transfer-requests.update');
    Route::delete('transfer-requests/{id}', [TransferRequestController::class, 'destroy'])->name('transfer-requests.destroy');
    Route::post('transfer-requests/{id}/approve', [TransferRequestController::class, 'approve'])->name('transfer-requests.approve');
    Route::post('transfer-requests/{id}/reject', [TransferRequestController::class, 'reject'])->name('transfer-requests.reject');
    Route::post('transfer-requests/{id}/receive', [TransferRequestController::class, 'receive'])->name('transfer-requests.receive');
    Route::get('get-branch-assets', [TransferRequestController::class, 'getBranchAssets'])->name('get-branch-assets');
    Route::get('get-branch-users', [TransferRequestController::class, 'getBranchUsers'])->name('get-branch-users');
    
    // Stock Report Route
    Route::get('stock-reports', [StockReportController::class, 'index'])->name('stock-reports.index');
});

Route::get('/student-import-log', function () {
    $logPath = storage_path('logs/student_import.log');
    if (!file_exists($logPath)) {
        return response('Log file does not exist.', 404);
    }
    $content = File::get($logPath);
    return response($content, 200, [
        'Content-Type' => 'text/plain',
    ]);
});

// Employment Letter Request Routes
Route::middleware(['auth'])->group(function () {
    // Specific routes must come before resource routes
    Route::get('employment-letter-requests/approval', [EmploymentLetterRequestController::class, 'approval'])
        ->name('employment-letter-requests.approval');
    Route::post('employment-letter-requests/{employmentLetterRequest}/approve', [EmploymentLetterRequestController::class, 'approve'])
        ->name('employment-letter-requests.approve');
    Route::get('employment-letter-requests/{employmentLetterRequest}/download', [EmploymentLetterRequestController::class, 'download'])
        ->name('employment-letter-requests.download');
    
    // Resource routes come last
    Route::resource('employment-letter-requests', EmploymentLetterRequestController::class);
});

// Debug route for permissions - remove after testing
Route::get('debug-permissions', function() {
    $user = Auth::user();
    return [
        'user_id' => $user->id,
        'user_name' => $user->name,
        'roles' => $user->roles->pluck('name'),
        'permissions' => $user->permissions->pluck('name'),
        'all_permissions' => $user->getAllPermissions()->pluck('name'),
        'can_review' => $user->can('review-exit-interviews'),
        'has_hr_role' => $user->hasRole('human_resource'),
        'has_hr_role_alt' => $user->hasRole('hr'),
        'has_admin_role' => $user->hasRole('admin'),
        'has_super_admin_role' => $user->hasRole('super_admin'),
    ];
})->middleware('auth');

// Exit Interview Feedback Routes
Route::middleware(['auth'])->group(function () {
    // Specific routes must come before resource routes
    Route::post('exit-interview-feedbacks/{exitInterviewFeedback}/submit', [App\Http\Controllers\ExitInterviewFeedbackController::class, 'submit'])
        ->name('exit-interview-feedbacks.submit');
    Route::get('exit-interview-feedbacks/review', [App\Http\Controllers\ExitInterviewFeedbackController::class, 'review'])
        ->name('exit-interview-feedbacks.review');
    Route::post('exit-interview-feedbacks/{exitInterviewFeedback}/mark-reviewed', [App\Http\Controllers\ExitInterviewFeedbackController::class, 'markReviewed'])
        ->name('exit-interview-feedbacks.mark-reviewed');
    
    // Resource routes come last
    Route::resource('exit-interview-feedbacks', App\Http\Controllers\ExitInterviewFeedbackController::class);
});

// WebSocket Connection Test Route (Development/Testing)
Route::get('/test-websocket', function () {
    return view('test-websocket');
})->middleware('auth')->name('test.websocket');

// Import Statistics Route
Route::get('/import-stats', [App\Http\Controllers\EmployeeController::class, 'getImportStats'])->middleware('auth')->name('import.stats');

// Export Routes
Route::post('/employees/export', [App\Http\Controllers\EmployeeController::class, 'exportEmployees'])->middleware('auth')->name('employees.export');
Route::get('/export-stats', [App\Http\Controllers\EmployeeController::class, 'getExportStats'])->middleware('auth')->name('export.stats');
Route::get('/export-download', [App\Http\Controllers\EmployeeController::class, 'downloadExport'])->middleware('auth')->name('export.download');

// Broadcasting Authentication Routes
Broadcast::routes(['middleware' => ['auth']]);
