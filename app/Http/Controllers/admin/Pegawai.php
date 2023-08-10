<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Controllers\Controller;

class Pegawai extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $employee = Employee::all();

      return view('admin.pegawai.index', [
        'totalUser' => 0,
        'verified' => 0,
        'notVerified' => 0,
        'userDuplicates' => 0,
        'employee' => $employee,
      ]);
    } else {
      $columns = [
        1 => 'id',
        2 => 'name',
        3 => 'nip',
        4 => 'nik',
      ];

      $search = [];

      $totalData = Employee::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $employee = Employee::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $employee = Employee::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->orWhere('nik', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = Employee::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->orWhere('nik', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($employee)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($employee as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['name'] = $row->name;
          $nestedData['nip'] = $row->nip;
          $nestedData['nik'] = $row->nik;

          $data[] = $nestedData;
        }
      }

      if ($data) {
        return response()->json([
          'draw' => intval($request->input('draw')),
          'recordsTotal' => intval($totalData),
          'recordsFiltered' => intval($totalFiltered),
          'code' => 200,
          'data' => $data,
        ]);
      } else {
        return response()->json([
          'message' => 'Internal Server Error',
          'code' => 500,
          'data' => [],
        ]);
      }
    }
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
