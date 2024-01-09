<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PsbGelombang;
use App\Models\TahunAjaran;

class GelombangController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'no_gel', 'nama_gel', 'nama_gel_long', 'tgl_mulai', 'tgl_akhir', 'pmb_online'];

  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $academic = PsbGelombang::all();
      $title = 'Gelombang';
      $indexed = $this->indexed;
      $tahun = TahunAjaran::orderBy('id', 'desc')->get();
      return view('admin.gelombang.index', compact('tahun', 'title', 'indexed'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'no_gel',
        3 => 'nama_gel',
        4 => 'nama_gel_long',
        5 => 'tgl_mulai',
        6 => 'tgl_akhir',
        7 => 'pmb_online',
      ];

      $search = [];

      $totalData = PsbGelombang::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $gelombang = PsbGelombang::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $gelombang = PsbGelombang::where('id', 'LIKE', "%{$search}%")
          ->orWhere('no_gel', 'LIKE', "%{$search}%")
          ->orWhere('nama_gel', 'LIKE', "%{$search}%")
          ->orWhere('nama_gel_long', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = PsbGelombang::where('id', 'LIKE', "%{$search}%")
          ->orWhere('no_gel', 'LIKE', "%{$search}%")
          ->orWhere('nama_gel', 'LIKE', "%{$search}%")
          ->orWhere('nama_gel_long', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($gelombang)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($gelombang as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['no_gel'] = $row->no_gel;
          $nestedData['nama_gel'] = $row->nama_gel;
          $nestedData['nama_gel_long'] = $row->nama_gel_long;
          $nestedData['tgl_mulai'] = date('d-m-Y', strtotime($row->tgl_mulai));
          $nestedData['tgl_akhir'] = date('d-m-Y', strtotime($row->tgl_akhir));
          $nestedData['tahun'] = $row->tahun;
          $nestedData['jenis'] = $row->jenis;
          $nestedData['pmb_online'] = $row->pmb_online;
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
      $gelombang = PsbGelombang::updateOrCreate(
        ['id' => $id],
        [
          'no_gel' => $request->no_gel,
          'nama_gel' => $request->nama_gel,
          'nama_gel_long' => $request->nama_gel_long,
          'tgl_mulai' => $request->tgl_mulai,
          'tgl_akhir' => $request->tgl_akhir,
          'tahun' => $request->tahun,
          'jenis' => $request->jenis,
          'pmb_online' => $request->pmb_online,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $gelombang = PsbGelombang::updateOrCreate(
        ['id' => $id],
        [
          'no_gel' => $request->no_gel,
          'nama_gel' => $request->nama_gel,
          'nama_gel_long' => $request->nama_gel_long,
          'tgl_mulai' => $request->tgl_mulai,
          'tgl_akhir' => $request->tgl_akhir,
          'tahun' => $request->tahun,
          'jenis' => $request->jenis,
          'pmb_online' => $request->pmb_online,
        ]
      );
      if ($gelombang) {
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

    $gelombang = PsbGelombang::where($where)->first();

    return response()->json($gelombang);
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
    $gelombang = PsbGelombang::where('id', $id)->delete();
  }
}
