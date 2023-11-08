<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PsbPesertaOnline;
use App\Models\PsbSekolahAsal;
use App\Models\PsbWaliPesertum;
use App\Models\PsbGelombang;
use App\Models\Province;
use App\Models\City;

class psb extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $array_bulan = [
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
  ];
  public $indexed = ['', 'id', 'nik', 'no_pendaftaran', 'nama', 'usia', 'status'];
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $title = 'Psb';
      $indexed = $this->indexed;
      return view('admin.psb.index', compact('title', 'indexed'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'nik',
        3 => 'no_pendaftaran',
        4 => 'nama',
        5 => 'usia',
        6 => 'status',
      ];

      $search = [];

      $totalData = PsbPesertaOnline::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $PsbPesertaOnline = PsbPesertaOnline::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $PsbPesertaOnline = PsbPesertaOnline::where(function ($query) use ($search) {
          $query
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->orWhere('nik', 'LIKE', "%{$search}%")
            ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
        })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = PsbPesertaOnline::where('jabatan_new', 12)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('nik', 'LIKE', "%{$search}%")
              ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
          })
          ->count();
      }

      $data = [];

      if (!empty($PsbPesertaOnline)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($PsbPesertaOnline as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['nik'] = $row->nik ?? '';
          $nestedData['no_pendaftaran'] = $row->no_pendaftaran . '';
          $nestedData['nama'] = $row->nama ?? '';
          $nestedData['usia'] = $row->usia_tahun . ' Tahun ' . $row->usia_bulan . ' Bulan';
          $nestedData['status'] = $row->status ?? '';
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
    $title = 'Psb';
    $indexed = $this->indexed;
    $provinsi = Province::all();
    return view('admin.psb.create', compact('title', 'indexed', 'provinsi'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
    $gelombang = PsbGelombang::where('pmb_online', 1)->first();
    $data = new PsbPesertaOnline();
    $data->nik = $request->nik;
    $data->nama = $request->nama_lengkap;
    $data->nama_panggilan = $request->nama_panggilan;
    $data->jenis_kelamin = $request->jenis_kelamin;
    $data->tempat_lahir = $request->tempat_lahir;
    $data->tanggal_lahir = date('Y-m-d', strtotime($request->tanggal_lahir));
    $data->usia_bulan = $request->usia_bulan;
    $data->usia_tahun = $request->usia_tahun;
    $data->jumlah_saudara = $request->jumlah_saudara;
    $data->anak_ke = $request->anak_ke;
    $data->alamat = $request->alamat;
    $data->prov_id = $request->provinsi;
    $data->kota_id = $request->kota;
    $data->kecamatan = $request->kecamatan;
    $data->kelurahan = $request->kelurahan;
    $data->kode_pos = $request->kode_pos;
    $data->gelombang_id = $gelombang->id;
    if ($data->save()) {
      $id = $data->id;
      $data_wali = new PsbWaliPesertum();
      $data_wali->nama_ayah = $request->nama_ayah;
      $data_wali->nama_ibu = $request->nama_ibu;
      $data_wali->pekerjaan_ayah = $request->pekerjaan_ayah;
      $data_wali->pekerjaan_ibu = $request->pekerjaan_ibu;
      $data_wali->pendidikan_ayah = $request->pendidikan_ayah;
      $data_wali->pendidikan_ibu = $request->pendidikan_ibu;
      $data_wali->alamat_ayah = $request->alamat_ayah;
      $data_wali->alamat_ibu = $request->alamat_ibu;
      $data_wali->no_hp = $request->no_hp;
      $data_wali->no_telp = $request->no_telp;
      $data_wali->psb_peserta_id = $id;
      $data_wali->save();
      $data_sekolah = new PsbSekolahAsal();
      $data_sekolah->jenjang = $request->jenjang;
      $data_sekolah->nama_sekolah = $request->nama_sekolah;
      $data_sekolah->nss = $request->nss;
      $data_sekolah->npsn = $request->npsn;
      $data_sekolah->nisn = $request->nisn;
      $data_sekolah->kelas = $request->kelas;
      $data_sekolah->psb_peserta_id = $id;
      $data_sekolah->save();

      return 1;
    } else {
      return 2; // erro saat input data
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
    echo $id;
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
