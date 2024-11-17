<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tahfidz;
use App\Models\EmployeeNew;
use App\Models\TahunAjaran;
use App\Models\StructuralPosition;
use App\Models\Grades;
use App\Models\Santri;
use App\Models\Loggin_mk1 as log;
use Illuminate\Http\Request;
use Session;
use URL;

class TahfidzController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'code', 'name', 'employee'];
  public $indexed2 = ['', 'id', 'nama', 'jenis_kelamin', 'jabatan', 'alamat', 'pendidikan'];
  public $code = ['a', 'b'];
  public function index(Request $request)
  {
    //
    $ta = TahunAjaran::where('is_aktif', 1);
    if (empty($request->input('length'))) {
      $employee = EmployeeNew::all();
      $title = 'Tahfidz';
      $indexed = $this->indexed;
      $code = $this->code;
      if ($ta->count() == 0 || $ta->count() > 1) {
        return view('admin.no_ta');
      } else {
        $ta = $ta->first();
        return view('admin.tahfidz.index', compact('title', 'indexed', 'code', 'employee', 'ta'));
      }
    } else {
      $columns = [
        1 => 'id',
        1 => 'code',
        2 => 'name',
        3 => 'employee',
      ];

      $search = [];

      $totalData = Tahfidz::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $Tahfidz = Tahfidz::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $Tahfidz = Tahfidz::where(function ($query) use ($search) {
          $query->whereRelation('pegawai', 'nama', 'like', "%{$search}%")->orWhere('name', 'LIKE', "%{$search}%");
        })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = Tahfidz::where(function ($query) use ($search) {
          $query->whereRelation('pegawai', 'nama', 'like', "%{$search}%")->orWhere('name', 'LIKE', "%{$search}%");
        })->count();
      }

      $data = [];

      if (!empty($Tahfidz)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($Tahfidz as $row) {
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
      $Tahfidz = Tahfidz::updateOrCreate(
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
          'jenis' => 3,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $Tahfidz = Tahfidz::updateOrCreate(
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
          'jenis' => 3,
        ]
      );
      if ($Tahfidz) {
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

    $Tahfidz = Tahfidz::where($where)->first();

    return response()->json($Tahfidz);
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
    $Tahfidz = Tahfidz::find($id);
    $log = log::updateOrCreate(
      ['id' => null],
      [
        'name' => $Tahfidz->name,
        'code' => $Tahfidz->code,
        'employee_id' => $Tahfidz->employee_id,
        'tahun_ajaran_id' => $Tahfidz->tahun_ajaran_id,
        'status' => 3,
        'jenis' => 3,
      ]
    );
    $Tahfidz = Tahfidz::where('id', $id)->delete();
  }
  public function ketahfidzan(Request $request)
  {
    if (empty($request->input('length'))) {
      $title = 'Ketahfidzan';
      $indexed = $this->indexed;
      $var['structural'] = StructuralPosition::all();
      $var['Grades'] = Grades::all();
      return view('admin.ketahfidzan.index', compact('title', 'indexed', 'var'));
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

      $totalData = EmployeeNew::where('jabatan_new', 13)->count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $EmployeeNew = EmployeeNew::offset($start)
          ->where('jabatan_new', 13)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $EmployeeNew = EmployeeNew::where('jabatan_new', 13)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('jenis_kelamin', 'LIKE', "%{$search}%");
          })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = EmployeeNew::where('jabatan_new', 13)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('jenis_kelamin', 'LIKE', "%{$search}%");
          })
          ->count();
      }

      $data = [];

      if (!empty($EmployeeNew)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($EmployeeNew as $row) {
          $ref_tahfidz = Tahfidz::where('employee_id',$row->id)->first();
          $jumlah_santri = 0;
          if($ref_tahfidz){
            $jumlah_santri = Santri::where('tahfidz_id',$ref_tahfidz->id)->count() ?? 0;
          }
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['nama'] = $row->nama;
          $nestedData['jenis_kelamin'] = $row->jenis_kelamin;
          $nestedData['jabatan'] = $row->jab->name ?? '';
          $nestedData['alamat'] = $row->alamat;
          $nestedData['pendidikan'] = $row->pen->name ?? '';
          $nestedData['jumlah_santri'] = $jumlah_santri;
          $nestedData['photo'] = $row->photo ?? 0;
          $nestedData['url_photo'] = URL::to('assets/img/upload/photo');

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
  public function tahfidz_detail(string $id)
  {
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $tahfidz = Tahfidz::where('employee_id', $id)->first();
    if($tahfidz){
      Session::put('tahfidz_id', $tahfidz->id);
      $var['list_santri'] = Santri::where('tahfidz_id', $tahfidz->id)->get();
    }else{
      Session::put('tahfidz_id', 0);
    }
    Session::put('employee_id', $id);


    return view('ustadz.tahfidz.index', compact('title', 'var', 'id'));
  }
  public function generate_emp_tahfidz()
  {
    $EmployeeNew = EmployeeNew::where('jabatan_new', 13)->get();
    $ta = TahunAjaran::where('is_aktif', 1)->first();
    foreach ($EmployeeNew as $row) {
      $cek_employee = Tahfidz::where('employee_id', $row->id);
      if ($cek_employee->count() == 0) {
        $tahfidz = new Tahfidz();
        $tahfidz->code = '1a';
        $tahfidz->name = 'Kelompok Tahfidz';
        $tahfidz->employee_id = $row->id;
        $tahfidz->tahun_ajaran_id = $ta->id;
        $tahfidz->save();
      } else {
        echo 'Ada';
      }
      echo '<br />';
    }
  }
  public function grafik(String $id){
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $tahfidz = Tahfidz::where('employee_id', $id)->first();
    if($tahfidz){
      Session::put('tahfidz_id', $tahfidz->id);
      $var['list_santri'] = Santri::where('tahfidz_id', $tahfidz->id)->get();
    }else{
      Session::put('tahfidz_id', 0);
    }
    Session::put('employee_id', $id);


    return view('ustadz.tahfidz.grafik', compact('title', 'var', 'id'));

  }
}
