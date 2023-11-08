<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\EmployeeNew;
use App\Models\StructuralPosition;
use App\Models\Grades;
use App\Models\Golrus;
use App\Models\EmpGolrus;
use App\Models\Santri;
use App\Models\Kamar;
use App\Models\Tahfidz;
use App\Models\SantriKamar;
use App\Models\TahunAjaran;
use App\Http\Controllers\Controller;
use Image;

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
          $nestedData['jabatan'] = $row->jab->name ?? '';
          $nestedData['alamat'] = $row->alamat;
          $nestedData['pendidikan'] = $row->pen->name ?? '';
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
      //return response()->json(dd($request->all()));
      //var_dump($_FILES['photo']['file_name']);
      if ($request->file('photos')) {
        $photo = $request->file('photos');
        $filename = date('YmdHi') . $photo->getClientOriginalName();
        $kompres = Image::make($photo)
          ->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
          })
          ->save('assets/img/upload/photo/' . $filename);
        if ($kompres) {
          //$file = $request->file->store('public/assets/img/upload/photo');
          $EmployeeNew2 = EmployeeNew::updateOrCreate(
            ['id' => $id],
            [
              'photo' => $filename,
            ]
          );
        }
      }
      // user updated
      $where = ['id' => $id];
      $EmployeeNew = EmployeeNew::where($where)->first();
      //$EmployeeNew = EmployeeNew::
      return response()->json($EmployeeNew);
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

  //store golru
  public function store_golru(Request $request)
  {
    //
    $employee_id = $request->employee_id;
    $group = $request->group_a;
    foreach ($group as $row) {
      if ($row['id'] == 0) {
        $golru = new EmpGolrus();
        $golru->employee_id = $employee_id;
        $golru->golru_id = $row['golru'];
        $golru->date_start = $row['tmt'];
        $golru->date_end = $row['sampai'];
        $golru->keterangan = $row['keterangan'];
        $golru->save();
      } else {
        $golru = EmpGolrus::find($row['id']);
        $golru->employee_id = $employee_id;
        $golru->golru_id = $row['golru'];
        $golru->date_start = $row['tmt'];
        $golru->date_end = $row['sampai'];
        $golru->keterangan = $row['keterangan'];
        $golru->save();
      }
    }
    $emp_golrus = EmpGolrus::where('employee_id', $employee_id)->get();
    return response()->json($emp_golrus);
  }

  public function del_golru(Request $request)
  {
    $golru = EmpGolrus::where('id', $request->id);
    $golru->delete();
    return response()->json($request->id);
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
  public function simpan_santri_murroby(Request $request)
  {
    $santri = $request->santri;
    $pegawai = $request->pegawai_id;

    $kamar = Kamar::where('employee_id', $pegawai)->first();
    $ta = TahunAjaran::where('status', 1)->first();
    foreach ($santri as $key => $value) {
      $santri = Santri::where('no_induk', $value);
      $santri->update(['kamar_id' => $kamar->id]);

      $get_santri = $santri->first();

      $santri_kamar = SantriKamar::where('santri_id', $get_santri->id);
      $santri_kamar->update(['status' => 0]);

      $santri_kamar = new SantriKamar();
      $santri_kamar->santri_id = $get_santri->id;
      $santri_kamar->kamar_id = $kamar->id;
      $santri_kamar->tahun_ajaran_id = $ta->id;
      $santri_kamar->status = 1;
      $santri_kamar->save();
    }
    $list_santri = Santri::where('kamar_id', $kamar->id)->get();
    return response()->json($list_santri);
  }
  public function hapus_murroby_santri(string $id)
  {
    //echo $id;
    $santri = Santri::where('no_induk', $id);
    $kamar_id = $santri->first()->kamar_id;

    if ($santri->update(['kamar_id' => 0])) {
      $list_santri = Santri::where('kamar_id', $kamar_id)->get();
      return response()->json($list_santri);
    } else {
      return response()->json('gagal');
    }
  }
}
