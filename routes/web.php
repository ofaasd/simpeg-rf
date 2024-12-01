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
use App\Http\Controllers\admin\PsbSlideController;
use App\Http\Controllers\ustadz\MurrobyController;
use App\Http\Controllers\ustadz\UangSakuController;
use App\Http\Controllers\ustadz\TahfidzController as UstTahfidzController;
use App\Http\Controllers\ustadz\DetailTahfidzController;
use App\Http\Controllers\admin\AdminDetailTahfidzController;
use App\Http\Controllers\ustadz\SakuMasukController;
use App\Http\Controllers\ustadz\SakuKeluarController;
use App\Http\Controllers\ustadz\KesehatanController as UstKesehatanController;
use App\Http\Controllers\admin\LaporanController;
use App\Http\Controllers\admin\AkuntansiController;
use App\Http\Controllers\admin\UangMasukController;
use App\Http\Controllers\admin\UangKeluarController;
use App\Http\Controllers\admin\KesehatanController;
use App\Http\Controllers\admin\AgendaController;
use App\Http\Controllers\admin\PembayaranController;
use App\Http\Controllers\admin\AlumniController;
use App\Http\Controllers\admin\AsetElektronikController;
use App\Http\Controllers\admin\AsetNonElektronikController;
use App\Http\Controllers\admin\master\aset\MasterGedungController;
use App\Http\Controllers\admin\master\aset\MasterJenisBarang;
use App\Http\Controllers\admin\master\aset\MasterJenisBarangController;
use App\Http\Controllers\admin\master\aset\MasterJenisRuangController;
use App\Http\Controllers\admin\master\aset\MasterLantaiController;
use App\Http\Controllers\admin\master\aset\MasterRuangController;
use App\Http\Controllers\admin\RuangController;
use App\Http\Controllers\admin\SaranController;
use App\Http\Controllers\BangunanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\admin\BeritaController;
use App\Http\Controllers\admin\KategoriController;
use App\Http\Controllers\NewMenuController;
use App\Http\Controllers\TanahController;

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

// Route::get('/poto', $controller_path . '\admin\BeritaController@poto')->name('poto');

Route::get('/sinkronisasi', $controller_path . '\admin\BeritaController@sinkronisasi')->name('sinkronisasi');

Route::get('/statistika', $controller_path . '\statistika@index')->name('statistika');
Route::post('/getJumlahPsb', $controller_path . '\statistika@getJumlahPsb');
Route::post('/getTarget', $controller_path . '\statistika@getTarget');
Route::post('/getPembayaran', $controller_path . '\statistika@getStatistikPembayaran');
Route::get('/get_kota_santri', $controller_path . '\statistika@get_kota_santri');
Route::get('/get_isi_kamar_santri', $controller_path . '\statistika@get_isi_kamar_santri');
Route::get('/pembayaranBulanIni', $controller_path . '\statistika@getPembayaranBulanINi');

Route::get('/dashboard', $controller_path . '\admin\HomePage@index')->name('pages-home');
// pages
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name(
  'auth-register-basic'
);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
  //kalo ada waktu route dirapikan pakai controller path
  $controller_path = 'App\Http\Controllers';
  Route::get('/dashboard', $controller_path . '\admin\HomePage@index')->name('pages-home');
  Route::get('/', $controller_path . '\admin\HomePage@index')->name('pages-home');
  Route::post('/get_jumlah_psb', $controller_path . '\admin\HomePage@get_jumlah_psb');
  Route::post('/get_target', $controller_path . '\admin\HomePage@get_target');

  Route::post('psb/validation', [psb::class, 'validation']);
  Route::post('psb/update_data_pribadi', [psb::class, 'update_data_pribadi']);
  Route::post('psb/update_data_walsan', [psb::class, 'update_data_walsan']);
  Route::post('psb/update_data_asal_sekolah', [psb::class, 'update_data_asal_sekolah']);
  Route::post('psb/update_data_berkas', [psb::class, 'update_data_berkas']);
  Route::post('psb/get_kota', [psb::class, 'get_kota']);
  Route::post('psb/get_kecamatan', [psb::class, 'get_kecamatan']);
  Route::post('psb/get_kelurahan', [psb::class, 'get_kelurahan']);
  Route::get('psb/berkas_pendukung/{id}', [psb::class, 'berkas_pendukung']);
  Route::get('psb/generate_password', [psb::class, 'generate_password']);

  Route::get('psb_new/validasi', [psb::class, 'validasi']);
  Route::get('psb_new/validasi_filter/{id}', [psb::class, 'validasi']);
  Route::get('psb_new/validasi/{id}/edit', [psb::class, 'edit_validasi']);
  Route::post('psb_new/validasi', [psb::class, 'store_validasi']);
  Route::get('psb_new/ujian', [psb::class, 'ujian']);
  Route::post('psb_new/ujian', [psb::class, 'kirim_wa']);
  Route::get('psb_new/ujian/{id}/edit_ujian', [psb::class, 'edit_ujian']);
  Route::post('psb_new/simpan_template_pesan', [psb::class, 'simpan_template_pesan']);
  Route::get('psb_new/kirim_file_pengumuman/{id}', [psb::class, 'kirim_file_pengumuman']);
  Route::get('psb_new/kirim_file_warning/{id}', [psb::class, 'kirim_file_warning']);
  Route::get('psb_new/export', [psb::class, 'exportData']);

  Route::post('kesehatan/reload', [KesehatanController::class, 'reload']);
  Route::get('kesehatan/santri', [KesehatanController::class, 'santri']);
  Route::get('kesehatan/santri/{id}', [KesehatanController::class, 'get_santri']);

  Route::post('agenda/reload', [AgendaController::class, 'reload']);
  Route::post('kategori-berita/reload', [KategoriController::class, 'reload']);
  Route::get('post-berita/reload', [BeritaController::class, 'reload']);

  Route::post('ustadz/kesehatan/reload', [UstKesehatanController::class, 'reload']);
  Route::get('ustadz/kesehatan/santri', [UstKesehatanController::class, 'santri']);
  Route::get('ustadz/kesehatan/santri/{id}', [UstKesehatanController::class, 'get_santri']);

  Route::get(
    '/structural-position/get-school/{id}',
    $controller_path . '\admin\StrucutralPositionController@getSchool'
  );
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
  Route::get('/santri/generate_kelas', [SantriController::class, 'generate_kelas']);

  Route::post('/santri/pemeriksaan', [SantriController::class, 'pemeriksaan']);
  Route::get('/santri/pemeriksaan/{id}', [SantriController::class, 'edit_pemeriksaan']);
  Route::get('/santri/reload_pemeriksaan/{id}', [SantriController::class, 'reload_pemeriksaan']);
  Route::delete('/santri/delete_pemeriksaan/{id}', [SantriController::class, 'delete_pemeriksaan']);

  Route::post('/ustadz/uang-saku/get_all', [UangSakuController::class, 'get_all']);
  Route::get('/ketahfidzan', [TahfidzController::class, 'ketahfidzan']);
  Route::get('/ketahfidzan/{id}', [TahfidzController::class, 'tahfidz_detail']);
  Route::delete('/pegawai/hapus_murroby_santri/{id}', [Pegawai::class, 'hapus_murroby_santri']);
  Route::get('/generate_emp_tahfidz', [TahfidzController::class, 'generate_emp_tahfidz']);
  Route::post('/ketahfidzan', [TahfidzController::class, 'ketahfidzan']);
  Route::get('/ketahfidzan/grafik/{id}', [TahfidzController::class, 'grafik']);
  Route::get('/ketahfidzan/detail_grafik/{id}', [TahfidzController::class, 'grafik_detail']);
  Route::get('ustadz/detail_ketahfidzan', [AdminDetailTahfidzController::class, 'index']);
  Route::post('ustadz/detail_ketahfidzan', [AdminDetailTahfidzController::class, 'store']);
  Route::post('ustadz/saku_masuk/update_bulan', [SakuMasukController::class, 'update_bulan']);
  Route::get('ustadz/hapus_saku_masuk/{id}', [UangSakuController::class, 'destroy']);
  Route::get('laporan/pembayaran', [LaporanController::class, 'pembayaran']);
  Route::post('laporan/pembayaran', [LaporanController::class, 'pembayaran']);
  Route::get('/tbd', function () {
    return view('content.pages.tbd');
  });

  Route::get('/admin/akuntansi', [AkuntansiController::class, 'index']);
  Route::post('/admin/uang_masuk/store', [UangMasukController::class, 'store']);
  Route::post('/admin/uang_keluar/store', [UangKeluarController::class, 'store']);
  Route::post('/admin/akuntansi/get_all', [AkuntansiController::class, 'get_all']);
  Route::post('/admin/uang_masuk/get_id', [UangMasukController::class, 'get_id']);
  Route::post('/admin/uang_keluar/get_id', [UangKeluarController::class, 'get_id']);
  Route::post('/admin/uang_masuk/hapus', [UangMasukController::class, 'hapus']);
  Route::post('/admin/uang_keluar/hapus', [UangKeluarController::class, 'hapus']);

  Route::post('/pembayaran/index', [PembayaranController::class, 'index']);
  Route::get('/pembayaran/export', [PembayaranController::class, 'export']);
  Route::post('/pembayaran/review', [PembayaranController::class, 'review']);
  Route::post('/pembayaran/detail_bayar', [PembayaranController::class, 'detail_bayar']);
  Route::post('/pembayaran/update_status', [PembayaranController::class, 'update_status']);
  Route::post('/pembayaran/get_pesan_warning', [PembayaranController::class, 'get_pesan_warning']);
  Route::post('/pembayaran/send_warning', [PembayaranController::class, 'send_warning']);

  Route::get('/gelombang_detail/{id}', [$controller_path . '\admin\GelombangDetailController', 'index']);

  Route::get('/psb_filter/{id}',  [psb::class, 'index']);

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
  Route::resource('/kesehatan', KesehatanController::class);
  Route::resource('/pembayaran', PembayaranController::class);
  Route::resource('/agenda', AgendaController::class);
  Route::resource('/ustadz/kesehatan', UstKesehatanController::class);
  Route::resource('/kategori-berita', KategoriController::class);
  Route::resource('/post-berita', BeritaController::class);
  Route::resource('/master/aset/gedung', MasterGedungController::class);
  Route::resource('/master/aset/lantai', MasterLantaiController::class);
  Route::resource('/master/aset/ruang', MasterRuangController::class);
  Route::resource('/master/aset/jenis-ruang', MasterJenisRuangController::class);
  Route::resource('/master/aset/jenis-barang', MasterJenisBarangController::class);

  Route::resource('/aset/ruang', RuangController::class);
  Route::resource('/aset/barang', BarangController::class);
  Route::resource('/aset/tanah', TanahController::class);
  Route::resource('/aset/bangunan', BangunanController::class);
  Route::resource('/aset/elektronik', AsetElektronikController::class);
  // Route::resource('/aset/non-elektronik', AsetNonElektronikController::class);

  Route::resource('/psb', psb::class);
  Route::resource('/psb_slide', PsbSlideController::class);
  Route::resource('/gelombang', $controller_path . '\admin\GelombangController');
  Route::resource('/gelombang_detail', $controller_path . '\admin\GelombangDetailController');

  Route::resource('/alumni', $controller_path . '\admin\AlumniController');
  Route::resource('/media_saran', $controller_path . '\admin\SaranController');

  Route::resource('/ustadz/murroby', MurrobyController::class);
  Route::resource('/ustadz/tahfidz', UstTahfidzController::class);
  Route::resource('/ustadz/uang-saku', UangSakuController::class);
  Route::resource('/ustadz/detail_tahfidz', DetailTahfidzController::class);
  Route::resource('/ustadz/detail_tahfidz', DetailTahfidzController::class);
  Route::resource('/detail_ketahfidzan', AdminDetailTahfidzController::class);
  Route::resource('/ustadz/saku_masuk', SakuMasukController::class);
  Route::resource('/ustadz/saku_keluar', SakuKeluarController::class);

  Route::resource('/new-menu', NewMenuController::class);
});
