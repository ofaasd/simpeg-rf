<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//psb
use App\Models\PsbGelombang;
use App\Models\PsbPesertaOnline;
//siswa
use App\Models\Santri;
//pegawai
use App\Models\EmployeeNew;
//pembayaran
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
//jumlah_belum_bayar
use DB;

class HomePage extends Controller
{
  public function index()
  {
    $jumlah_psb = 0;
    $jumlah_psb_baru = 0;
    $jumlah_siswa = 0;
    $jumlah_pegawai = 0;
    $jumlah_pegawai_l = 0;
    $jumlah_pegawai_p = 0;
    $jumlah_pembayaran = 0;
    $tot_bayar = 0;
    $rincian_pembayaran = [];
    $jumlah_pembayaran_lalu = 0;
    $jumlah_siswa_belum_lapor = 0;
    $jumlah_psb_laki = 0;
    $jumlah_psb_perempuan = 0;
    $jumlah_siswa_l = 0;
    $jumlah_siswa_p = 0;
    $list_bulan = [
      1 => 'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember',
    ];

    $bulan = (int) date('m');
    $tahun = (int) date('Y');
    $gelombang = PsbGelombang::where('pmb_online', 1)->first();
    $psb = PsbPesertaOnline::where('gelombang_id', $gelombang->id);
    $psb2 = PsbPesertaOnline::where('gelombang_id', $gelombang->id)->whereRaw(
      'MONTH(FROM_UNIXTIME(created_at)) = ' . $bulan
    );
    if ($psb->count() > 0) {
      $jumlah_psb = $psb->count();
      $jumlah_psb_laki = $psb->where('jenis_kelamin', 'L')->count();
      $jumlah_psb_perempuan = PsbPesertaOnline::where('gelombang_id', $gelombang->id)
        ->where('jenis_kelamin', 'P')
        ->count();
    }
    if ($psb2->count() > 0) {
      $jumlah_psb_baru = $psb2->count();
    }

    $santri = Santri::where('status', 0);
    if ($santri->count() > 0) {
      $jumlah_siswa = $santri->count();
      $jumlah_siswa_l = $santri->where('jenis_kelamin', 'L')->count();
      $jumlah_siswa_p = Santri::where('status', 0)
        ->where('jenis_kelamin', 'P')
        ->count();
    }

    $pegawai = EmployeeNew::count();
    if ($pegawai > 0) {
      $jumlah_pegawai = $pegawai;
      $jumlah_pegawai_l = EmployeeNew::where('jenis_kelamin', 'Laki-laki')->count();
      $jumlah_pegawai_p = EmployeeNew::where('jenis_kelamin', 'Perempuan')->count();
    }

    $bayar = Pembayaran::whereMonth('tanggal_validasi', $bulan)
      ->whereYear('tanggal_validasi', $tahun)
      ->sum('jumlah');
    if ($bayar > 0) {
      $jumlah_pembayaran = $bayar;
    }

    $bayar = Pembayaran::whereMonth('tanggal_bayar', $bulan)
      ->whereYear('tanggal_bayar', $tahun)
      ->sum('jumlah');
    if ($bayar > 0) {
      $tot_bayar = $bayar;
    }

    $jumlah_santri_lapor = Pembayaran::whereMonth('tanggal_bayar', $bulan)
      ->whereYear('tanggal_bayar', $tahun)
      ->distinct('nama_santri');
    $jumlah_siswa_belum_lapor = $jumlah_siswa - $jumlah_santri_lapor->count();

    if ($bulan == 1) {
      $bulan = 13;
      $tahun = $tahun - 1;
    }
    $bulan_lalu = $bulan - 1;
    $bayar_lalu = Pembayaran::whereMonth('tanggal_validasi', $bulan_lalu)
      ->whereYear('tanggal_validasi', $tahun)
      ->sum('jumlah');
    if ($bayar_lalu > 0) {
      $jumlah_pembayaran_lalu = $bayar_lalu;
    }

    $kelas = Santri::select('kelas')
      ->groupBy('kelas')
      ->orderBy('kelas')
      ->get();

    return view(
      'content.pages.pages-home',
      compact(
        'kelas',
        'list_bulan',
        'jumlah_psb_baru',
        'jumlah_psb_laki',
        'jumlah_psb_perempuan',
        'jumlah_psb',
        'jumlah_siswa',
        'jumlah_siswa_l',
        'jumlah_siswa_p',
        'jumlah_pegawai',
        'jumlah_pegawai_l',
        'jumlah_pegawai_p',
        'jumlah_pembayaran',
        'jumlah_pembayaran_lalu',
        'jumlah_siswa_belum_lapor',
        'tot_bayar'
      )
    );
  }
  public function get_jumlah_psb(Request $request)
  {
    $tahun = $request->tahun;
    $bulan = [
      1 => 'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember',
    ];
    $jumlah = [];
    foreach ($bulan as $key => $value) {
      $psb2 = PsbPesertaOnline::whereRaw('MONTH(FROM_UNIXTIME(created_at)) = ' . $key)->whereRaw(
        'YEAR(FROM_UNIXTIME(created_at)) = ' . $tahun
      );
      $jumlah[$key] = $psb2->count();
    }
    $hasil[0] = array_values($bulan);
    $hasil[1] = array_values($jumlah);
    return response()->json($hasil);
  }
  public function get_target(Request $request)
  {
    $total_santri = 0;
    $sudah_bayar = 0;

    $bulan = $request->bulan;
    $tahun = $request->tahun;
    $kelas = $request->kelas;

    $siswa = Santri::where('kelas', $kelas)->get();
    foreach ($siswa as $row) {
      $total_santri++;
      $pembayaran = Pembayaran::where(['periode' => $bulan, 'tahun' => $tahun, 'nama_santri' => $row->no_induk]);
      if ($pembayaran->count() > 0) {
        foreach ($pembayaran->get() as $pem) {
          $detail = DetailPembayaran::where('id_pembayaran', $pem->id)
            ->where('id_jenis_pembayaran', 1)
            ->count();
          if ($detail > 0) {
            $sudah_bayar++;
          }
        }
      }
    }
    $belum_bayar = $total_santri - $sudah_bayar;
    $hasil[0] = ['Belum Lapor', 'Sudah Lapor'];
    $hasil[1] = [$belum_bayar, $sudah_bayar];
    return response()->json($hasil);
  }
}
