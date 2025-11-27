<?php
// routes/web.php - With Working Middleware

use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Teacher\TeacherAuthController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherMaterialController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ============== STUDENT ROUTES ==============
Route::prefix('student')->name('student.')->group(function () {
    // Guest-only routes (redirect if already logged in)
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });

    // Auth-required routes
    Route::middleware('auth:web')->group(function () {
        Route::get('/home', [MaterialController::class, 'index'])->name('home');
        Route::get('/classes', [\App\Http\Controllers\StudentClassController::class, 'index'])->name('classes');
        Route::get('/classes/{id}', [\App\Http\Controllers\StudentClassController::class, 'show'])->name('class.show');
        Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('materials.show');
        Route::get('/category/{category}', [MaterialController::class, 'category'])->name('materials.category');
        Route::post('/join-class', [MaterialController::class, 'joinClass'])->name('joinClass');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

// ============== TEACHER ROUTES ==============
Route::prefix('teacher')->name('teacher.')->group(function () {
    // Guest-only routes (redirect if already logged in as teacher)
    Route::middleware('guest:teacher')->group(function () {
        Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [TeacherAuthController::class, 'login'])->name('login.post');
        Route::get('/register', [TeacherAuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [TeacherAuthController::class, 'register'])->name('register.post');
    });

    // Auth-required routes (must be logged in as teacher)
    Route::middleware('auth:teacher')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        
        // Class Management
        Route::post('/class/create', [TeacherDashboardController::class, 'createClass'])->name('class.create');
        Route::get('/class/{id}', [TeacherDashboardController::class, 'showClass'])->name('class.show');
        Route::get('/class/{id}/edit', [TeacherDashboardController::class, 'editClass'])->name('class.edit');
        Route::put('/class/{id}', [TeacherDashboardController::class, 'updateClass'])->name('class.update');
        Route::delete('/class/{id}', [TeacherDashboardController::class, 'deleteClass'])->name('class.delete');
        Route::delete('/class/{classId}/student/{studentId}', [TeacherDashboardController::class, 'removeStudent'])->name('class.removeStudent');
        
        // Material Management
        Route::get('/class/{classId}/material/create', [TeacherMaterialController::class, 'create'])->name('material.create');
        Route::post('/class/{classId}/material', [TeacherMaterialController::class, 'store'])->name('material.store');
        Route::get('/class/{classId}/material/{materialId}/edit', [TeacherMaterialController::class, 'edit'])->name('material.edit');
        Route::put('/class/{classId}/material/{materialId}', [TeacherMaterialController::class, 'update'])->name('material.update');
        Route::delete('/class/{classId}/material/{materialId}', [TeacherMaterialController::class, 'destroy'])->name('material.destroy');
        
        Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('logout');
    });
});