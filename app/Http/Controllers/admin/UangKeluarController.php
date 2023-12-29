<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TbUangKeluar;

class UangKeluarController extends Controller
{
  //
  public function store(Request $request)
  {
    $uang_keluar = new TbUangKeluar();
    $uang_keluar->bulan = date('m', strtotime($request->tanggal));
    $uang_keluar->tahun = date('Y', strtotime($request->tanggal));
    $uang_keluar->tanggal_transaksi = strtotime($request->tanggal);
    $uang_keluar->keterangan = $request->catatan;
    $uang_keluar->jumlah = $request->jumlah;
    if ($uang_keluar->save()) {
      $array = [
        'status' => 'Success',
        'code' => 1,
      ];
      return json_encode($array);
    } else {
      $array = [
        'status' => 'Failed',
        'code' => 0,
      ];
      return json_encode($array);
    }
  }
}
