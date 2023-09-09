<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'id_tahun', 'awal', 'akhir', 'jenis', 'is_aktif'];
  public $jenis = [1 => 'Ganjil', 'Genap', 'Antara Ganjil Genap', 'Antara Genap Ganjil'];
  public $aktif = ['tidak_aktif', 'aktif'];
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $title = 'TA';
      $indexed = $this->indexed;
      $jenis = $this->jenis;
      $aktif = $this->aktif;
      return view('admin.ta.index', compact('title', 'indexed', 'jenis', 'aktif'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'id_tahun',
        3 => 'awal',
        4 => 'akhir',
        5 => 'jenis',
        6 => 'is_aktif',
      ];

      $search = [];

      $totalData = TahunAjaran::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $TahunAjaran = TahunAjaran::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $TahunAjaran = TahunAjaran::where('id', 'LIKE', "%{$search}%")
          ->orWhere('id_tahun', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = TahunAjaran::where('id', 'LIKE', "%{$search}%")
          ->orWhere('id_tahun', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($TahunAjaran)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($TahunAjaran as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['id_tahun'] = $row->id_tahun;
          $nestedData['awal'] = $row->awal;
          $nestedData['akhir'] = $row->akhir;
          $nestedData['jenis'] = $this->jenis[$row->jenis];
          $nestedData['is_aktif'] = $this->aktif[$row->is_aktif];
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
      $TahunAjaran = TahunAjaran::updateOrCreate(
        ['id' => $id],
        [
          'id_tahun' => $request->id_tahun,
          'awal' => $request->awal,
          'akhir' => $request->akhir,
          'jenis' => $request->jenis,
          'is_aktif' => $request->is_aktif,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $TahunAjaran = TahunAjaran::updateOrCreate(
        ['id' => $id],
        [
          'id_tahun' => $request->id_tahun,
          'awal' => $request->awal,
          'akhir' => $request->akhir,
          'jenis' => $request->jenis,
          'is_aktif' => $request->is_aktif,
        ]
      );
      if ($TahunAjaran) {
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

    $TahunAjaran = TahunAjaran::where($where)->first();

    return response()->json($TahunAjaran);
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
