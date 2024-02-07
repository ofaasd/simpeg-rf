<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\TbKesehatan;

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
      $list_santri[$row->id] = $row;
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
      if (empty($request->tanggal_sembuh) || empty($request->keterangan_sembuh)) {
        $kesehatan = TbKesehatan::updateOrCreate(
          ['id' => $id],
          [
            'santri_id' => $request->santri_id,
            'sakit' => $request->sakit,
            'tanggal_sakit' => strtotime($request->tanggal_sakit),
            'keterangan_sakit' => $request->keterangan_sakit,
          ]
        );
      } else {
        $kesehatan = TbKesehatan::updateOrCreate(
          ['id' => $id],
          [
            'santri_id' => $request->santri_id,
            'sakit' => $request->sakit,
            'tanggal_sakit' => strtotime($request->tanggal_sakit),
            'keterangan_sakit' => $request->keterangan_sakit,
            'tanggal_sembuh' => strtotime($request->tanggal_sembuh),
            'keterangan_sembuh' => $request->keterangan_sembuh,
          ]
        );
      }

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();
      if (empty($request->tanggal_sembuh) || empty($request->keterangan_sembuh)) {
        $kesehatan = TbKesehatan::updateOrCreate(
          ['id' => $id],
          [
            'santri_id' => $request->santri_id,
            'sakit' => $request->sakit,
            'tanggal_sakit' => strtotime($request->tanggal_sakit),
            'keterangan_sakit' => $request->keterangan_sakit,
          ]
        );
      } else {
        $kesehatan = TbKesehatan::updateOrCreate(
          ['id' => $id],
          [
            'santri_id' => $request->santri_id,
            'sakit' => $request->sakit,
            'tanggal_sakit' => strtotime($request->tanggal_sakit),
            'keterangan_sakit' => $request->keterangan_sakit,
            'tanggal_sembuh' => strtotime($request->tanggal_sembuh),
            'keterangan_sembuh' => $request->keterangan_sembuh,
          ]
        );
      }
      if ($kesehatan) {
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
      $list_santri[$row->id] = $row;
    }

    return view('admin.kesehatan.table', compact('santri', 'list_santri', 'title', 'kesehatan', 'var'));
  }
}
