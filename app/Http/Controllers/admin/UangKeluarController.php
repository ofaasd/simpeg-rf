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
    $id = $request->id_uang_keluar;
    if ($id) {
      // update the value
      $uang_keluar = TbUangKeluar::updateOrCreate(
        ['id' => $id],
        [
          'bulan' => date('m', strtotime($request->tanggal)),
          'tahun' => date('Y', strtotime($request->tanggal)),
          'tanggal_transaksi' => strtotime($request->tanggal),
          'nama_kegiatan' => $request->nama_kegiatan,
          'keterangan' => $request->keterangan,
          'jumlah' => $request->jumlah,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $uang_keluar = TbUangKeluar::updateOrCreate(
        ['id' => $id],
        [
          'bulan' => date('m', strtotime($request->tanggal)),
          'tahun' => date('Y', strtotime($request->tanggal)),
          'tanggal_transaksi' => strtotime($request->tanggal),
          'nama_kegiatan' => $request->nama_kegiatan,
          'keterangan' => $request->keterangan,
          'jumlah' => $request->jumlah,
        ]
      );
      if ($uang_keluar) {
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
    $uang_keluar = TbUangKeluar::find($id);
    $tanggal = date('Y-m-d', $uang_keluar->tanggal_transaksi);
    $array = [
      'status' => 'Success',
      'code' => 1,
      'data' => $uang_keluar,
      'tanggal' => $tanggal,
    ];
    return json_encode($array);
  }
  public function hapus(Request $request)
  {
    $id = $request->id;
    $uang_keluar = TbUangKeluar::where('id', $id)->delete();
  }
}
