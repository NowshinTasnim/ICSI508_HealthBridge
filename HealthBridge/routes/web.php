<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\PatientController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();



// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/dashboard', [LoginController::class, 'login'])->name('login.submit');
Route::post('/Lab/dashboard', [LoginController::class, 'login'])->name('login.submit');
//Route::post('/patient/dashboard', [LoginController::class, 'login'])->name('login.submit');
Route::post('/insurance/dashboard', [LoginController::class, 'login'])->name('login.submit');

// Register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::get('/logout', [LoginController::class, 'logout'])->name('Plogout');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/insurance/dashboard', [RegisterController::class, 'register_insurance'])->name('insuranceRegister.submit');
Route::post('/lab/dashboard', [RegisterController::class, 'register_lab'])->name('labRegister.submit');
Route::post('/patient/dashboard', [RegisterController::class, 'register_patient'])->name('patientRegister.submit');

Route::get('/api/login-ids', function () {
    // Fetch all the Login_IDs from the credentials table
    return response()->json(DB::table('credentials')->pluck('Login_ID'));
});


// Landing page and login
Route::get('/', [NavigationController::class, 'home'])->name('home');
Route::post('/login', [NavigationController::class, 'login'])->name('login');


// Admin
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/request-claim', [AdminController::class, 'requestClaim'])->name('admin.request-claim');
    Route::get('/approved-claims', [AdminController::class, 'approvedClaims'])->name('admin.approved-claims');
    Route::get('/rejected-claims', [AdminController::class, 'rejectedClaims'])->name('admin.rejected-claims');
    Route::post('/submit-claim', [AdminController::class, 'submitClaim'])->name('admin.submit-claim');
    Route::get('/submitted-claims', [AdminController::class, 'submittedClaims'])->name('admin.submitted-claims');
    Route::post('/view-report', [AdminController::class, 'viewReport'])->name('admin.view-report');
    Route::post('/view-bill', [AdminController::class, 'viewBill'])->name('admin.view-bill');
});

// Lab
Route::prefix('lab')->group(function(){
    Route::get('/dashboard', [LabController::class, 'dashboard'])->name('Lab.dashboard');
    Route::get('/profile', [LabController::class, 'profile'])->name('Lab.profile');
    Route::post('/profile', [LabController::class, 'updateTest'])->name('Lab.updateTest');
    Route::put('/profile', [LabController::class, 'updatePassword'])->name('Lab.updatePassword');
    Route::get('/patient_list', [LabController::class, 'patient_list'])->name('Lab.patient_list');
    Route::post('/patient_list', [LabController::class, 'markAsDone'])->name('Lab.patient_list.markAsDone');
    Route::get('/patient_list/search', [LabController::class, 'searchPatients'])->name('Lab.patient_list.search');
    Route::get('/upload_reports', [LabController::class, 'upload_reports_view'])->name('Lab.upload_reports_view');
    Route::post('/upload_reports', [LabController::class, 'uploadReport'])->name('upload.report');
    Route::get('/upload_bills', [LabController::class, 'upload_bills_view'])->name('Lab.upload_bills_view');
    Route::post('/upload_bills', [LabController::class, 'uploadBill'])->name('upload.bill');
});



// Insurance
Route::prefix('insurance')->group(function () {
    Route::get('/dashboard', [InsuranceController::class, 'dashboard'])->name('Insurance.dashboard');
    Route::get('/claim', [InsuranceController::class, 'claim_list'])->name('Insurance.claim');
    Route::post('/claim', [InsuranceController::class, 'updateApprovalStatus'])->name('Insurance.claim.update');
    Route::post('/claim/download', [InsuranceController::class, 'downloadFile'])->name('Insurance.claim.download');
    Route::get('/profile', [InsuranceController::class, 'profile'])->name('Insurance.profile');
    Route::put('/profile', [InsuranceController::class, 'updatePassword'])->name('Insurance.updatePassword');



});

Route::prefix('patient')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'index'])->name('patient_dashboard');

    Route::get('/labtests', [PatientController::class, 'showTests'])->name('show_lab_tests');

    Route::get('/appointments', [PatientController::class, 'showAppointments'])->name('appointment_list');

    Route::get('/createAppointment/{Available_date}/{Start_time}/{LabID}/{TestID}', [PatientController::class, 'createAppointment'])->name('create_appointment');
});


