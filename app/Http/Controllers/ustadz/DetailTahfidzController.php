<?php

namespace App\Http\Controllers\ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailTahfidzController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    $bulan = (int) date('m');
    $tahun = date('Y');
    $id_user = Auth::user()->id;
    $user = User::find($id_user);
    $id = $user->pegawai_id;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $tahfidz = Tahfidz::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('tahfidz_id', $tahfidz->id)->get();
    $var['list_detail_tahfidz'] = DetailTahfidz::where('bulan', $bulan)
      ->where('tahun', $tahun)
      ->get();
    return view('ustadz.tahfidz.tahfidz', compact('title', 'var'));
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
}
