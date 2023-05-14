<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\admin\ProgrammeController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\classsupervisor\ClassSupervisorController;
use App\Http\Controllers\dean\DeanController;
use App\Http\Controllers\deanschool\DeanSchoolController;
use App\Http\Controllers\student\StudentController;
use App\Http\Controllers\student\StudentDashboardController;
use App\Http\Middleware\Custom\AdminMiddleware;
use App\Http\Middleware\Custom\AuthMiddleware;
use App\Http\Middleware\Custom\StudentMiddleware;
use App\Http\Middleware\Custom\ClassSupervisorMiddleware;
use App\Http\Middleware\Custom\DeanFacultyMiddleware;
use App\Http\Middleware\Custom\DeanSchoolMiddleware;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('home');

        Route::get('/admin/programmes', [ProgrammeController::class, 'showProgrammes'])->name('admin.programmes');
        Route::post('/admin/programme/add', [ProgrammeController::class, 'storeProgramme'])->name('add-programme');

        Route::get('/admin/departments', [DepartmentController::class, 'showDepartments'])->name('admin.departments');
        Route::post('/admin/department/add', [DepartmentController::class, 'storeDepartment'])->name('add-department');

        Route::get('/admin/deans', [DeanController::class, 'showDeans'])->name('admin.deans');
        Route::post('/admin/deans/add', [DeanController::class, 'storeDean'])->name('add-dean');

        Route::get('/admin/supervisors', [ClassSupervisorController::class, 'showSupervisors'])->name('admin.supervisors');
        Route::post('/admin/supervisors/add', [ClassSupervisorController::class, 'storeSupervisor'])->name('add-supervisor');

        Route::get('/admin/students', [StudentController::class, 'showStudents'])->name('admin.students');
        Route::post('/admin/students/add', [StudentController::class, 'storeStudent'])->name('add-student');
    });

    Route::middleware([StudentMiddleware::class])->group(function () {
        Route::get('student/dashboard', [StudentController::class, 'dashboard'])->name('st-home');
        Route::get('student/permission-request', [StudentController::class, 'requestForm'])->name('request');
        Route::post('student/make-request', [StudentController::class, 'makeRequest'])->name('make-request');
        Route::get('student/permission-progress', [StudentController::class, 'permission_progress'])->name('permission-progress');
    });

    Route::middleware([ClassSupervisorMiddleware::class])->group(function () {
        Route::get('class-supervisor/dashboard', [ClassSupervisorController::class, 'dashboard'])->name('cs-home');
        Route::get('class-supervisor/student-requests', [ClassSupervisorController::class, 'studentRequests'])->name('st-requests');
        Route::get('class-supervisor/student-requests/view-doc/{doc_id}', [ClassSupervisorController::class, 'view_document'])->name('view-doc');

        Route::post('/supervisor/remark', [ClassSupervisorController::class, 'remark_request'])->name('supervisor-remark');
    });

    Route::get('class-supervisor/student-requests/view-doc/{doc_id}', [ClassSupervisorController::class, 'view_document'])->name('view-doc');


    Route::middleware([DeanFacultyMiddleware::class])->group(function () {
        Route::get('/dean_of_faculty/dashboard', [DeanController::class, 'dashboard'])->name('dof-home');
        Route::get('/dean_of_faculty/permission-requests', [DeanController::class, 'permission_requests'])->name('dof-view-requests');
        Route::post('/dean_of_faculty/remark_request', [DeanController::class, 'remark_request'])->name('dof_remark_request');
    });

    Route::middleware([DeanSchoolMiddleware::class])->group(function () {
        Route::get('/dean_of_school/dashboard', [DeanSchoolController::class, 'dashboard'])->name('dos-home');
        Route::get('/dean_of_school/permission-requests', [DeanSchoolController::class, 'permission_requests'])->name('dos-view-requests');
        Route::post('/dean_of_school/remark_request', [DeanSchoolController::class, 'remark_request'])->name('dos_remark_request');
    });

    Route::get('/dashboard', function () {
        return view('NiceAdmin.index');
    })->name('dashboard');
});

// auth routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');

Route::post('/login-user', [AuthController::class, 'login'])->name('login-user');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::any('{query}', function () {
    return view('NiceAdmin.404');
})->where('query', '.*');
