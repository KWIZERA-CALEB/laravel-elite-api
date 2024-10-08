<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Base\CourseConroller;
use App\Http\Controllers\Base\UserController;
use App\Http\Controllers\CertificateController;
use App\Http\Middleware\ExpireOldTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logOut'])->middleware('auth:sanctum');
Route::post('/edit-pass', [AuthController::class, 'editPassword'])->middleware('auth:sanctum');

// Courses Endpoints
Route::post('/add-course', [CourseConroller::class, 'addCourse'])->middleware('auth:sanctum');
Route::get('/courses', [CourseConroller::class, 'allCourses']);
Route::put('/edit-course/{id}', [CourseConroller::class, 'editCourse'])->middleware('auth:sanctum');
Route::get('/course/{id}', [CourseConroller::class, 'singleCourse']);
Route::delete('/delete-course/{id}', [CourseConroller::class, 'deleteCourse'])->middleware('auth:sanctum');
Route::get('/search', [CourseConroller::class, 'searchCourse']);
Route::get('/teacher/courses', [CourseConroller::class, 'allTeacherCourses'])->middleware(['auth:sanctum']);

// Users Endpoints
Route::get('/teachers', [UserController::class, 'teachers']);
Route::get('/user', [UserController::class, 'loginedUser'])->middleware(['auth:sanctum']);
Route::get('/user/{id}', [UserController::class, 'userProfile'])->middleware('auth:sanctum');

// Certificates EndPoints
Route::post('/add-certificate/{id}', [CertificateController::class, 'addCertificate'])->middleware('auth:sanctum');
Route::get('/single-certificate/{id}', [CertificateController::class, 'singleCertificate'])->middleware('auth:sanctum');
Route::get('/student/certificates', [CertificateController::class, 'allUserCertificates'])->middleware('auth:sanctum');
