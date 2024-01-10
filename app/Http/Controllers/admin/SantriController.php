<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\City;
use App\Models\Kamar;
use App\Models\Kelas;
use App\Models\Tahfidz;
use App\Models\RefSiswa;
use App\Models\SantriKamar;
use App\Models\SantriKelas;
use App\Models\SantriTahfidz;
use App\Models\TahunAjaran;
use App\Models\Province;
use Illuminate\Support\Facades\DB;
use Image;

class SantriController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'no_induk', 'nik', 'nama', 'kelas', 'kamar'];
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $title = 'Santri';
      $indexed = $this->indexed;
      $kota = City::all();
      $prov = Province::all();
      $kamar = Kamar::all();
      $tahfidz = Tahfidz::all();
      $kelas = Kelas::all();
      $status = [0 => 'aktif', 1 => 'lulus/alumni', 2 => 'boyong/keluar', 3 => 'meninggal'];

      return view(
        'admin.santri.index',
        compact('status', 'title', 'indexed', 'kota', 'kelas', 'prov', 'kamar', 'tahfidz')
      );
    } else {
      $columns = [
        1 => 'id',
        2 => 'no_induk',
        3 => 'nik',
        4 => 'nama',
        5 => 'kelas',
        6 => 'kamar',
      ];

      $search = [];

      $totalData = Santri::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $Santri = Santri::where('status', 0)
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
        $totalFiltered = Santri::where('status', 0)->count();
      } else {
        $search = $request->input('search.value');

        $Santri = Santri::where('status', 0)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('no_induk', 'LIKE', "%{$search}%")
              ->orWhere('kelas', 'LIKE', "%{$search}%");
          })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = Santri::where('status', 0)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('no_induk', 'LIKE', "%{$search}%")
              ->orWhere('kelas', 'LIKE', "%{$search}%");
          })
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
          $nestedData['kamar'] = $row->kamar->code ?? 0;
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
          'tanggal_lahir' => date('Y-m-d', strtotime($request->tanggal_fix)),
          'jenis_kelamin' => $request->jenis_kelamin,
          'alamat' => $request->alamat,
          'provinsi' => $request->provinsi,
          'kabkota' => $request->kabkota,
          'kecamatan' => $request->kecamatan,
          'kelurahan' => $request->kelurahan,
          'kode_pos' => $request->kode_pos,
          'nik' => $request->nik ?? '',
          'no_hp' => $request->no_hp,
          'tahfidz_id' => $request->tahfidz_id,
          'kamar_id' => $request->kamar_id,
          'kelas' => $request->kelas,
          'status' => $request->status,
        ]
      );
      //return response()->json(dd($request->all()));
      //var_dump($_FILES['photo']['file_name']);
      if ($request->file('photos')) {
        $photo = $request->file('photos');
        $filename = date('YmdHi') . $photo->getClientOriginalName();
        $kompres = Image::make($photo)
          ->resize(400, 400)
          ->save('assets/img/upload/photo/' . $filename);
        if ($kompres) {
          //$file = $request->file->store('public/assets/img/upload/photo');
          $Santri2 = Santri::find($id);
          $Santri2->photo = $filename;
          $Santri2->photo_location = 2;
          $Santri2->save();
        }
      }
      // user updated
      $where = ['id' => $id];
      $Santri_show = Santri::where($where)->first();
      $Santri = Santri::updateOrCreate([]);
      //$Santri = Santri::
      $tahunAjaran = TahunAjaran::where(['is_aktif' => 1])->first();
      if ($request->kamar_id != 0) {
        $SantriKamar = SantriKamar::where('santri_id', $id);
        if ($SantriKamar->count() > 0) {
          $santri_update = $SantriKamar->update(['status' => 0]);
        }
        $SantriKamar = SantriKamar::create([
          'kamar_id' => $request->kamar_id,
          'santri_id' => $request->id,
          'tahun_ajaran_id' => $tahunAjaran->id,
          'status' => 1,
        ]);
      }
      if ($request->tahfidz_id != 0) {
        $SantriTahfidz = SantriTahfidz::where('santri_id', $id);
        if ($SantriTahfidz->count() > 0) {
          $santri_update = $SantriTahfidz->update(['status' => 0]);
        }
        $SantriTahfidz = SantriTahfidz::create([
          'tahfidz_id' => $request->tahfidz_id,
          'santri_id' => $request->id,
          'tahun_ajaran_id' => $tahunAjaran->id,
          'status' => 1,
        ]);
      }
      return response()->json($Santri_show);
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
          'tanggal_lahir' => $request->tanggal_fix,
          'jenis_kelamin' => $request->jenis_kelamin,
          'alamat' => $request->alamat,
          'provinsi' => $request->provinsi,
          'kabkota' => $request->kabkota,
          'kecamatan' => $request->kecamatan,
          'kelurahan' => $request->kelurahan,
          'kode_pos' => $request->kode_pos,
          'nik' => $request->nik ?? '',
          'no_hp' => $request->no_hp,
          'tahfidz_id' => $request->tahfidz_id,
          'kamar_id' => $request->kamar_id,
          'kelas' => $request->kelas,
          'status' => $request->status,
        ]
      );
      $RefSantri = RefSiswa::updateOrCreate(
        ['id' => $id],
        [
          'kode' => $request->kelas,
          'kode_murroby' => $request->kamar_id,
          'nama' => $request->nama,
          'no_induk' => $request->no_induk,
          'password' => md5(date('dmy', strtotime($request->tanggal_fix)) . $request->jenis_kelamin),
          'status' => $request->status,
        ]
      );
      // $tahunAjaran = TahunAjaran::where(['is_aktif' => 1])->first();
      // if ($request->kamar_id != 0) {
      //   $SantriKamar = SantriKamar::where('santri_id', $id);
      //   if ($SantriKamar->count() > 0) {
      //     $santri_update = $SantriKamar->update(['status' => 0]);
      //   }
      //   $SantriKamar = SantriKamar::create([
      //     'kamar_id' => $request->kamar_id,
      //     'santri_id' => $Santri->id,
      //     'tahun_ajaran_id' => $tahunAjaran->id,
      //     'status' => 1,
      //   ]);
      // }
      // if ($request->tahfidz_id != 0) {
      //   $SantriTahfidz = SantriTahfidz::where('santri_id', $id);
      //   if ($SantriTahfidz->count() > 0) {
      //     $santri_update = $SantriTahfidz->update(['status' => 0]);
      //   }
      //   $SantriTahfidz = SantriTahfidz::create([
      //     'tahfidz_id' => $request->tahfidz_id,
      //     'santri_id' => $Santri->id,
      //     'tahun_ajaran_id' => $tahunAjaran->id,
      //     'status' => 1,
      //   ]);
      // }
      if ($Santri) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create Grades');
      }
    }
  }
  public function update_keluarga(Request $request)
  {
    $id = $request->id;
    $Santri = Santri::updateOrCreate(
      ['id' => $id],
      [
        'nik_kk' => $request->nik_kk,
        'nama_lengkap_ayah' => $request->nama_lengkap_ayah,
        'pendidikan_ayah' => $request->pendidikan_ayah,
        'pekerjaan_ayah' => $request->pekerjaan_ayah,
        'nama_lengkap_ibu' => $request->nama_lengkap_ibu,
        'pendidikan_ibu' => $request->pendidikan_ibu,
        'pekerjaan_ibu' => $request->pekerjaan_ibu,
      ]
    );

    if ($Santri) {
      // user created
      return response()->json('Created');
    } else {
      return response()->json('Failed Create Grades');
    }
  }
  public function update_kamar(Request $request)
  {
    $id = $request->id;
    DB::beginTransaction();
    try {
      $Santri = Santri::updateOrCreate(
        ['id' => $id],
        [
          'kamar_id' => $request->kamar_id,
        ]
      );
      $tahunAjaran = TahunAjaran::where(['is_aktif' => 1])->first();
      $santriKamar = SantriKamar::where('santri_id', $id);
      if ($santriKamar->count() > 0) {
        $santri_update = $santriKamar->update(['status' => 0]);
      }
      $SantriKamar = SantriKamar::create([
        'kamar_id' => $request->kamar_id,
        'santri_id' => $request->id,
        'tahun_ajaran_id' => $tahunAjaran->id,
        'status' => 1,
      ]);
      DB::commit();
      return response()->json('Created');
    } catch (\Exception $e) {
      DB::rollback();
      // something went wrong
      return response()->json($e);
    }
  }
  public function update_tahfidz(Request $request)
  {
    $id = $request->id;
    DB::beginTransaction();
    try {
      $Santri = Santri::updateOrCreate(
        ['id' => $id],
        [
          'tahfidz_id' => $request->tahfidz_id,
        ]
      );
      $tahunAjaran = TahunAjaran::where(['is_aktif' => 1])->first();
      $santriTahfidz = SantriTahfidz::where('santri_id', $id);
      if ($santriTahfidz->count() > 0) {
        $santri_update = $santriTahfidz->update(['status' => 0]);
      }
      $santriTahfidz = SantriTahfidz::create([
        'tahfidz_id' => $request->tahfidz_id,
        'santri_id' => $request->id,
        'tahun_ajaran_id' => $tahunAjaran->id,
        'status' => 1,
      ]);
      DB::commit();
      return response()->json('Created');
    } catch (\Exception $e) {
      DB::rollback();
      // something went wrong
      return response()->json($e);
    }
  }
  public function update_kelas(Request $request)
  {
    $id = $request->id;
    DB::beginTransaction();
    try {
      //get code from ref kelas
      $kelas = Kelas::find($request->kelas_id);
      $santri = Santri::where(['id' => $id]);
      $s_update = $santri->update(['kelas' => $kelas->code]);
      $tahunAjaran = TahunAjaran::where(['is_aktif' => 1])->first();
      $SantriKelas = SantriKelas::where('santri_id', $id);
      if ($SantriKelas->count() > 0) {
        $santri_update = $SantriKelas->update(['status' => 0]);
      }
      $SantriKelas = SantriKelas::create([
        'kelas_id' => $request->kelas_id,
        'santri_id' => $request->id,
        'tahun_ajaran_id' => $tahunAjaran->id,
        'status' => 1,
      ]);
      DB::commit();
      return response()->json('Created');
    } catch (\Exception $e) {
      DB::rollback();
      // something went wrong
      return response()->json($e);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
    $where = ['id' => $id];
    $var['santri'] = Santri::where($where)->first();
    $var['santri_photo'] = asset('assets/img/avatars/1.png');
    if (!empty($var['santri']->photo) && $var['santri']->photo_location == 2) {
      $var['santri_photo'] = asset('assets/img/upload/photo/' . $var['santri']->photo);
    } elseif (!empty($var['santri']->photo) && $var['santri']->photo_location == 1) {
      $var['santri_photo'] = 'https://payment.ppatq-rf.id/assets/upload/user/' . $var['santri']->photo;
    }
    $title = 'santri';
    $var['kota'] = City::all();
    $var['prov'] = Province::all();
    $var['kelas'] = Kelas::all();
    $var['kamar'] = Kamar::all();
    $var['tahfidz'] = Tahfidz::all();
    if ($var['santri']->kamar_id != 0) {
      $var['curr_kamar'] = Kamar::find($var['santri']->kamar_id);
    }
    if ($var['santri']->kelas != 0) {
      $var['curr_kelas'] = Kelas::where('code', $var['santri']->kelas)->first();
    }
    if ($var['santri']->tahfidz_id != 0) {
      $var['curr_tahfidz'] = Tahfidz::find($var['santri']->tahfidz_id);
    }
    $var['menu'] = ['biodata', 'keluarga', 'kamar', 'kelas', 'tahfidz'];
    $status = [0 => 'aktif', 1 => 'lulus/alumni', 2 => 'boyong/keluar', 3 => 'meninggal'];
    return view('admin.santri.show', compact('status', 'title', 'var'));
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
  public function teman_kamar(Request $request)
  {
    $kamar_id = $request->id;
    $santri = Santri::where('kamar_id', $kamar_id)->get();
    return response()->json($santri);
  }
  public function teman_kelas(Request $request)
  {
    $kelas_id = $request->id;
    $kelas = Kelas::find($kelas_id);
    $santri = Santri::where('kelas', $kelas->code)->get();
    return response()->json($santri);
  }
  public function teman_tahfidz(Request $request)
  {
    $kelas_id = $request->id;
    $santri = Santri::where('tahfidz_id', $kelas_id)->get();
    return response()->json($santri);
  }
  public function generate_kelas()
  {
    $santri = RefSiswa::all();
    foreach ($santri as $row) {
      echo $row->kode . ' ' . $row->no_induk;
      $santri_detail = Santri::where('no_induk', $row->no_induk);

      if ($santri_detail->count() > 0) {
        $santri_detail = $santri_detail->first();
        if ($santri_detail->kelas != $row->kode) {
          $santri_update = Santri::find($santri_detail->id);
          $santri_update->kelas = $row->kode;
          if ($santri_update->save()) {
            echo 'berhasil <br />';
          }
        }
      }
    }
  }
}
