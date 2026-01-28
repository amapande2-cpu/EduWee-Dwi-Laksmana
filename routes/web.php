<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\MaterialProgressController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\Teacher\TeacherAuthController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherMaterialController;
use App\Http\Controllers\Teacher\TeacherProfileController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('welcome');

/*
|--------------------------------------------------------------------------
| PUBLIC MATERIALS (NO AUTH)
|--------------------------------------------------------------------------
| Anyone can view published materials
| NO progress tracking here
*/
Route::get('/materials', [MaterialController::class, 'publicIndex'])
    ->name('materials.public');

Route::get('/materials/{material}', [MaterialController::class, 'showPublic'])
    ->name('materials.public.show');

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Guest (Not Logged In)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');

        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated Student
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:web')->group(function () {

        /*
        | Profile
        */
        Route::get('/profile', [StudentProfileController::class, 'show'])
            ->name('profile.show');

        Route::get('/profile/edit', [StudentProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [StudentProfileController::class, 'update'])
            ->name('profile.update');

        /*
        | Dashboard
        */
        Route::get('/home', [MaterialController::class, 'index'])
            ->name('home');

        /*
        | Classes
        */
        Route::get('/classes', [StudentClassController::class, 'index'])
            ->name('classes.index');

        // ✅ FIXED: Route Model Binding
        Route::get('/classes/{class}', [StudentClassController::class, 'show'])
            ->name('classes.show');

        /*
        | Join Class
        */
        Route::post('/classes/join', [MaterialController::class, 'joinClass'])
            ->name('classes.join');

        /*
        | Student Material View (ENROLLED ONLY)
        */
        Route::get('/materials/{material}', [MaterialController::class, 'show'])
            ->name('materials.show');

        /*
        | Progress Tracking (STUDENT ONLY)
        */
        Route::post(
            '/materials/steps/{step}/toggle',
            [MaterialProgressController::class, 'toggleStepProgress']
        )->name('materials.steps.toggle');

        /*
        | Logout
        */
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Guest Teacher
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest:teacher')->group(function () {
        Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [TeacherAuthController::class, 'login'])->name('login.post');

        Route::get('/register', [TeacherAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [TeacherAuthController::class, 'register'])->name('register.post');
    });

    /*
    |--------------------------------------------------------------------------
    | Authenticated Teacher
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:teacher')->group(function () {

        /*
        | Profile
        */
        Route::get('/profile', [TeacherProfileController::class, 'show'])
            ->name('profile.show');

        Route::get('/profile/edit', [TeacherProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [TeacherProfileController::class, 'update'])
            ->name('profile.update');

        /*
        | Dashboard
        */
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])
            ->name('dashboard');

        /*
         | Classes
         */
        Route::get('/classes', [TeacherDashboardController::class, 'classesIndex'])
            ->name('classes.index');

        Route::post('/classes', [TeacherDashboardController::class, 'createClass'])
            ->name('classes.store');

        Route::get('/classes/{class}', [TeacherDashboardController::class, 'showClass'])
            ->name('classes.show');

        Route::get('/classes/{class}/edit', [TeacherDashboardController::class, 'editClass'])
            ->name('classes.edit');

        Route::put('/classes/{class}', [TeacherDashboardController::class, 'updateClass'])
            ->name('classes.update');

        Route::delete('/classes/{class}', [TeacherDashboardController::class, 'destroyClass'])
            ->name('classes.destroy');

        Route::delete(
            '/classes/{class}/students/{student}',
            [TeacherDashboardController::class, 'removeStudent']
        )->name('classes.students.remove');

        /*
        | Materials (Teacher)
        */
        Route::get(
            '/classes/{class}/materials/create',
            [TeacherMaterialController::class, 'create']
        )->name('materials.create');

        Route::post(
            '/classes/{class}/materials',
            [TeacherMaterialController::class, 'store']
        )->name('materials.store');

        Route::get(
            '/classes/{class}/materials/{material}/edit',
            [TeacherMaterialController::class, 'edit']
        )->name('materials.edit');

        Route::put(
            '/classes/{class}/materials/{material}',
            [TeacherMaterialController::class, 'update']
        )->name('materials.update');

        Route::delete(
            '/classes/{class}/materials/{material}',
            [TeacherMaterialController::class, 'destroy']
        )->name('materials.destroy');

        Route::get(
            '/classes/{class}/materials/{material}/progress',
            [TeacherMaterialController::class, 'showStudentProgress']
        )->name('materials.progress');

        Route::get(
            '/classes/{class}/materials/{material}/students/{student}/progress',
            [TeacherMaterialController::class, 'showStudentDetailProgress']
        )->name('materials.students.progress');

        /*
        | Logout
        */
        Route::post('/logout', [TeacherAuthController::class, 'logout'])
            ->name('logout');
    });
});
