<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\EmployeeNew;
use App\Models\StructuralPosition;
use App\Models\Grades;
use App\Http\Controllers\Controller;

class Pegawai extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'nama', 'jenis_kelamin', 'jabatan', 'alamat', 'pendidikan'];
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $title = 'Pegawai';
      $indexed = $this->indexed;
      $var['structural'] = StructuralPosition::all();
      $var['Grades'] = Grades::all();
      return view('admin.pegawai.index', compact('title', 'indexed', 'var'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'nama',
        3 => 'jenis_kelamin',
        4 => 'jabatan',
        5 => 'alamat',
        6 => 'pendidikan',
      ];

      $search = [];

      $totalData = EmployeeNew::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $EmployeeNew = EmployeeNew::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $EmployeeNew = EmployeeNew::where('id', 'LIKE', "%{$search}%")
          ->orWhere('nama', 'LIKE', "%{$search}%")
          ->orWhere('jenis_kelamin', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = EmployeeNew::where('id', 'LIKE', "%{$search}%")
          ->orWhere('nama', 'LIKE', "%{$search}%")
          ->orWhere('jenis_kelamin', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($EmployeeNew)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($EmployeeNew as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['nama'] = $row->nama;
          $nestedData['jenis_kelamin'] = $row->jenis_kelamin;
          $nestedData['jabatan'] = $row->jabatan;
          $nestedData['alamat'] = $row->alamat;
          $nestedData['pendidikan'] = $row->pen->name;
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
    // $employee = EmployeeNew::all();
    // foreach ($employee as $row) {
    //   $u_employee = EmployeeNew::find($row->id);
    //   $u_employee->tanggal_lahir = date('Y-m-d', strtotime($row->tanggal_lahir));
    //   $u_employee->pengangkatan = date('Y-m-d', strtotime($row->pengangkatan));
    //   $u_employee->save();
    // }
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
      $EmployeeNew = EmployeeNew::updateOrCreate(
        ['id' => $id],
        [
          'nama' => $request->nama,
          'tempat_lahir' => $request->tempat_lahir,
          'tanggal_lahir' => $request->tanggal_lahir,
          'jenis_kelamin' => $request->jenis_kelamin,
          'jabatan_new' => $request->jabatan_new,
          'alamat' => $request->alamat,
          'pendidikan' => $request->pendidikan,
          'pengangkatan' => $request->pengangkatan,
          'lembaga_induk' => $request->lembaga_induk,
          'lembaga_diikuti' => $request->lembaga_diikuti,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $EmployeeNew = EmployeeNew::updateOrCreate(
        ['id' => $id],
        [
          'nama' => $request->nama,
          'tempat_lahir' => $request->tempat_lahir,
          'tanggal_lahir' => $request->tanggal_lahir,
          'jenis_kelamin' => $request->jenis_kelamin,
          'jabatan_new' => $request->jabatan_new,
          'alamat' => $request->alamat,
          'pendidikan' => $request->pendidikan,
          'pengangkatan' => $request->pengangkatan,
          'lembaga_induk' => $request->lembaga_induk,
          'lembaga_diikuti' => $request->lembaga_diikuti,
        ]
      );
      if ($EmployeeNew) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create Grades');
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $var['structural'] = StructuralPosition::all();
    $var['Grades'] = Grades::all();
    return view('admin.pegawai.show', compact('title', 'var'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
    $where = ['id' => $id];

    $EmployeeNew = EmployeeNew::where($where)->first();

    return response()->json($EmployeeNew);
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
    $EmployeeNew = EmployeeNew::where('id', $id)->delete();
  }
}
