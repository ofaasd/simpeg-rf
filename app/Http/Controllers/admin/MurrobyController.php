<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Kamar;
use App\Models\Grades;
use App\Models\Santri;
use App\Models\Perilaku;
use App\Models\UangSaku;
use App\Models\SakuMasuk;
use App\Models\SakuKeluar;
use App\Models\EmployeeNew;
use Illuminate\Http\Request;
use App\Models\StructuralPosition;
use App\Http\Controllers\Controller;
use App\Models\Kelengkapan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MurrobyController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  protected $labelPerilaku = ['Kurang Baik', 'Cukup', 'Baik'];
  protected $labelKelengkapan = ['Tidak Lengkap', 'Lengkap & Kurang baik', 'lengkap & baik'];


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
  public function kosongkan_uang_saku(Request $request)
  {
    $id = $request->pegawai_id;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $kamar = Kamar::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    foreach ($var['list_santri'] as $row) {
      $saku_keluar = new SakuKeluar();
      $saku_keluar->no_induk = $row->no_induk;
      $saku_keluar->pegawai_id = $id;
      $saku_keluar->tanggal = date('Y-m-d');
      $saku_keluar->jumlah = UangSaku::where('no_induk', $row->no_induk)->first()->jumlah ?? 0;
      $saku_keluar->note = 'Pengosongan Uang Saku Bulan ' . date('m-Y');
      $saku_keluar->save();
      UangSaku::where('no_induk', $row->no_induk)->update(['jumlah' => 0]);
    }
    
    return json_encode(['status' => 'success', 'message' => 'Berhasil mengosongkan uang saku semua santri.']);
  }
  public function reset_saku_masuk(Request $request)
  {
    $id = $request->pegawai_id;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $kamar = Kamar::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    foreach ($var['list_santri'] as $row) {
      SakuMasuk::where('no_induk', $row->no_induk)
        ->where('dari', 1)
        ->whereMonth('tanggal', date('m'))
        ->whereYear('tanggal', date('Y'))
        ->delete();
    }
    
    return json_encode(['status' => 'success', 'message' => 'Berhasil mereset saku masuk semua santri.']);
  } 

  public function reset_saku_keluar(Request $request)
  {
    $id = $request->pegawai_id;
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $kamar = Kamar::where('employee_id', $id)->first();

    $var['list_santri'] = Santri::where('kamar_id', $kamar->id)->get();
    foreach ($var['list_santri'] as $row) {
      SakuKeluar::where('no_induk', $row->no_induk)
        ->whereMonth('tanggal', date('m'))
        ->whereYear('tanggal', date('Y'))
        ->delete();
    }

    return json_encode(['status' => 'success', 'message' => 'Berhasil mereset saku keluar semua santri.']);
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

  public function indexPerilaku(string $id)
  {
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';

    $kamar = Kamar::where('employee_id', $id)->first();
    $var['listSantri'] = Santri::where('kamar_id', $kamar->id)
      ->orderBy('nama', 'asc')
      ->get();

    $labelPerilaku = $this->labelPerilaku;
    $perilaku = Perilaku::whereIn('no_induk', $var['listSantri']->pluck('no_induk'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('no_induk')
        ->map(function ($group) use ($labelPerilaku) {
            $item = $group->first(); // Ambil data terbaru

            // Konversi angka ke labelPerilaku
            $item->tanggal = Carbon::parse($item->tanggal)->format('Y-m-d') ?? '-';

            $item->ketertiban = $labelPerilaku[$item->ketertiban] ?? '-';
            $item->kebersihan = $labelPerilaku[$item->kebersihan] ?? '-';
            $item->kedisiplinan = $labelPerilaku[$item->kedisiplinan] ?? '-';
            $item->kerapian = $labelPerilaku[$item->kerapian] ?? '-';
            $item->kesopanan = $labelPerilaku[$item->kesopanan] ?? '-';
            $item->kepekaan_lingkungan = $labelPerilaku[$item->kepekaan_lingkungan] ?? '-';
            $item->ketaatan_peraturan = $labelPerilaku[$item->ketaatan_peraturan] ?? '-';

            return $item;
        });

    $var['perilaku'] = $perilaku;

    return view('ustadz.murroby.index-perilaku', compact('title', 'var', 'id'));
  }

  public function storePerilaku(Request $request)
  {
    $data = Perilaku::create([
      'no_induk' => $request->noInduk,
      'tanggal' => $request->tanggal,
      'ketertiban' => $request->ketertiban,
      'kebersihan' => $request->kebersihan,
      'kedisiplinan' => $request->kedisiplinan,
      'kerapian' => $request->kerapian,
      'kesopanan' => $request->kesopanan,
      'kepekaan_lingkungan' => $request->kepekaanLingkungan,
      'ketaatan_peraturan' => $request->ketaatanPeraturan,
    ]);

    if ($data) {
      return response()->json('Success Create', 201);
    } else {
      return response()->json('Failed Create', 500);
    }
  }

  public function showPerilaku(string $noInduk)
  {
    $var['dataSantri'] = Santri::where('no_induk', $noInduk)->first();

    $kamar = Kamar::where('id', $var['dataSantri']->kamar_id)->first();
    $var['EmployeeNew'] = EmployeeNew::where('id', $kamar->employee_id)->first();

    $title = 'Detail Perilaku' . $var['dataSantri']->nama;

    $labelPerilaku = $this->labelPerilaku;
    $var['perilaku'] = Perilaku::where('no_induk', $noInduk)
      ->orderBy('created_at', 'desc') // jika kamu ingin data terbaru di urutan atas
      ->get()
      ->map(function ($item) use ($labelPerilaku) {
          $item->tanggal = $item->tanggal ? Carbon::parse($item->tanggal)->format('Y-m-d') : '-';

          $item->ketertiban = $labelPerilaku[$item->ketertiban] ?? '-';
          $item->kebersihan = $labelPerilaku[$item->kebersihan] ?? '-';
          $item->kedisiplinan = $labelPerilaku[$item->kedisiplinan] ?? '-';
          $item->kerapian = $labelPerilaku[$item->kerapian] ?? '-';
          $item->kesopanan = $labelPerilaku[$item->kesopanan] ?? '-';
          $item->kepekaan_lingkungan = $labelPerilaku[$item->kepekaan_lingkungan] ?? '-';
          $item->ketaatan_peraturan = $labelPerilaku[$item->ketaatan_peraturan] ?? '-';

          return $item;
      });

    $id = $var['EmployeeNew']->id;

    return view('ustadz.murroby.show-perilaku', compact('title', 'var', 'id'));
  }

  public function editPerilaku(int $id)
  {
    $where = ['id' => $id];

    $data = Perilaku::where($where)->first();

    return response()->json($data);
  }

  public function updatePerilaku(Request $request, $id)
  {
    $data = Perilaku::where('id', $id)->first();
    $save = $data->update([
      'no_induk' => $request->noInduk,
      'tanggal' => $request->tanggal,
      'ketertiban' => $request->ketertiban,
      'kebersihan' => $request->kebersihan,
      'kedisiplinan' => $request->kedisiplinan,
      'kerapian' => $request->kerapian,
      'kesopanan' => $request->kesopanan,
      'kepekaan_lingkungan' => $request->kepekaanLingkungan,
      'ketaatan_peraturan' => $request->ketaatanPeraturan,
    ]);

    if ($save) {
      return response()->json('Success Update', 200);
    } else {
      return response()->json('Failed Update', 500);
    }
  }

  public function deletePerilaku(int $id)
  {
    $data = Perilaku::where('id', $id)->first();
    $data->delete();
  }

  public function indexKelengkapan(string $id)
  {
    $where = ['id' => $id];
    $var['EmployeeNew'] = EmployeeNew::where($where)->first();
    $title = 'Pegawai';

    $kamar = Kamar::where('employee_id', $id)->first();
    $var['listSantri'] = Santri::where('kamar_id', $kamar->id)
      ->orderBy('nama', 'asc')
      ->get();

    $labelKelengkapan = $this->labelKelengkapan;
    $kelengkapan = Kelengkapan::whereIn('no_induk', $var['listSantri']->pluck('no_induk'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('no_induk')
        ->map(function ($group) use ($labelKelengkapan) {
            $item = $group->first(); // Ambil data terbaru

            // Konversi angka ke labelKelengkapan
            $item->tanggal = Carbon::parse($item->tanggal)->format('Y-m-d') ?? '-';

            $item->perlengkapan_mandi = $labelKelengkapan[$item->perlengkapan_mandi] ?? '-';
            $item->peralatan_sekolah = $labelKelengkapan[$item->peralatan_sekolah] ?? '-';
            $item->perlengkapan_diri = $labelKelengkapan[$item->perlengkapan_diri] ?? '-';

            return $item;
        });

    $var['kelengkapan'] = $kelengkapan;

    return view('ustadz.murroby.index-kelengkapan', compact('title', 'var', 'id'));
  }

  public function storeKelengkapan(Request $request)
  {
    $data = Kelengkapan::create([
      'no_induk' => $request->noInduk,
      'tanggal' => $request->tanggal,
      'perlengkapan_mandi' => $request->perlengkapanMandi,
      'catatan_mandi' => $request->catatanMandi,
      'peralatan_sekolah' => $request->peralatanSekolah,
      'catatan_sekolah' => $request->catatanSekolah,
      'perlengkapan_diri' => $request->perlengkapanDiri,
      'catatan_diri' => $request->catatanDiri,
    ]);

    if ($data) {
      return response()->json('Success Create', 201);
    } else {
      return response()->json('Failed Create', 500);
    }
  }

  public function showKelengkapan(string $noInduk)
  {
    $var['dataSantri'] = Santri::where('no_induk', $noInduk)->first();

    $kamar = Kamar::where('id', $var['dataSantri']->kamar_id)->first();
    $var['EmployeeNew'] = EmployeeNew::where('id', $kamar->employee_id)->first();

    $title = 'Detail Kelengkapan' . $var['dataSantri']->nama;

    $labelKelengkapan = $this->labelKelengkapan;
    $var['kelengkapan'] = Kelengkapan::where('no_induk', $noInduk)
      ->orderBy('created_at', 'desc') // jika kamu ingin data terbaru di urutan atas
      ->get()
      ->map(function ($item) use ($labelKelengkapan) {
          $item->tanggal = $item->tanggal ? Carbon::parse($item->tanggal)->format('Y-m-d') : '-';

          $item->perlengkapan_mandi = $labelKelengkapan[$item->perlengkapan_mandi] ?? '-';
          $item->peralatan_sekolah = $labelKelengkapan[$item->peralatan_sekolah] ?? '-';
          $item->perlengkapan_diri = $labelKelengkapan[$item->perlengkapan_diri] ?? '-';

          return $item;
      });

    $id = $var['EmployeeNew']->id;

    return view('ustadz.murroby.show-kelengkapan', compact('title', 'var', 'id'));
  }

  public function editKelengkapan(int $id)
  {
    $where = ['id' => $id];

    $data = Kelengkapan::where($where)->first();

    return response()->json($data);
  }

  public function updateKelengkapan(Request $request, $id)
  {
    $data = Kelengkapan::where('id', $id)->first();
    $save = $data->update([
      'no_induk' => $request->noInduk,
      'tanggal' => $request->tanggal,
      'perlengkapan_mandi' => $request->perlengkapanMandi,
      'catatan_mandi' => $request->catatanMandi,
      'peralatan_sekolah' => $request->peralatanSekolah,
      'catatan_sekolah' => $request->catatanSekolah,
      'perlengkapan_diri' => $request->perlengkapanDiri,
      'catatan_diri' => $request->catatanDiri,
    ]);

    if ($save) {
      return response()->json('Success Update', 200);
    } else {
      return response()->json('Failed Update', 500);
    }
  }

  public function deleteKelengkapan(int $id)
  {
    $data = Kelengkapan::where('id', $id)->first();
    $data->delete();
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
