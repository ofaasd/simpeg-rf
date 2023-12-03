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
use App\Http\Controllers\admin\GradesController;
use App\Http\Controllers\admin\AbsensiController;
use App\Http\Controllers\admin\KamarController;
use App\Http\Controllers\admin\KelasController;
use App\Http\Controllers\admin\TahfidzController;
use App\Http\Controllers\admin\TahunAjaranController;
use App\Http\Controllers\admin\SantriController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\MurrobyController as AdminMurrobyController;
use App\Http\Controllers\admin\psb;
use App\Http\Controllers\ustadz\MurrobyController;
use App\Http\Controllers\ustadz\UangSakuController;
use App\Http\Controllers\ustadz\TahfidzController as UstTahfidzController;
use App\Http\Controllers\ustadz\DetailTahfidzController;
use App\Http\Controllers\admin\AdminDetailTahfidzController;
use App\Http\Controllers\ustadz\SakuMasukController;
use App\Http\Controllers\ustadz\SakuKeluarController;
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
  $controller_path = 'App\Http\Controllers';
  Route::get('/', $controller_path . '\pages\HomePage@index')->name('pages-home');
  Route::resource('/users', UserController::class);
  Route::resource('/user-list', UserManagement::class);
  Route::resource('/pegawai', Pegawai::class);
  Route::resource('/academic', AcademicController::class);
  Route::resource('/school', SchoolController::class);
  Route::resource('/employee-status', EmployeeStatusController::class);
  Route::resource('/employee-status-detail', EmployeeStatusDetailController::class);
  Route::resource('/structural-position', StrucutralPositionController::class);
  Route::resource('/grades', GradesController::class);
  Route::resource('/absensi', AbsensiController::class);
  Route::resource('/kamar', KamarController::class);
  Route::resource('/kelas', KelasController::class);
  Route::resource('/tahfidz', TahfidzController::class);
  Route::resource('/ta', TahunAjaranController::class);
  Route::resource('/santri', SantriController::class);
  Route::resource('/profile', ProfileController::class);
  Route::resource('/murroby', AdminMurrobyController::class);

  Route::resource('/psb', psb::class);
  Route::post('psb/validation', [psb::class, 'validation']);
  Route::post('psb/update_data_pribadi', [psb::class, 'update_data_pribadi']);
  Route::post('psb/update_data_walsan', [psb::class, 'update_data_walsan']);
  Route::post('psb/update_data_asal_sekolah', [psb::class, 'update_data_asal_sekolah']);
  Route::post('psb/update_data_berkas', [psb::class, 'update_data_berkas']);
  Route::get('psb_new/validasi', [psb::class, 'validasi']);
  Route::get('psb_new/validasi/{id}/edit', [psb::class, 'edit_validasi']);
  Route::post('psb_new/validasi', [psb::class, 'store_validasi']);

  Route::resource('/ustadz/murroby', MurrobyController::class);
  Route::resource('/ustadz/tahfidz', UstTahfidzController::class);
  Route::resource('/ustadz/uang-saku', UangSakuController::class);
  Route::resource('/ustadz/detail_tahfidz', DetailTahfidzController::class);
  Route::resource('/ustadz/detail_tahfidz', DetailTahfidzController::class);
  Route::resource('/detail_ketahfidzan', AdminDetailTahfidzController::class);
  Route::resource('/ustadz/saku_masuk', SakuMasukController::class);
  Route::resource('/ustadz/saku_keluar', SakuKeluarController::class);
  Route::get('/structural-position/get-school/{id}', '\admin\StrucutralPositionController@getSchool');
  Route::get('/murroby/uang-saku/{id}', [AdminMurrobyController::class, 'uang_saku']);
  Route::get('/murroby/uang-saku-detail/{id}/{id_santri}', [AdminMurrobyController::class, 'uang_saku_detail']);
  Route::post('/pegawai/store_golru', [Pegawai::class, 'store_golru']);
  Route::post('/pegawai/del_golru', [Pegawai::class, 'del_golru']);
  Route::post('/pegawai/simpan_santri_murroby', [Pegawai::class, 'simpan_santri_murroby']);
  Route::post('/santri/get_kota', [SantriController::class, 'get_kota']);
  Route::post('/santri/update_keluarga', [SantriController::class, 'update_keluarga']);
  Route::post('/santri/update_kamar', [SantriController::class, 'update_kamar']);
  Route::post('/santri/teman_kamar', [SantriController::class, 'teman_kamar']);
  Route::post('/santri/update_kelas', [SantriController::class, 'update_kelas']);
  Route::post('/santri/teman_kelas', [SantriController::class, 'teman_kelas']);
  Route::post('/santri/update_tahfidz', [SantriController::class, 'update_tahfidz']);
  Route::post('/santri/teman_tahfidz', [SantriController::class, 'teman_tahfidz']);
  Route::post('/ustadz/uang-saku/get_all', [UangSakuController::class, 'get_all']);
  Route::get('/ketahfidzan', [TahfidzController::class, 'ketahfidzan']);
  Route::get('/ketahfidzan/{id}', [TahfidzController::class, 'tahfidz_detail']);
  Route::delete('/pegawai/hapus_murroby_santri/{id}', [Pegawai::class, 'hapus_murroby_santri']);
  Route::get('/generate_emp_tahfidz', [TahfidzController::class, 'generate_emp_tahfidz']);
  Route::post('/ketahfidzan', [TahfidzController::class, 'ketahfidzan']);
  Route::get('ustadz/detail_ketahfidzan', [AdminDetailTahfidzController::class, 'index']);
  Route::post('ustadz/detail_ketahfidzan', [AdminDetailTahfidzController::class, 'store']);
  Route::post('ustadz/saku_masuk/update_bulan', [SakuMasukController::class, 'update_bulan']);
  Route::get('ustadz/hapus_saku_masuk/{id}', [UangSakuController::class, 'destroy']);
  Route::get('/tbd', function () {
    return view('content.pages.tbd');
  });
});
