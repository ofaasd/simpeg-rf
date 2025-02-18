<?php

namespace App\Http\Controllers\ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeNew;
use App\Models\Tahfidz;
use App\Models\Santri;
use App\Models\User;
use App\Models\DetailSantriTahfidz;
use App\Models\KodeJuz
;
use Session;

class TahfidzController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $id_user = Auth::user()->id;
    $user = User::find($id_user);
    $id = $user->pegawai_id;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $tahfidz = Tahfidz::where('employee_id', $id)->first();

    //exit;
    $var['list_santri'] = Santri::where('tahfidz_id', $tahfidz->id)->get();
    return view('ustadz.tahfidz.index', compact('title', 'var','tahfidz'));
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
  public function grafik(Int $id=0){
    $id_user = Auth::user()->id;
    $user = User::find($id_user);
    $id = $user->pegawai_id;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $tahfidz = Tahfidz::where('employee_id', $id)->first();
    $var['list_santri'] = Santri::where('tahfidz_id', $tahfidz->id)->get();
    $var['kode_juz'] = KodeJuz::all();
    $title = "Grafik Santri";
    $id = '';
    return view('ustadz.tahfidz.grafik', compact('title', 'var','tahfidz','id'));
  }
  public function get_grafik(Request $request){
    $no_induk = $request->no_induk;
    $detail = DetailSantriTahfidz::where('no_induk', $no_induk)->get();
    $hasil = [];
    $i = 0;
    foreach ($detail as $row) {
      $hasil[0][$i] = date('d-m-Y', strtotime($row->tanggal));
      $hasil[1][$i] = $row->kode_juz_surah;
      $i++;
    }

    return response()->json($hasil);
  }
}
