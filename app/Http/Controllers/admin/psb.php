<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PsbPesertaOnline;
use App\Models\PsbSekolahAsal;
use App\Models\PsbWaliPesertum;

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
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
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
