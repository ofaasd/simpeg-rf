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
  public function index(Request $request)
  {
    if (empty($request->input('length'))) {
      $id = Session::get('employee_id');
      $where = ['id' => $id];
      $var['EmployeeNew'] = EmployeeNew::where($where)->first();
      $tahfidz = Tahfidz::find(Session::get('tahfidz_id'));
      $var['list_santri'] = Santri::where('tahfidz_id', $tahfidz->id)->get();
      $var['bulan'] = $this->bulan;
      $ta = TahunAjaran::where(['is_aktif' => 1])->first();
      $var['id_tahfidz'] = $tahfidz->id;
      $title = 'Tahfidz Santri';
      $page = 'DetailKetahfidzan';
      $var['kode_juz'] = KodeJuz::all();
      $indexed = $this->indexed;
      return view('admin.tahfidz.tahfidz_detail', compact('title', 'page', 'indexed', 'var', 'ta', 'id'));
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
      $santri = Santri::where('tahfidz_id', Session::get('tahfidz_id'))->get();
      $arr_santri = [];
      foreach ($santri as $row) {
        $arr_santri[] = $row->no_induk;
      }

      if (empty($request->input('search.value'))) {
        $detail = DetailSantriTahfidz::where('bulan', date('m'))
          ->where('tahun', date('Y'))
          ->whereIn('no_induk', $arr_santri)
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $detail = DetailSantriTahfidz::where('bulan', date('m'))
          ->where('tahun', date('Y'))
          ->whereIn('no_induk', $arr_santri)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhereRelation('santri', 'nama', 'like', "%{$search}%")
              ->orWhereRelation('kode_juz', 'nama', 'like', "%{$search}%");
          })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = DetailSantriTahfidz::where('bulan', date('m'))
          ->where('tahun', date('Y'))
          ->whereIn('no_induk', $arr_santri)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhereRelation('santri', 'nama', 'like', "%{$search}%")
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
          $nestedData['no_induk'] = $row->santri->nama;
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

    if ($id) {
      // update the value
      $detail = DetailSantriTahfidz::updateOrCreate(
        ['id' => $id],
        [
          'id_tahfidz' => $request->id_tahfidz,
          'no_induk' => $request->no_induk,
          'bulan' => $request->bulan,
          'tahun' => $request->tahun,
          'id_tahun_ajaran' => $request->id_tahun_ajaran,
          'kode_juz_surah' => $request->kode_juz_surah,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      $cek_data = DetailSantriTahfidz::where('id_tahfidz', $request->id_tahfidz)
        ->where('no_induk', $request->no_induk)
        ->where('bulan', $request->bulan)
        ->where('tahun', $request->tahun)
        ->first();
      if (!empty($cek_data->id)) {
        $id = $cek_data->id;
      }

      $detail = DetailSantriTahfidz::updateOrCreate(
        ['id' => $id],
        [
          'id_tahfidz' => $request->id_tahfidz,
          'no_induk' => $request->no_induk,
          'bulan' => $request->bulan,
          'tahun' => $request->tahun,
          'id_tahun_ajaran' => $request->id_tahun_ajaran,
          'kode_juz_surah' => $request->kode_juz_surah,
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
}
