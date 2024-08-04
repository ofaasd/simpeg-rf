<?php

namespace App\Http\Controllers\ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\EmployeeNew;
use App\Models\UangSaku;
use App\Models\SakuMasuk;
use App\Models\SakuKeluar;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class UangSakuController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //generate santri ke tb_uang_saku di 0 kan jumlah nya
    $id_user = Auth::user()->id;
    $user = User::find($id_user);
    $id = $user->pegawai_id;
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
      $var['uang_saku'][$row->no_induk] = UangSaku::where('no_induk', $row->no_induk)->first()->jumlah ?? 0;
    }
    $var['saku_masuk'] = SakuMasuk::whereIn('no_induk', $list_no_induk)->get();
    $var['saku_keluar'] = SakuKeluar::whereIn('no_induk', $list_no_induk)->get();

    return view('ustadz.murroby.uang_saku', compact('title', 'var'));
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
    $jenis = $request->jenis;
    if ($jenis == 'saku_masuk') {
      DB::beginTransaction();
      try {
        $jumlah = (int) str_replace('.', '', $request->jumlah);
        $sakuMasuk = SakuMasuk::create([
          'dari' => $request->dari,
          'jumlah' => $jumlah,
          'no_induk' => $request->nama_santri,
          'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
        ]);
        $saku = UangSaku::where('no_induk', $request->nama_santri)->first();

        // $updateSaku = UangSaku::update(
        //   ['no_induk' => $request->no_induk],
        //   ['jumlah' => $saku->jumlah + $request->jumlah]
        // );
        $updateSaku = UangSaku::find($saku->id);
        $updateSaku->jumlah = $saku->jumlah + $jumlah;
        $updateSaku->save();
        DB::commit();
        return response()->json('Created');
      } catch (\Exception $e) {
        DB::rollback();
        // something went wrong
        return response()->json($e);
      }
    } else {
      if (!empty($request->note)) {
        DB::beginTransaction();
        try {
          $jumlah = 0;
          foreach ($request->note as $key => $value) {
            $sakuKeluar = SakuKeluar::create([
              'pegawai_id' => Auth::user()->id,
              'jumlah' => (int) str_replace('.', '', $request->jumlah[$key]),
              'no_induk' => $request->nama_santri,
              'note' => $value,
              'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            ]);
            $jumlah += (int) str_replace('.', '', $request->jumlah[$key]);
          }
          $saku = UangSaku::where('no_induk', $request->nama_santri)->first();

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
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //Data ustadz

    $var['no_induk'] = $id;
    $id_user = Auth::user()->id;
    $user = User::find($id_user);
    $id_pegawai = $user->pegawai_id;
    $where = ['id' => $id_pegawai];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';
    $kamar = Kamar::where('employee_id', $id_pegawai)->first();

    $dari = [1 => 'Pembayaran Wali Santri', 2 => 'Kunjungan Walsan', 3 => 'Sisa Bulan Kemarin'];
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
    return view('ustadz.murroby.detail_uang_saku', compact('title', 'var'));
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
    //echo $id;
    $saku = SakuMasuk::find($id);
    $jumlah = $saku->jumlah;
    $no_induk = $saku->no_induk;
    $saku->delete();
    $uangsaku = UangSaku::where('no_induk', $no_induk)->first();
    $newjumlah = $uangsaku->jumlah - $jumlah;
    $uangsaku->update(['jumlah' => $newjumlah]);
    return Redirect::to('ustadz/uang-saku');
  }
  public function get_all(request $request)
  {
    $id = $request->pegawai_id;
    $kamar = Kamar::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    $var['uang_saku'] = [];
    foreach ($var['list_santri'] as $row) {
      $var['uang_saku'][$row->no_induk] = UangSaku::where('no_induk', $row->no_induk)->first()->jumlah;
    }
    return response()->json($var);
  }
}
