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

    $bayar = Pembayaran::whereMonth('tanggal_validasi', $bulan)->sum('jumlah');
    if ($bayar > 0) {
      $jumlah_pembayaran = $bayar;
    }

    $bulan_lalu = $bulan - 1;
    $bayar_lalu = Pembayaran::whereMonth('tanggal_validasi', $bulan)->sum('jumlah');
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
        'jumlah_pembayaran_lalu'
      )
    );
  }
}
