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
    $id = $request->id_uang_masuk;
    if ($id) {
      // update the value
      $uang_masuk = TbUangMasuk::updateOrCreate(
        ['id' => $id],
        [
          'bulan' => date('m', strtotime($request->tanggal)),
          'tahun' => date('Y', strtotime($request->tanggal)),
          'tanggal_transaksi' => strtotime($request->tanggal),
          'nama_kegiatan' => $request->nama_kegiatan,
          'sumber' => $request->sumber,
          'jumlah' => $request->jumlah,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $uang_masuk = TbUangMasuk::updateOrCreate(
        ['id' => $id],
        [
          'bulan' => date('m', strtotime($request->tanggal)),
          'tahun' => date('Y', strtotime($request->tanggal)),
          'tanggal_transaksi' => strtotime($request->tanggal),
          'nama_kegiatan' => $request->nama_kegiatan,
          'sumber' => $request->sumber,
          'jumlah' => $request->jumlah,
        ]
      );
      if ($uang_masuk) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create Academic');
      }
    }
  }
  public function get_id(Request $request)
  {
    $id = $request->id;
    $uang_masuk = TbUangMasuk::find($id);
    $tanggal = date('Y-m-d', $uang_masuk->tanggal_transaksi);
    $array = [
      'status' => 'Success',
      'code' => 1,
      'data' => $uang_masuk,
      'tanggal' => $tanggal,
    ];
    return json_encode($array);
  }
  public function hapus(Request $request)
  {
    $id = $request->id;
    $uang_masuk = TbUangMasuk::where('id', $id)->delete();
    if ($uang_masuk) {
      echo 'Ok';
    } else {
      echo 'Gagal';
    }
  }
}
