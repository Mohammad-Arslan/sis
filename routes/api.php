<?php

use App\Http\Controllers\Api\BiometricAttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIControllers\AuthController;
use App\Http\Controllers\APIControllers\FranchiseInquiryController;
use App\Http\Controllers\APIControllers\CommonController;
use App\Http\Controllers\APIControllers\BankController;
use App\Http\Controllers\APIControllers\AdmissionQueryController;
use App\Http\Controllers\Api\GuardianController;
use App\Http\Controllers\Api\StudentAttendanceController;
use App\Http\Controllers\Api\GuardianInfoUpdateController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ParentQuriesController;
use App\Http\Controllers\Api\StudentAssessmentController;
use App\Http\Controllers\Api\StudentInvoiceController;
use App\Http\Controllers\GuardianInfoUpdateController as ControllersGuardianInfoUpdateController;
use App\Http\Controllers\Api\ViewHomeworkDetailApi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api']], function () {

    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::get('/list_cities', [CommonController::class, 'listCities'])->name('api.listCities');
    Route::get('/list_sources', [CommonController::class, 'listSources'])->name('api.listSources');
    Route::get('/list_towns', [CommonController::class, 'listTowns'])->name('api.listTowns');
    Route::get('/list_town_branches', [CommonController::class, 'getTownBranches'])->name('api.getTownBranches');
    Route::get('/list_classes', [CommonController::class, 'getClasses'])->name('api.getClasses');
    Route::get('/list_branch_classes', [CommonController::class, 'getBranchClasses'])->name('api.getBranchClasses');
    Route::get('/royalty_computation_report', [CommonController::class, 'royaltyComputationReport'])->name('api.royaltyComputationReport');

    Route::get('/homework-detail-api', [ViewHomeworkDetailApi::class, 'viewHomeworkDetailApi'])->name('homework.detail.api');

    Route::resource('franchise-inquiry', FranchiseInquiryController::class);

    Route::get('/inquiry', [BankController::class, 'getInquiry'])->name('api.getInquiry');
    Route::get('/update_payment', [BankController::class, 'updatePayment'])->name('api.updatePayment');

    Route::post('/admission-query', [AdmissionQueryController::class, 'store'])->name('api.admission_query.store');

    Route::post('/guardian-login', [GuardianController::class, 'login']);
    //Guardian info change request
    Route::post('/guardian-info-update', [GuardianInfoUpdateController::class, 'guardianInfoChangeRequest']);

    Route::get('/student-assessments/{student_behaviour_skill_id}/{student_id}', [StudentAssessmentController::class, 'studentAssessments']);
    Route::post('/student-assessments-async/{student_behaviour_skill_id}/{student_id}', [StudentAssessmentController::class, 'studentAssessmentsAsync']);
    Route::get('/student-assessments-status/{student_behaviour_skill_id}/{student_id}', [StudentAssessmentController::class, 'checkPdfStatus'])->name('api.student.assessment.status');
    Route::get('/show-invoice/{id}', [StudentInvoiceController::class, 'showInvoice']);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/user', [AuthController::class, 'getUser'])->name('api.getUser');
        Route::post('/store-fcm', [GuardianController::class, 'storeFCM']);
        Route::post('/verify-otp', [GuardianController::class, 'verifyOTP']);
        Route::get('/refresh-info', [GuardianController::class, 'refreshInfo']);
        Route::get('/resend-otp', [GuardianController::class, 'resendOTP']);
        Route::post('/student-attendance', [StudentAttendanceController::class, 'getStudentAttendance']);
        Route::post('/student-challan', [StudentInvoiceController::class, 'studentChallan']);
        Route::post('/student-guardians', [GuardianInfoUpdateController::class, 'getGuardiansInfo']);
        Route::post('/student-terms', [StudentAssessmentController::class, 'studentTerms']);
        Route::post('/parent-query', [ParentQuriesController::class, 'saveQuries']);
        Route::get('/get-notifications', [NotificationController::class, 'getNotification']);
        //Get withdrawal reasons
        Route::get('/withdrawal-reasons', [\App\Http\Controllers\Api\WithdrawalController::class, 'getWithdrawalReasons']);
        //Create withdrawal request
        Route::post('/withdrawal-request', [\App\Http\Controllers\Api\WithdrawalController::class, 'setWithdrawalRequest']);
    });

    Route::post('/biometric-attendance', [BiometricAttendanceController::class, 'store']);
});
