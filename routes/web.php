<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\UserManagement;
use App\Http\Controllers\admin\Pegawai;
use App\Http\Controllers\admin\AcademicController;
use App\Http\Controllers\admin\SchoolController;
use App\Http\Controllers\admin\EmployeeStatusController;
use App\Http\Controllers\admin\EmployeeStatusDetailController;
use App\Http\Controllers\admin\StrucutralPositionController;
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

$controller_path = 'App\Http\Controllers';

// Main Page Route
Route::get('/', $controller_path . '\pages\HomePage@index')->name('pages-home');
Route::get('/page-2', $controller_path . '\pages\Page2@index')->name('pages-page-2');

// pages
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name(
  'auth-register-basic'
);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
  Route::get('/dashboard', function () {
    return view('content.pages.pages-home');
  })->name('dashboard');

  Route::resource('/users', UserController::class);
  Route::resource('/user-list', UserManagement::class);
  Route::resource('/pegawai', Pegawai::class);
  Route::resource('/academic', AcademicController::class);
  Route::resource('/school', SchoolController::class);
  Route::resource('/employee-status', EmployeeStatusController::class);
  Route::resource('/employee-status-detail', EmployeeStatusDetailController::class);
  Route::resource('/structural-position', StrucutralPositionController::class);
  Route::get('/structural-position/get-school/{$id}', '\admin\StrucutralPositionController@getSchool');
});
