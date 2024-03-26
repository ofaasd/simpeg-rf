<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\RefJenisPembayaran;
use App\Models\Kamar;
use App\Models\EmployeeNew;
use App\Models\Santri;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembayaranExport;

class PembayaranController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = [
    '',
    'id',
    'Nama Santri',
    'Kelas',
    'Jumlah(Rp)',
    'Tanggal',
    'Bulan',
    'Bank',
    'AN',
    'Note',
    'Validasi',
  ];
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

    $periode = (int) date('m');
    $tahun = (int) date('Y');
    $kelas = 0;
    $data['kelas'] = 0;
    if (empty($request->periode)) {
      $where = [
        'periode' => $periode,
        'tahun' => $tahun,
        'is_hapus' => 0,
      ];
    } else {
      $where = [
        'periode' => $request->periode,
        'tahun' => $request->tahun,
        'kelas' => $request->kelas,
        'is_hapus' => 0,
      ];
      $periode = $request->periode;
      $data['kelas'] = $request->kelas;
    }
    $kelas = Santri::select('kelas')
      ->groupBy('kelas')
      ->orderBy('kelas')
      ->get();
    $pembayaran = Pembayaran::where($where)
      ->join('santri_detail', 'santri_detail.no_induk', '=', 'tb_pembayaran.nama_santri')
      ->get();
    $title = 'Pembayaran';
    $kamar = Kamar::all();
    $data['nama_murroby'] = [];
    $data['bulan'] = $this->bulan;
    $data['periode'] = $periode;
    $data['tahun'] = $tahun;

    foreach ($kamar as $row) {
      // $data['nama_murroby'][$row->id] = $this->db
      //   ->get_where('employee_new', ['id' => $row->employee_id])
      //   ->row()->nama;
      $data['nama_murroby'][$row->id] = EmployeeNew::find($row->employee_id)->nama;
    }
    return view('admin.pembayaran.index', compact('title', 'pembayaran', 'data', 'kelas'));
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
    if (empty($request->input('length'))) {
      $academic = academic::all();
      $title = 'Academic';
      $indexed = $this->indexed;
      return view('admin.academic.index', compact('title', 'indexed'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'name',
        3 => 'description',
      ];

      $search = [];

      $totalData = Academic::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $academic = Academic::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $academic = Academic::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = Academic::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($academic)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($academic as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['name'] = $row->name;
          $nestedData['description'] = $row->description;
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
  public function export(Request $request)
  {
    return Excel::download(
      new PembayaranExport($request->tahun, $request->periode, $request->kelas),
      'DataPembayaran' . $request->tahun . '-' . $request->periode . '-' . $request->kelas . '.xlsx'
    );
  }
}
