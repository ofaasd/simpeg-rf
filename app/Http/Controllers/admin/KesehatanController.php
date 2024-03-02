<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\TbKesehatan;
use App\Models\TbPemeriksaan;

class KesehatanController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    //
    $var['list_bulan'] = [
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
    $santri = Santri::all();
    $title = 'Kesehatan Santri';
    $bulan = date('m');
    $tahun = date('Y');
    if (!empty($request->bulan)) {
      $bulan = $request->bulan;
      $tahun = $request->tahun;
    }
    $var['bulan'] = $bulan;
    $var['tahun'] = $tahun;
    $kesehatan = TbKesehatan::whereRaw('MONTH(FROM_UNIXTIME(tanggal_sakit)) = ' . $bulan)
      ->whereRaw('YEAR(FROM_UNIXTIME(tanggal_sakit)) = ' . $tahun)
      ->get();
    $list_santri = [];
    foreach ($santri as $row) {
      $list_santri[$row->no_induk] = $row;
    }

    return view('admin.kesehatan.index', compact('santri', 'list_santri', 'title', 'kesehatan', 'var'));
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

      $kesehatan = TbKesehatan::updateOrCreate(
        ['id' => $id],
        [
          'santri_id' => $request->santri_id,
          'sakit' => $request->sakit,
          'tanggal_sakit' => strtotime($request->tanggal_sakit),
          'keterangan_sakit' => $request->keterangan_sakit,
          'keterangan_sembuh' => $request->keterangan_sembuh,
          'tindakan' => $request->tindakan,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $kesehatan = TbKesehatan::updateOrCreate(
        ['id' => $id],
        [
          'santri_id' => $request->santri_id,
          'sakit' => $request->sakit,
          'tanggal_sakit' => strtotime($request->tanggal_sakit),
          'keterangan_sakit' => $request->keterangan_sakit,
          'keterangan_sembuh' => $request->keterangan_sembuh,
          'tindakan' => $request->tindakan,
        ]
      );
      if ($kesehatan) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create');
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

    $kesehatan = TbKesehatan::where($where)->first();
    return response()->json($kesehatan);
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
    $kesehatan = TbKesehatan::where('id', $id)->delete();
  }

  public function reload(Request $request)
  {
    $var['list_bulan'] = [
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
    $santri = Santri::all();
    $title = 'Kesehatan Santri';
    $bulan = date('m');
    $tahun = date('Y');
    if (!empty($request->bulan)) {
      $bulan = $request->bulan;
      $tahun = $request->tahun;
    }
    $var['bulan'] = $bulan;
    $var['tahun'] = $tahun;
    $kesehatan = TbKesehatan::whereRaw('MONTH(FROM_UNIXTIME(tanggal_sakit)) = ' . $bulan)
      ->whereRaw('YEAR(FROM_UNIXTIME(tanggal_sakit)) = ' . $tahun)
      ->get();
    $list_santri = [];
    foreach ($santri as $row) {
      $list_santri[$row->no_induk] = $row;
    }

    return view('admin.kesehatan.table', compact('santri', 'list_santri', 'title', 'kesehatan', 'var'));
  }

  public function santri(Request $request)
  {
    //
    $santri = Santri::where('status', 0)->get();

    $var['berat_badan'] = [];
    $var['tinggi_badan'] = [];
    $var['lingkar_pinggul'] = [];
    $var['lingkar_dada'] = [];
    $var['kondisi_gigi'] = [];
    $var['tanggal_periksa'] = [];

    foreach ($santri as $row) {
      $pemeriksaan = TbPemeriksaan::where('no_induk', $row->no_induk)->orderBy('id', 'desc');
      if ($pemeriksaan->count() > 0) {
        $hasil = $pemeriksaan->first();
        $var['berat_badan'][$row->no_induk] = $hasil->berat_badan;
        $var['tinggi_badan'][$row->no_induk] = $hasil->tinggi_badan;
        $var['lingkar_pinggul'][$row->no_induk] = $hasil->lingkar_pinggul;
        $var['lingkar_dada'][$row->no_induk] = $hasil->lingkar_dada;
        $var['kondisi_gigi'][$row->no_induk] = $hasil->kondisi_gigi;
        $var['tanggal_periksa'][$row->no_induk] = date('d-m-Y', $hasil->tanggal_periksa);
      } else {
        $var['berat_badan'][$row->no_induk] = 0;
        $var['tinggi_badan'][$row->no_induk] = 0;
        $var['lingkar_pinggul'][$row->no_induk] = 0;
        $var['lingkar_dada'][$row->no_induk] = 0;
        $var['kondisi_gigi'][$row->no_induk] = 0;
        $var['tanggal_periksa'][$row->no_induk] = '';
      }
    }
    $title = 'Daftar Santri';
    return view('admin.kesehatan.santri', compact('santri', 'title', 'var'));
  }
  public function get_santri(string $id)
  {
    $where = ['id' => $id];
    $var['santri'] = Santri::where($where)->first();
    $var['santri_photo'] = asset('assets/img/avatars/1.png');
    if (!empty($var['santri']->photo) && $var['santri']->photo_location == 2) {
      $var['santri_photo'] = asset('assets/img/upload/photo/' . $var['santri']->photo);
    } elseif (!empty($var['santri']->photo) && $var['santri']->photo_location == 1) {
      $var['santri_photo'] = 'https://payment.ppatq-rf.id/assets/upload/user/' . $var['santri']->photo;
    }
    $title = 'santri';

    $var['menu'] = ['pemeriksaan'];
    $status = [0 => 'aktif', 1 => 'lulus/alumni', 2 => 'boyong/keluar', 3 => 'meninggal'];

    $var['pemeriksaan'] = TbPemeriksaan::where('no_induk', $var['santri']->no_induk)->get();
    return view('admin.kesehatan.detail', compact('status', 'title', 'var'));
  }
}
