<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeNew;
use App\Models\StructuralPosition;
use App\Models\Grades;
use App\Models\Golrus;
use App\Models\User;
use App\Models\EmpGolrus;
use App\Models\Kamar;
use App\Models\Tahfidz;
use App\Models\Santri;

class ProfileController extends Controller
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
    $var['structural'] = StructuralPosition::all();
    $var['Grades'] = Grades::all();
    $var['golrus'] = Golrus::all();
    $var['murroby'] = 0;
    $var['tahfidz'] = 0;
    if ($var['EmployeeNew']->jabatan_new == 12) {
      //pegawai murroby
      $var['murroby'] = 1;
      $var['kamar'] = Kamar::where('employee_id', $id);
      $var['santri_all'] = [];
      $var['santri'] = [];
      if ($var['kamar']->count() > 0) {
        $var['santri'] = Santri::where('kamar_id', $var['kamar']->first()->id)->get();
        $var['santri_all'] = Santri::all();
      } else {
        $var['murroby'] = 0;
      }
    }
    if ($var['EmployeeNew']->jabatan_new == 13) {
      //pegawai murroby
      $var['tahfidz'] = 1;
      $var['list_tahfidz'] = Tahfidz::where('employee_id', $id);
      $var['santri_all'] = [];
      $var['santri'] = [];
      if ($var['list_tahfidz']->count() > 0) {
        $var['list_tahfidz'] = Santri::where('tahfidz_id', $var['list_tahfidz']->first()->id)->get();
        $var['santri_all'] = Santri::all();
      } else {
        $var['tahfidz'] = 0;
      }
    }

    $var['emp_golrus'] = EmpGolrus::where('employee_id', $id);
    return view('admin.pegawai.show', compact('title', 'var'));
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
  public function show()
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
