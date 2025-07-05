<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeNew;
use App\Models\Tahfidz;
use App\Models\Santri;
use App\Models\User;
use App\Models\DetailSantriTahfidz;
use App\Models\KodeJuz;
use App\Models\TahunAjaran;
use Session;

class AdminDetailTahfidzController extends Controller
{
  //
  public $indexed = ['', 'id', 'no_induk', 'bulan', 'kode_juz_surah'];
  public $bulan = [
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
  public function show(Request $request, String $id)
  {
    $bulan = 0;
    $tahun = 0;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $tahfidz = Tahfidz::where('employee_id', $id)->first();
    if (empty($request->input('length'))) {
      $var['list_santri'] = Santri::select("santri_detail.*","employee_new.nama as nama_murroby")->where('tahfidz_id', $tahfidz->id)->leftJoin("ref_kamar","ref_kamar.id","=","santri_detail.kamar_id")->leftJoin("employee_new","employee_new.id","=","ref_kamar.employee_id")->get();
      $var['bulan'] = $this->bulan;
      $ta = TahunAjaran::where(['is_aktif' => 1])->first();
      $var['id_tahfidz'] = $tahfidz->id;
      $title = 'Tahfidz Santri';
      $page = 'detail_ketahfidzan';
      $var['kode_juz'] = KodeJuz::all();
      $indexed = $this->indexed;
      return view('admin.tahfidz.tahfidz_detail', compact('title', 'page', 'indexed', 'var', 'ta','id'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'no_induk',
        3 => 'bulan',
        4 => 'kode_juz_surah',
      ];

      $search = [];

      $totalData = DetailSantriTahfidz::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if($bulan == 0){
        $bulan = date('m');
      }

      if($tahun == 0){
        $tahun = date('Y');
      }


      if (empty($request->input('search.value'))) {
        // $detail = DetailSantriTahfidz::where('bulan', $bulan)
        //   ->where('tahun', $tahun)
        //   ->offset($start)
        //   ->limit($limit)
        //   ->orderBy($order, $dir)
        //   ->get();
        $detail = DetailSantriTahfidz::where('id_tahfidz', $tahfidz->id)
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        // $detail = DetailSantriTahfidz::where('bulan', $bulan)
        //   ->where('tahun', $tahun)
        //   ->where(function ($query) use ($search) {
        //     $query
        //       ->where('id', 'LIKE', "%{$search}%")
        //       ->orWhereRelation('santri', 'nama', 'like', "%{$search}%")
        //       ->orWhereRelation('kode_juz', 'nama', 'like', "%{$search}%");
        //   })
        //   ->offset($start)
        //   ->limit($limit)
        //   ->orderBy($order, $dir)
        //   ->get();
        $detail = DetailSantriTahfidz::where('id_tahfidz', $tahfidz->id)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhereRelation('santri_detail', 'nama', 'like', "%{$search}%")
              ->orWhereRelation('kode_juz', 'nama', 'like', "%{$search}%");
          })
          ->join('santri_detail', 'detail_santri_tahfidz.no_induk', '=', 'santri_detail.no_induk')
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        // $totalFiltered = DetailSantriTahfidz::where('bulan', $bulan)
        //   ->where('tahun', $tahun)
        //   ->where(function ($query) use ($search) {
        //     $query
        //       ->where('id', 'LIKE', "%{$search}%")
        //       ->orWhereRelation('santri', 'nama', 'like', "%{$search}%")
        //       ->orWhereRelation('kode_juz', 'nama', 'like', "%{$search}%");
        //   })
        //   ->count();
        $totalFiltered = DetailSantriTahfidz::where('id_tahfidz', $tahfidz->id)
            ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhereRelation('santri_detail', 'nama', 'like', "%{$search}%")
              ->orWhereRelation('kode_juz', 'nama', 'like', "%{$search}%");
          })
          ->count();
      }

      $data = [];

      if (!empty($detail)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($detail as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['nama'] = $row->nama;
          $nestedData['no_induk'] = $row->nama;
          $nestedData['bulan'] = $this->bulan[$row->bulan];
          $nestedData['kode_juz_surah'] = $row->kode_juz->nama;
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
          'dump' => $detail,
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

  public function store(Request $request)
  {
    //
    $id = $request->id;
    $tanggal = $request->tanggal;

    if ($id) {
      // update the value
      $bulan = date('m', strtotime($tanggal));
      $tahun = date('Y', strtotime($tanggal));
      $detail = DetailSantriTahfidz::updateOrCreate(
        ['id' => $id],
        [
          'id_tahfidz' => $request->id_tahfidz,
          'no_induk' => $request->no_induk,
          'bulan' => $bulan,
          'tahun' => $tahun,
          'tanggal' => $tanggal,
          'id_tahun_ajaran' => $request->id_tahun_ajaran,
          'kode_juz_surah' => $request->kode_juz_surah,
          'hafalan' => $request->hafalan,
          'tilawah' => $request->tilawah,
          'kefasihan' => $request->kefasihan,
          'daya_ingat' => $request->daya_ingat,
          'kelancaran' => $request->kelancaran,
          'praktek_tajwid' => $request->praktek_tajwid,
          'makhroj' => $request->makhroj,
          'tanafus' => $request->tanafus,
          'waqof_wasol' => $request->waqof_wasol,
          'ghorib' => $request->ghorib,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      $bulan = date('m', strtotime($tanggal));
      $tahun = date('Y', strtotime($tanggal));
      $cek_data = DetailSantriTahfidz::where('id_tahfidz', $request->id_tahfidz)
        ->where('no_induk', $request->no_induk)
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->first();
      if (!empty($cek_data->id)) {
        $id = $cek_data->id;
      }

      $detail = DetailSantriTahfidz::updateOrCreate(
        ['id' => $id],
        [
          'id_tahfidz' => $request->id_tahfidz,
          'no_induk' => $request->no_induk,
          'bulan' => $bulan,
          'tahun' => $tahun,
          'tanggal' => $tanggal,
          'id_tahun_ajaran' => $request->id_tahun_ajaran,
          'kode_juz_surah' => $request->kode_juz_surah,
          'hafalan' => $request->hafalan,
          'tilawah' => $request->tilawah,
          'kefasihan' => $request->kefasihan,
          'daya_ingat' => $request->daya_ingat,
          'kelancaran' => $request->kelancaran,
          'praktek_tajwid' => $request->praktek_tajwid,
          'makhroj' => $request->makhroj,
          'tanafus' => $request->tanafus,
          'waqof_wasol' => $request->waqof_wasol,
          'ghorib' => $request->ghorib,
        ]
      );
      if ($detail) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create Academic');
      }
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
    $where = ['id' => $id];

    $detail = DetailSantriTahfidz::where($where)->first();

    return response()->json($detail);
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
    $detail = DetailSantriTahfidz::where('id', $id)->delete();
  }

}
