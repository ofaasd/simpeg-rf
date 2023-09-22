<?php

namespace App\Http\Controllers\ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\EmployeeNew;
use App\Models\UangSaku;
use App\Models\SakuMasuk;
use App\Models\SakuKeluar;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kamar;
use Illuminate\Support\Facades\DB;

class UangSakuController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //generate santri ke tb_uang_saku di 0 kan jumlah nya
    $id_user = Auth::user()->id;
    $user = User::find($id_user);
    $id = $user->pegawai_id;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $kamar = Kamar::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    $var['uang_saku'] = [];
    foreach ($var['list_santri'] as $row) {
      $var['uang_saku'][$row->no_induk] = UangSaku::where('no_induk', $row->no_induk)->first()->jumlah;
    }

    return view('ustadz.murroby.uang_saku', compact('title', 'var'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
    $jenis = $request->jenis;
    if ($jenis == 'saku_masuk') {
      DB::beginTransaction();
      try {
        $sakuMasuk = SakuMasuk::create([
          'dari' => $request->dari,
          'jumlah' => $request->jumlah,
          'no_induk' => $request->nama_santri,
          'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
        ]);
        $saku = UangSaku::where('no_induk', $request->nama_santri)->first();

        // $updateSaku = UangSaku::update(
        //   ['no_induk' => $request->no_induk],
        //   ['jumlah' => $saku->jumlah + $request->jumlah]
        // );
        $updateSaku = UangSaku::find($saku->id);
        $updateSaku->jumlah = $saku->jumlah + $request->jumlah;
        $updateSaku->save();
        DB::commit();
        return response()->json('Created');
      } catch (\Exception $e) {
        DB::rollback();
        // something went wrong
        return response()->json($e);
      }
    } else {
      if (!empty($request->note)) {
        DB::beginTransaction();
        try {
          $jumlah = 0;
          foreach ($request->note as $key => $value) {
            $sakuKeluar = SakuKeluar::create([
              'pegawai_id' => Auth::user()->id,
              'jumlah' => $request->jumlah[$key],
              'no_induk' => $request->nama_santri,
              'note' => $value,
              'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            ]);
            $jumlah += $request->jumlah[$key];
          }
          $saku = UangSaku::where('no_induk', $request->nama_santri)->first();

          // $updateSaku = UangSaku::update(
          //   ['no_induk' => $request->no_induk],
          //   ['jumlah' => $saku->jumlah + $request->jumlah]
          // );
          $updateSaku = UangSaku::find($saku->id);
          $updateSaku->jumlah = $saku->jumlah - $jumlah;
          $updateSaku->save();
          DB::commit();
          return response()->json('Created');
        } catch (\Exception $e) {
          DB::rollback();
          // something went wrong
          return response()->json($e);
        }
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
  public function get_all(request $request)
  {
    $id = $request->pegawai_id;
    $kamar = Kamar::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    $var['uang_saku'] = [];
    foreach ($var['list_santri'] as $row) {
      $var['uang_saku'][$row->no_induk] = UangSaku::where('no_induk', $row->no_induk)->first()->jumlah;
    }
    return response()->json($var);
  }
}
