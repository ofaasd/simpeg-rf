<?php

namespace App\Http\Controllers\ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeNew;
use App\Models\TahunAjaran;
use App\Models\Santri;
use App\Models\SakuKeluar;
use App\Models\UangSaku;
use App\Models\User;
use App\Models\Kamar;
use Illuminate\Support\Facades\DB;
use Session;

class SakuKeluarController extends Controller
{
  //
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
  public $indexed = ['', 'id', 'santri', 'note', 'jumlah', 'tanggal'];
  public function index(Request $request)
  {
    $id_user = Auth::user()->id;
    $user = User::find($id_user);
    $id = $user->pegawai_id;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $kamar = Kamar::where('employee_id', $id)->first();
    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    $list_santri = [];
    foreach ($var['list_santri'] as $row) {
      $list_santri[] = $row->no_induk;
    }
    if (empty(Session::get('bulan'))) {
      $bulan = date('m');
      $tahun = date('Y');
    } else {
      $bulan = Session::get('bulan');
      $tahun = Session::get('tahun');
    }

    $var['bulan'] = $this->bulan;
    if (empty($request->input('length'))) {
      $page = 'SakuKeluar';
      $title = 'Saku Keluar';
      $indexed = $this->indexed;

      return view('ustadz.saku_keluar.index', compact('title', 'bulan', 'tahun', 'indexed', 'var', 'page'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'santri',
        3 => 'note',
        4 => 'jumlah',
        5 => 'tanggal',
      ];

      $search = [];

      $totalData = SakuKeluar::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $saku = SakuKeluar::whereIn('no_induk', $list_santri)
          ->whereMonth('tanggal', $bulan)
          ->whereYear('tanggal', $tahun)
          ->offset($start)
          ->orderBy('id', $dir)
          ->limit($limit)
          ->get();
      } else {
        $search = $request->input('search.value');

        $saku = SakuKeluar::whereIn('no_induk', $list_santri)
          ->whereMonth('tanggal', $bulan)
          ->whereYear('tanggal', $tahun)
          ->where(function ($query) use ($search) {
            $query->whereRelation('santri', 'nama', 'like', "%{$search}%");
          })
          ->offset($start)
          ->limit($limit)
          ->orderBy('id', $dir)
          ->get();

        $totalFiltered = SakuKeluar::whereIn('no_induk', $list_santri)
          ->whereMonth('tanggal', $bulan)
          ->whereYear('tanggal', $tahun)
          ->where(function ($query) use ($search) {
            $query->whereRelation('santri', 'nama', 'like', "%{$search}%");
          })
          ->count();
      }

      $data = [];

      if (!empty($saku)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($saku as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['santri'] = $row->santri->nama;
          $nestedData['note'] = $row->note;
          $nestedData['jumlah'] = number_format($row->jumlah, 0, ',', '.');
          $nestedData['tanggal'] = $row->tanggal;

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
    $id = $request->id;
    $id_user = Auth::user()->id;
    $user = User::find($id_user);
    $pegawai_id = $user->pegawai_id;
    if ($id) {
      DB::beginTransaction();
      try {
        $jumlah = (int) str_replace('.', '', $request->jumlah);
        //ambil nilai lama dulu
        $SakuKeluar = SakuKeluar::find($id);
        $old_jumlah = $SakuKeluar->jumlah;
        $old_no_induk = $SakuKeluar->no_induk;
        //kurangi uang saku sebelumnya
        $saku = UangSaku::where('no_induk', $old_no_induk)->first();
        $updateSaku = UangSaku::find($saku->id);
        $updateSaku->jumlah = $saku->jumlah + $old_jumlah;
        $updateSaku->save();
        //update SakuKeluar
        $SakuKeluar = SakuKeluar::updateOrCreate(
          ['id' => $id],
          [
            'note' => $request->note,
            'jumlah' => $jumlah,
            'no_induk' => $request->no_induk,
            'pegawai_id' => $pegawai_id,
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
          ]
        );
        //perbarui nilai uang saku sekarang
        $saku = UangSaku::where('no_induk', $request->no_induk)->first();
        $updateSaku = UangSaku::find($saku->id);
        $updateSaku->jumlah = $saku->jumlah - $jumlah;
        $updateSaku->save();

        // $updateSaku = UangSaku::update(
        //   ['no_induk' => $request->no_induk],
        //   ['jumlah' => $saku->jumlah + $request->jumlah]
        // );

        DB::commit();
        return response()->json('Created');
      } catch (\Exception $e) {
        DB::rollback();
        // something went wrong
        return response()->json($e);
      }
    } else {
      DB::beginTransaction();
      try {
        $jumlah = (int) str_replace('.', '', $request->jumlah);
        $SakuKeluar = SakuKeluar::create([
          'note' => $request->note,
          'jumlah' => $jumlah,
          'no_induk' => $request->no_induk,
          'pegawai_id' => $pegawai_id,
          'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
        ]);
        $saku = UangSaku::where('no_induk', $request->no_induk)->first();

        // $updateSaku = UangSaku::update(
        //   ['no_induk' => $request->no_induk],
        //   ['jumlah' => $saku->jumlah + $request->jumlah]
        // );
        $updateSaku = UangSaku::find($saku->id);
        $updateSaku->jumlah = $saku->jumlah - $jumlah;
        $updateSaku->save();
        DB::commit();
        return response()->json('Created');
      } catch (\Exception $e) {
        DB::rollback();
        // something went wrong
        return response()->json($e);
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

    $saku_keluar = SakuKeluar::where($where)->first();

    return response()->json($saku_keluar);
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
    DB::beginTransaction();
    try {
      $saku_keluar = SakuKeluar::find($id);
      $uang_saku = UangSaku::where('no_induk', $saku_keluar->no_induk)->first();
      $jumlah = $uang_saku->jumlah + $saku_keluar->jumlah;
      $uang_saku_new = UangSaku::find($uang_saku->id);
      $uang_saku_new->jumlah = $jumlah;
      $uang_saku_new->save();

      $uang_saku->$saku_keluar = SakuKeluar::where('id', $id)->delete();
      DB::commit();
      return response()->json('Created');
    } catch (\Exception $e) {
      DB::rollback();
      // something went wrong
      return response()->json($e);
    }
  }
}
