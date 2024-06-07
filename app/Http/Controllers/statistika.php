<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

use App\Models\City;
use App\Models\PsbGelombang;
use App\Models\PsbPesertaOnline;
use App\Models\Province;
use App\Models\Kamar;
//siswa
use App\Models\Santri;
//pegawai
use App\Models\EmployeeNew;
//pembayaran
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\SantriKamar;
use Exception;
use Illuminate\Http\Request;

class statistika extends Controller
{
  public function index()
  {
    $jumlah_psb_baru = 0;
    $jumlah_pembayaran_lalu = 0;
    $jumlah_pembayaran = 0;
    $tot_bayar = 0;

    $list_bulan = [
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

    $bulan = (int) date('m');
    $tahun = (int) date('Y');
    $gelombang = PsbGelombang::where('pmb_online', 1)->first();
    $psb = PsbPesertaOnline::where('gelombang_id', $gelombang->id);

    $psb2 = PsbPesertaOnline::where('gelombang_id', $gelombang->id)->whereRaw(
      'MONTH(FROM_UNIXTIME(created_at)) = ' . $bulan
    );

    if ($psb->count() > 0) {
      $jumlah_psb = $psb->count();
      $jumlah_psb_laki = $psb->where('jenis_kelamin', 'L')->count();
      $jumlah_psb_perempuan = PsbPesertaOnline::where('gelombang_id', $gelombang->id)
        ->where('jenis_kelamin', 'P')
        ->count();
    }

    if ($psb2->count() > 0) {
      $jumlah_psb_baru = $psb2->count();
    }

    $kelas = Santri::select('kelas')
      ->groupBy('kelas')
      ->orderBy('kelas')
      ->get();

    $santri = Santri::where('status', 0);
    if ($santri->count() > 0) {
      $jumlah_siswa = $santri->count();
      $jumlah_siswa_l = $santri->where('jenis_kelamin', 'L')->count();
      $jumlah_siswa_p = Santri::where('status', 0)
        ->where('jenis_kelamin', 'P')
        ->count();
    }

    $pegawai = EmployeeNew::count();
    if ($pegawai > 0) {
      $jumlah_pegawai = $pegawai;
      $jumlah_pegawai_l = EmployeeNew::where('jenis_kelamin', 'Laki-laki')->count();
      $jumlah_pegawai_p = EmployeeNew::where('jenis_kelamin', 'Perempuan')->count();
    }

    $bayar = Pembayaran::whereMonth('tanggal_validasi', $bulan)
      ->whereYear('tanggal_validasi', $tahun)
      ->sum('jumlah');
    if ($bayar > 0) {
      $jumlah_pembayaran = $bayar;
    }

    $bayar = Pembayaran::whereMonth('tanggal_bayar', $bulan)
      ->whereYear('tanggal_bayar', $tahun)
      ->sum('jumlah');
    if ($bayar > 0) {
      $tot_bayar = $bayar;
    }

    $jumlah_santri_lapor = Pembayaran::whereMonth('tanggal_bayar', $bulan)
      ->whereYear('tanggal_bayar', $tahun)
      ->distinct('nama_santri');
    $jumlah_siswa_belum_lapor = $jumlah_siswa - $jumlah_santri_lapor->count();

    if ($bulan == 1) {
      $bulan = 13;
      $tahun = $tahun - 1;
    }
    $bulan_lalu = $bulan - 1;
    $bayar_lalu = Pembayaran::whereMonth('tanggal_validasi', $bulan_lalu)
      ->whereYear('tanggal_validasi', $tahun)
      ->sum('jumlah');
    if ($bayar_lalu > 0) {
      $jumlah_pembayaran_lalu = $bayar_lalu;
    }

    $provinsis = Province::all();

    // Mengambil semua nilai dari kolom 'jabatan_new' dalam tabel EmployeeNew
    $idJabatan = EmployeeNew::pluck('jabatan_new');

    // Menyimpan jumlah karyawan per jabatan
    $jumlah = [];

    // Menghitung jumlah karyawan per jabatan
    foreach ($idJabatan as $key) {
      if (!isset($jumlah[$key])) {
        $jumlah[$key] = 0;
      }
      $jumlah[$key]++;
    }

    // Menyiapkan hasil dalam format yang diinginkan
    $hasil = [];
    foreach ($jumlah as $key => $count) {
      $employee = EmployeeNew::where('jabatan_new', $key)->first();

      if ($employee) {
        $hasil[] = [
          'jabatan' => $employee->jabatan,
          'jumlah' => $count,
        ];
      }
    }

    return view(
      'content.pages.pages-statistika',
      compact(
        'kelas',
        'list_bulan',
        'jumlah_psb_baru',
        'jumlah_psb_laki',
        'jumlah_psb_perempuan',
        'jumlah_psb',
        'jumlah_siswa',
        'jumlah_siswa_l',
        'jumlah_siswa_p',
        'jumlah_pegawai',
        'jumlah_pegawai_l',
        'jumlah_pegawai_p',
        'jumlah_pembayaran',
        'jumlah_pembayaran_lalu',
        'jumlah_siswa_belum_lapor',
        'tot_bayar',
        'provinsis',
        'hasil',
        'tahun'
      )
    );
  }

  public function getJumlahPsb(Request $request)
  {
    $tahun = $request->tahun;
    $bulan = [
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
    $jumlah = [];
    foreach ($bulan as $key => $value) {
      $psb2 = PsbPesertaOnline::whereRaw('MONTH(FROM_UNIXTIME(created_at)) = ' . $key)->whereRaw(
        'YEAR(FROM_UNIXTIME(created_at)) = ' . $tahun
      );
      $jumlah[$key] = $psb2->count();
    }
    $hasil[0] = array_values($bulan);
    $hasil[1] = array_values($jumlah);
    return response()->json($hasil);
  }

  public function get_kota_santri()
  {
    // Mengambil semua nilai dari kolom 'kabkota' dalam tabel Santri
    $kotaSantri = Santri::pluck('kabkota');

    // Menyimpan jumlah santri per kota
    $jumlah = [];

    // Menghitung jumlah santri per kota
    foreach ($kotaSantri as $key) {
      if (!isset($jumlah[$key])) {
        $jumlah[$key] = 0;
      }
      $jumlah[$key]++;
    }

    // Menyiapkan hasil dalam format yang diinginkan
    $hasil = [];
    foreach ($jumlah as $key => $count) {
      $kota = City::where('city_id', $key)->first();
      if ($kota) {
        $hasil[] = [
          'Kota' => $kota->city_name,
          'Jumlah' => $count,
        ];
      }
    }

    // Mengembalikan hasil dalam format JSON
    return response()->json($hasil);
  }
  public function get_isi_kamar_santri()
  {
    // Mengambil semua nilai dari kolom 'kabkota' dalam tabel Santri
    $idKamarSantri = SantriKamar::pluck('kamar_id');

    // Menyimpan jumlah santri per kota
    $jumlah = [];

    // Menghitung jumlah santri per kota
    foreach ($idKamarSantri as $key) {
      if (!isset($jumlah[$key])) {
        $jumlah[$key] = 0;
      }
      $jumlah[$key]++;
    }

    // Menyiapkan hasil dalam format yang diinginkan
    $hasil = [];
    foreach ($jumlah as $key => $count) {
      $kamar = Kamar::where('id', $key)->first();
      if ($kamar) {
        $hasil[] = [
          'Kamar' => $kamar->name,
          'Jumlah' => $count,
        ];
      }
    }

    // Mengembalikan hasil dalam format JSON
    return response()->json($hasil);
  }

  public function getTarget(Request $request)
  {
    $total_santri = 0;
    $sudah_bayar = 0;

    $bulan = $request->bulan;
    $tahun = $request->tahun;
    $kelas = $request->kelas;

    $siswa = Santri::where('kelas', $kelas)->get();
    foreach ($siswa as $row) {
      $total_santri++;
      $pembayaran = Pembayaran::where(['periode' => $bulan, 'tahun' => $tahun, 'nama_santri' => $row->no_induk]);
      if ($pembayaran->count() > 0) {
        foreach ($pembayaran->get() as $pem) {
          $detail = DetailPembayaran::where('id_pembayaran', $pem->id)
            ->where('id_jenis_pembayaran', 1)
            ->count();
          if ($detail > 0) {
            $sudah_bayar++;
          }
        }
      }
    }
    $belum_bayar = $total_santri - $sudah_bayar;
    $hasil[0] = ['Belum Lapor', 'Sudah Lapor'];
    $hasil[1] = [$belum_bayar, $sudah_bayar];
    return response()->json($hasil);
  }

  public function getPembayaranBulanINi()
  {
    $today = Carbon::now();

    $currentMonth = $today->month;
    $currentYear = $today->year;

    try {
      $pembayaranBulanini = Pembayaran::selectRaw('DATE(tanggal_bayar) as tanggal, COUNT(*) as jumlah_transaksi')
        ->whereMonth('tanggal_bayar', '=', $currentMonth)
        ->whereYear('tanggal_bayar', '=', $currentYear)
        ->groupByRaw('DATE(tanggal_bayar)')
        ->get()
        ->map(function ($item) {
          $tanggal = Carbon::parse($item->tanggal)->format('j');
          $bulan = Carbon::parse($item->tanggal)->format('n');

          $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
          ];

          return [
            'tanggal' => $tanggal,
            'nama_bulan' => $namaBulan[$bulan],
            'jumlah_transaksi' => $item->jumlah_transaksi,
          ];
        });

      if ($pembayaranBulanini->isEmpty()) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
      }
      return response()->json($pembayaranBulanini);
    } catch (Exception $e) {
      return response()->json([
        'message' => $e,
      ]);
    }
  }

  public function getStatistikPembayaran(Request $request)
  {
    $tahun = $request->tahun;
    $bulan = [
      1 => 'Januari',
      2 => 'Februari',
      3 => 'Maret',
      4 => 'April',
      5 => 'Mei',
      6 => 'Juni',
      7 => 'Juli',
      8 => 'Agustus',
      9 => 'September',
      10 => 'Oktober',
      11 => 'November',
      12 => 'Desember',
    ];
    $jumlah = [];
    $jumlah = [];
    foreach ($bulan as $key => $value) {
      dd($key + $value);
      // Menggunakan fungsi Laravel untuk mengekstrak bulan dan tahun dari kolom tanggal_bayar
      $pembayaran = Pembayaran::whereMonth('tanggal_bayar', '=', $key)->whereYear('tanggal_bayar', '=', $tahun);
      $jumlah[$key] = $pembayaran->count();
    }
    $hasil[0] = array_values($bulan);
    $hasil[1] = array_values($jumlah);
    return response()->json($hasil);
  }
}
