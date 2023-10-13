<?php

namespace App\Http\Controllers\ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeNew;
use App\Models\TahunAjaran;
use App\Models\Santri;
use App\Models\SakuMasuk;
use App\Models\User;
use App\Models\Kamar;

class SakuMasukController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'santri', 'dari', 'jumlah', 'tanggal'];
  public $code = ['a', 'b'];
  public function index(Request $request)
  {
    if (empty($request->input('length'))) {
      $id_user = Auth::user()->id;
      $user = User::find($id_user);
      $id = $user->pegawai_id;
      $where = ['id' => $id];
      $var['EmployeeNew'] = EmployeeNew::where($where)->first();
      $title = 'Pegawai';
      $kamar = Kamar::where('employee_id', $id)->first();
      $page = 'SakuMasuk';
      $title = 'Saku Masuk';
      $indexed = $this->indexed;
      $code = $this->code;

      return view('ustadz.saku_masuk.index', compact('title', 'indexed', 'var', 'page'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'santri',
        3 => 'dari',
        4 => 'jumlah',
        5 => 'tanggal',
      ];

      $search = [];

      $totalData = SakuMasuk::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $saku = SakuMasuk::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $saku = SakuMasuk::where(function ($query) use ($search) {
          $query->whereRelation('santri', 'nama', 'like', "%{$search}%");
        })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = SakuMasuk::where(function ($query) use ($search) {
          $query->whereRelation('santri', 'nama', 'like', "%{$search}%");
        })->count();
      }

      $data = [];

      if (!empty($saku)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($saku as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['dari'] = $row->dari;
          $nestedData['jumlah'] = $row->jumlah;
          $nestedData['tanggal'] = $row->tanggal;
          $nestedData['santri'] = $row->santri->nama;
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
