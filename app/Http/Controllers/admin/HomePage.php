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
//jumlah_belum_bayar

class HomePage extends Controller
{
  public function index()
  {
    $jumlah_psb = 0;
    $jumlah_psb_baru = 0;
    $jumlah_siswa = 0;
    $jumlah_pegawai = 0;
    $jumlah_pembayaran = 0;
    $jumlah_pembayaran_lalu = 0;
    $jumlah_siswa_belum_lapor = 0;

    $bulan = (int) date('m');
    $tahun = (int) date('Y');
    $gelombang = PsbGelombang::where('pmb_online', 1)->first();
    $psb = PsbPesertaOnline::where('gelombang_id', $gelombang->id);
    $psb2 = PsbPesertaOnline::where('gelombang_id', $gelombang->id)->whereMonth('created_at', $bulan);
    if ($psb->count() > 0) {
      $jumlah_psb = $psb->count();
    }
    if ($psb2->count() > 0) {
      $jumlah_psb_baru = $psb2->count();
    }

    $santri = Santri::count();
    if ($santri > 0) {
      $jumlah_siswa = $santri;
    }

    $pegawai = EmployeeNew::count();
    if ($pegawai > 0) {
      $jumlah_pegawai = $pegawai;
    }

    $bayar = Pembayaran::whereMonth('tanggal_validasi', $bulan)
      ->whereYear('tanggal_validasi', $tahun)
      ->sum('jumlah');
    if ($bayar > 0) {
      $jumlah_pembayaran = $bayar;
    }

    $jumlah_santri_lapor = $bayar_lalu = Pembayaran::whereMonth('tanggal_validasi', $bulabulann_lalu)
      ->whereYear('tanggal_validasi', $tahun)
      ->groupBy('nama_santri')
      ->count();
    $jumlah_siswa_belum_lapor = $jumlah_siswa - $jumlah_santri_lapor;

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

    return view(
      'content.pages.pages-home',
      compact(
        'jumlah_psb_baru',
        'jumlah_psb',
        'jumlah_siswa',
        'jumlah_pegawai',
        'jumlah_pembayaran',
        'jumlah_pembayaran_lalu',
        'jumlah_siswa_belum_lapor'
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
}
