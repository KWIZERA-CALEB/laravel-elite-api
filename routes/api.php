<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Base\CourseConroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/edit-pass', [AuthController::class, 'editPassword']);
Route::post('/add-course', [CourseConroller::class, 'addCourse']);
Route::get('/courses', [CourseConroller::class, 'allCourses']);
Route::put('/edit-course/{id}', [CourseConroller::class, 'editCourse']);
Route::get('/course/{id}', [CourseConroller::class, 'singleCourse']);
Route::delete('/delete-course/{id}', [CourseConroller::class, 'deleteCourse']);
Route::get('/search', [CourseConroller::class, 'searchCourse']);