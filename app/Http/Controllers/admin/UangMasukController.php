<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TbUangMasuk;

class UangMasukController extends Controller
{
  //
  public function store(Request $request)
  {
    $uang_masuk = new TbUangMasuk();
    $uang_masuk->bulan = date('m', strtotime($request->tanggal));
    $uang_masuk->tahun = date('Y', strtotime($request->tanggal));
    $uang_masuk->tanggal_transaksi = strtotime($request->tanggal);
    $uang_masuk->sumber = $request->sumber;
    $uang_masuk->jumlah = $request->jumlah;
    if ($uang_masuk->save()) {
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
