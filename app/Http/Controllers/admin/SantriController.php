<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\City;
use App\Models\Province;

class SantriController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'no_induk', 'nik', 'nama', 'kelas'];
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $title = 'Santri';
      $indexed = $this->indexed;
      $kota = City::all();
      $prov = Province::all();
      return view('admin.santri.index', compact('title', 'indexed', 'kota', 'prov'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'no_induk',
        3 => 'nik',
        4 => 'nama',
        5 => 'kelas',
      ];

      $search = [];

      $totalData = Santri::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $Santri = Santri::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $Santri = Santri::where('id', 'LIKE', "%{$search}%")
          ->orWhere('nama', 'LIKE', "%{$search}%")
          ->orWhere('no_induk', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = Santri::where('id', 'LIKE', "%{$search}%")
          ->orWhere('nama', 'LIKE', "%{$search}%")
          ->orWhere('no_induk', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($Santri)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($Santri as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['no_induk'] = $row->no_induk ?? 0;
          $nestedData['nik'] = $row->nik ?? 0;
          $nestedData['nama'] = $row->nama ?? '';
          $nestedData['kelas'] = $row->kelas ?? 0;
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
      $Santri = Santri::updateOrCreate(
        ['id' => $id],
        [
          'nama' => $request->nama,
          'no_induk' => $request->no_induk,
          'nisn' => $request->nisn,
          'anak_ke' => $request->anak_ke,
          'tempat_lahir' => $request->tempat_lahir,
          'tanggal_lahir' => $request->tanggal_lahir,
          'jenis_kelamin' => $request->jenis_kelamin,
          'alamat' => $request->alamat,
          'provinsi' => $request->provinsi,
          'kabkota' => $request->kabkota,
          'kecamatan' => $request->kecamatan,
          'kelurahan' => $request->kelurahan,
          'kode_pos' => $request->kode_pos,
          'no_hp' => $request->no_hp,
        ]
      );
      //return response()->json(dd($request->all()));
      //var_dump($_FILES['photo']['file_name']);
      if ($request->file('photos')) {
        $photo = $request->file('photos');
        $filename = date('YmdHi') . $photo->getClientOriginalName();
        if ($photo->move(public_path('assets/img/upload/photo'), $filename)) {
          //$file = $request->file->store('public/assets/img/upload/photo');
          $Santri2 = Santri::updateOrCreate(
            ['id' => $id],
            [
              'photo' => $filename,
            ]
          );
        }
      }
      // user updated
      $where = ['id' => $id];
      $Santri = Santri::where($where)->first();
      //$Santri = Santri::
      return response()->json($Santri);
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $Santri = Santri::updateOrCreate(
        ['id' => $id],
        [
          'nama' => $request->nama,
          'no_induk' => $request->no_induk,
          'nisn' => $request->nisn,
          'anak_ke' => $request->anak_ke,
          'tempat_lahir' => $request->tempat_lahir,
          'tanggal_lahir' => $request->tanggal_lahir,
          'jenis_kelamin' => $request->jenis_kelamin,
          'alamat' => $request->alamat,
          'provinsi' => $request->provinsi,
          'kabkota' => $request->kabkota,
          'kecamatan' => $request->kecamatan,
          'kelurahan' => $request->kelurahan,
          'kode_pos' => $request->kode_pos,
          'no_hp' => $request->no_hp,
        ]
      );

      if ($Santri) {
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
    $var['Santri'] = Santri::where($where)->first();
    $title = 'santri';
    $var['structural'] = StructuralPosition::all();
    $var['Grades'] = Grades::all();
    $var['golrus'] = Golrus::all();
    $var['emp_golrus'] = EmpGolrus::where('employee_id', $id);
    return view('admin.santri.show', compact('title', 'var'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
    $where = ['id' => $id];

    $Santri = Santri::where($where)->first();
    $tanggal = date('Y-m-d', strtotime($Santri->tanggal_lahir));
    $Santri->tanggal_fix = $tanggal;
    return response()->json($Santri);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
    $where = ['id' => $id];

    $Santri = Santri::where($where)->first();

    return response()->json($Santri);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
    $Santri = Santri::where('id', $id)->delete();
  }

  public function get_kota(Request $request)
  {
    $id = $request->id;
    $where = ['prov_id' => $id];
    $kota = City::where($where)->get();
    return response()->json($kota);
  }
}
