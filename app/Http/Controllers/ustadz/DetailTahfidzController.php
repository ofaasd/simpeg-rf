<?php

namespace App\Http\Controllers\ustadz;

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

class DetailTahfidzController extends Controller
{
  /**
   * Display a listing of the resource.
   */
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
    //
    // $bulan = (int) date('m');
    // $tahun = date('Y');
    // $id_user = Auth::user()->id;
    // $user = User::find($id_user);
    // $id = $user->pegawai_id;
    // $where = ['id' => $id];
    // $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    // $title = 'Pegawai';
    // $tahfidz = Tahfidz::where('employee_id', $id)->first();

    // $var['list_santri'] = Santri::where('tahfidz_id', $tahfidz->id)->get();
    // $var['list_detail_tahfidz'] = DetailSantriTahfidz::where('bulan', $bulan)
    //   ->where('tahun', $tahun)
    //   ->get();
    // return view('ustadz.tahfidz.tahfidz', compact('title', 'var'));

    if (empty($request->input('length'))) {
      $id_user = Auth::user()->id;
      $user = User::find($id_user);
      $id = $user->pegawai_id;
      $where = ['id' => $id];
      $var['EmployeeNew'] = EmployeeNew::where($where)->first();
      $tahfidz = Tahfidz::where('employee_id', $id)->first();
      $var['list_santri'] = Santri::where('tahfidz_id', $tahfidz->id)->get();
      $var['bulan'] = $this->bulan;
      $ta = TahunAjaran::where(['is_aktif' => 1])->first();
      $var['id_tahfidz'] = $tahfidz->id;
      $title = 'Tahfidz Santri';
      $page = 'DetailTahfidz';
      $var['kode_juz'] = KodeJuz::all();
      $indexed = $this->indexed;
      return view('ustadz.tahfidz.tahfidz', compact('title', 'page', 'indexed', 'var', 'ta'));
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

      if (empty($request->input('search.value'))) {
        $detail = DetailSantriTahfidz::where('bulan', date('m'))
          ->where('tahun', date('Y'))
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $detail = DetailSantriTahfidz::where('bulan', date('m'))
          ->where('tahun', date('Y'))
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
      $bulan = date('m', strtotime($request->tanggal));
      $tahun = date('Y', strtotime($request->tanggal));
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
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      $bulan = date('m', strtotime($request->tanggal));
      $tahun = date('Y', strtotime($request->tanggal));
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
