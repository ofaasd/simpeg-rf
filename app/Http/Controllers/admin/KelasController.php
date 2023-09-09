<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\EmployeeNew;
use App\Models\TahunAjaran;
use App\Models\Loggin_mk1 as log;
use Illuminate\Http\Request;

class KelasController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'code', 'name', 'employee'];
  public $code = ['a', 'b'];
  public function index(Request $request)
  {
    //
    $ta = TahunAjaran::where('is_aktif', 1);
    if (empty($request->input('length'))) {
      $employee = EmployeeNew::all();
      $title = 'Kelas';
      $indexed = $this->indexed;
      $code = $this->code;
      if ($ta->count() == 0 || $ta->count() > 1) {
        return view('admin.no_ta');
      } else {
        $ta = $ta->first();
        return view('admin.kelas.index', compact('title', 'indexed', 'code', 'employee', 'ta'));
      }
    } else {
      $columns = [
        1 => 'id',
        1 => 'code',
        2 => 'name',
        3 => 'employee',
      ];

      $search = [];

      $totalData = Kelas::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $Kelas = Kelas::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $Kelas = Kelas::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->where('')
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = Kelas::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($Kelas)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($Kelas as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['code'] = $row->code;
          $nestedData['name'] = $row->name;
          $nestedData['employee'] = $row->pegawai->nama;
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
    $id = $request->id;

    if ($id) {
      // update the value
      $Kelas = Kelas::updateOrCreate(
        ['id' => $id],
        [
          'name' => $request->name,
          'code' => $request->code,
          'employee_id' => $request->employee_id,
          'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]
      );
      $id = null;
      $log = log::updateOrCreate(
        ['id' => $id],
        [
          'name' => $request->name,
          'code' => $request->code,
          'employee_id' => $request->employee_id,
          'tahun_ajaran_id' => $request->tahun_ajaran_id,
          'status' => 2,
          'jenis' => 2,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $Kelas = Kelas::updateOrCreate(
        ['id' => $id],
        [
          'name' => $request->name,
          'code' => $request->code,
          'employee_id' => $request->employee_id,
          'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]
      );
      $id = 0;
      $log = log::updateOrCreate(
        ['id' => $id],
        [
          'name' => $request->name,
          'code' => $request->code,
          'employee_id' => $request->employee_id,
          'tahun_ajaran_id' => $request->tahun_ajaran_id,
          'status' => 1,
          'jenis' => 2,
        ]
      );
      if ($Kelas) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create Academic');
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
    $where = ['id' => $id];

    $Kelas = Kelas::where($where)->first();

    return response()->json($Kelas);
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
    $Kelas = Kelas::find($id);
    $log = log::updateOrCreate(
      ['id' => null],
      [
        'name' => $Kelas->name,
        'code' => $Kelas->code,
        'employee_id' => $Kelas->employee_id,
        'tahun_ajaran_id' => $Kelas->tahun_ajaran_id,
        'status' => 3,
        'jenis' => 2,
      ]
    );
    $Kelas = Kelas::where('id', $id)->delete();
  }
}
