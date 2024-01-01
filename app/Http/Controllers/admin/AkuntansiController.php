<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TbUangMasuk;
use App\Models\TbUangKeluar;

class AkuntansiController extends Controller
{
  //
  public function index(Request $request)
  {
    $var['list_bulan'] = [
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
    $var['bulan'] = (int) date('m');
    $var['tahun'] = date('Y');
    $title = 'Akuntansi';
    if (!empty($request->bulan) && !empty($request->tahun)) {
      $var['bulan'] = $request->bulan;
      $var['tahun'] = $request->tahun;
    }

    $var['uang_masuk'] = [];
    $var['dari_uang_masuk'] = [];
    $var['uang_keluar'] = [];
    $var['note_uang_keluar'] = [];
    $var['tanggal'] = [];
    $bulan = $var['bulan'];
    $tahun = $var['tahun'];
    for ($d = 1; $d <= 31; $d++) {
      $time = mktime(12, 0, 0, $bulan, $d, $tahun);
      if (date('m', $time) == $bulan) {
        $tanggal = date('Y-m-d', $time);
        $var['tanggal'][] = $tanggal;
        $uang_masuk = TbUangMasuk::where('tanggal_transaksi', strtotime($tanggal));
        if ($uang_masuk->count() > 0) {
          foreach ($uang_masuk->get() as $row) {
            $var['uang_masuk'][$tanggal][$row->id] = $row->jumlah;
            $var['dari_uang_masuk'][$tanggal][$row->id] = $row->sumber;
          }
        }
        $uang_keluar = TbUangKeluar::where('tanggal_transaksi', strtotime($tanggal));
        if ($uang_keluar->count() > 0) {
          foreach ($uang_keluar->get() as $row) {
            $var['uang_keluar'][$tanggal][$row->id] = $row->jumlah;
            $var['note_uang_keluar'][$tanggal][$row->id] = $row->keterangan;
          }
        }
      }
    }
    return view('admin.akuntansi.index', compact('title', 'var'));
  }
  public function get_all(Request $request)
  {
    $var['list_bulan'] = [
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
    $var['bulan'] = (int) date('m');
    $var['tahun'] = date('Y');
    $title = 'Akuntansi';
    if (!empty($request->bulan) && !empty($request->tahun)) {
      $var['bulan'] = $request->bulan;
      $var['tahun'] = $request->tahun;
    }
    $var['uang_masuk'] = [];
    $var['dari_uang_masuk'] = [];
    $var['uang_keluar'] = [];
    $var['note_uang_keluar'] = [];
    $var['tanggal'] = [];
    $bulan = $var['bulan'];
    $tahun = $var['tahun'];
    for ($d = 1; $d <= 31; $d++) {
      $time = mktime(12, 0, 0, $bulan, $d, $tahun);
      if (date('m', $time) == $bulan) {
        $tanggal = date('Y-m-d', $time);
        $var['tanggal'][] = $tanggal;
        $uang_masuk = TbUangMasuk::where('tanggal_transaksi', strtotime($tanggal));
        if ($uang_masuk->count() > 0) {
          foreach ($uang_masuk->get() as $row) {
            $var['uang_masuk'][$tanggal][$row->id] = $row->jumlah;
            $var['dari_uang_masuk'][$tanggal][$row->id] = $row->sumber;
          }
        }
        $uang_keluar = TbUangKeluar::where('tanggal_transaksi', strtotime($tanggal));
        if ($uang_keluar->count() > 0) {
          foreach ($uang_keluar->get() as $row) {
            $var['uang_keluar'][$tanggal][$row->id] = $row->jumlah;
            $var['note_uang_keluar'][$tanggal][$row->id] = $row->keterangan;
          }
        }
      }
    }
    return view('admin.akuntansi.table', compact('title', 'var'));
  }
}
