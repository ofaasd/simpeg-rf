<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeNew;
use App\Models\StructuralPosition;
use App\Models\Grades;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use App\Models\Santri;
use App\Models\User;
use App\Models\UangSaku;
use App\Models\SakuMasuk;
use App\Models\SakuKeluar;

class MurrobyController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'nama', 'jenis_kelamin', 'jabatan', 'alamat', 'pendidikan'];
  public function index(Request $request)
  {
    //
    $array_bulan = [
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

    if (empty($request->input('length'))) {
      $title = 'Murroby';
      $indexed = $this->indexed;
      $var['structural'] = StructuralPosition::all();
      $var['Grades'] = Grades::all();
      $var['list_bulan'] = $array_bulan;
      return view('admin.murroby.index', compact('title', 'indexed', 'var'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'nama',
        3 => 'jenis_kelamin',
        4 => 'jabatan',
        5 => 'alamat',
        6 => 'pendidikan',
      ];

      $search = [];

      $totalData = EmployeeNew::where('jabatan_new', 12)->count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $EmployeeNew = EmployeeNew::offset($start)
          ->where('jabatan_new', 12)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $EmployeeNew = EmployeeNew::where('jabatan_new', 12)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('jenis_kelamin', 'LIKE', "%{$search}%");
          })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = EmployeeNew::where('jabatan_new', 12)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('jenis_kelamin', 'LIKE', "%{$search}%");
          })
          ->count();
      }

      $data = [];

      if (!empty($EmployeeNew)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($EmployeeNew as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['nama'] = $row->nama;
          $nestedData['jenis_kelamin'] = $row->jenis_kelamin;
          $nestedData['jabatan'] = $row->jab->name ?? '';
          $nestedData['alamat'] = $row->alamat;
          $nestedData['pendidikan'] = $row->pen->name ?? '';
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
    $array_bulan = [
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
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $var['list_bulan'] = $array_bulan;
    $kamar = Kamar::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    return view('ustadz.murroby.index', compact('title', 'var', 'id'));
  }

  public function uang_saku(string $id)
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

    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $array_bulan = [
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
    $var['list_bulan'] = $array_bulan;
    $kamar = Kamar::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    $var['uang_saku'] = [];
    $var['uang_masuk'] = [];
    $var['tanggal_masuk'] = [];
    $list_no_induk = [];
    foreach ($var['list_santri'] as $row) {
      $list_no_induk[] = $row->no_induk;
      $saku_masuk = SakuMasuk::where('no_induk', $row->no_induk)
        ->where('dari', 1)
        ->whereMonth('tanggal', date('m'))
        ->whereYear('tanggal', date('Y'))
        ->first();
      $var['uang_masuk'][$row->no_induk] = $saku_masuk->jumlah ?? 0;
      $var['tanggal_masuk'][$row->no_induk] = $saku_masuk->tanggal ?? '';
      //cek lagi
      $var['uang_saku'][$row->no_induk] = UangSaku::where('no_induk', $row->no_induk)->first()->jumlah ?? 0;
    }
    $var['saku_masuk'] = SakuMasuk::whereIn('no_induk', $list_no_induk)->get();
    $var['saku_keluar'] = SakuKeluar::whereIn('no_induk', $list_no_induk)->get();
    return view('ustadz.murroby.uang_saku', compact('title', 'var', 'id'));
  }

  public function uang_saku_detail(string $id, string $id_santri)
  {
    //Data ustadz
    $var['no_induk'] = $id_santri;
    $id_pegawai = $id;
    $id = $id_santri;
    $where = ['id' => $id_pegawai];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $kamar = Kamar::where('employee_id', $id_pegawai)->first();

    $dari = [1 => 'Uang Saku', 2 => 'Kunjungan Walsan', 3 => 'Sisa Bulan Kemarin'];
    $bulan = (int) date('m');
    $tahun = date('Y');
    $array_bulan = [
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
    $var['list_bulan'] = $array_bulan;
    $var['bulan'] = $bulan;
    $var['tahun'] = $tahun;
    $var['uang_masuk'] = [];
    $var['dari_uang_masuk'] = [];
    $var['uang_keluar'] = [];
    $var['note_uang_keluar'] = [];
    $var['tanggal'] = [];
    for ($d = 1; $d <= 31; $d++) {
      $time = mktime(12, 0, 0, $bulan, $d, $tahun);
      if (date('m', $time) == $bulan) {
        $tanggal = date('Y-m-d', $time);
        $var['tanggal'][] = $tanggal;
        $uang_masuk = SakuMasuk::where('tanggal', $tanggal)->where('no_induk', $id);
        if ($uang_masuk->count() > 0) {
          foreach ($uang_masuk->get() as $row) {
            $var['uang_masuk'][$tanggal][$row->id] = $row->jumlah;
            $var['dari_uang_masuk'][$tanggal][$row->id] = $dari[$row->dari];
          }
        }
        $uang_keluar = SakuKeluar::where('tanggal', $tanggal)->where('no_induk', $id);
        if ($uang_keluar->count() > 0) {
          foreach ($uang_keluar->get() as $row) {
            $var['uang_keluar'][$tanggal][$row->id] = $row->jumlah;
            $var['note_uang_keluar'][$tanggal][$row->id] = $row->note;
          }
        }
      }
    }
    $var['santri'] = Santri::where('no_induk', $id)->first();
    // print_r($var['santri']);
    // exit();
    $id = $id_pegawai;
    return view('ustadz.murroby.detail_uang_saku', compact('title', 'var', 'id'));
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
