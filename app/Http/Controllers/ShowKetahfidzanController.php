<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailSantriTahfidz;
use Illuminate\Support\Facades\DB;

class ShowKetahfidzanController extends Controller
{
    public function index(Request $request)
  {
    $title = 'Laporan Pondok';

    if (!empty($request->input())) {
      $tahun = $request->input('pilih-tahun');
      $query = DetailSantriTahfidz::select(
        "santri_detail.nama AS nmSantri",
        "employee_new.nama AS nmMurroby",
        "santri_detail.kelas AS klsSantri",
        \DB::raw("MONTH(detail_santri_tahfidz.tanggal) as bulan"),
        \DB::raw("MAX(detail_santri_tahfidz.kode_juz_surah) as maxJuzSurah"),
        \DB::raw("(SELECT kode_juz.nama 
                   FROM kode_juz 
                   WHERE kode_juz.kode = MAX(detail_santri_tahfidz.kode_juz_surah) 
                   LIMIT 1) AS nmJuz") // Mengambil nama juz berdasarkan MAX kode_juz_surah
        )
        ->leftJoin("ref_tahfidz", "ref_tahfidz.id", "=", "detail_santri_tahfidz.id_tahfidz")
        ->leftJoin("employee_new", "employee_new.id", "=", "ref_tahfidz.employee_id")
        ->leftJoin("santri_detail", "santri_detail.no_induk", "=", "detail_santri_tahfidz.no_induk")
        ->whereYear("detail_santri_tahfidz.tanggal", $tahun)
        ->groupBy("santri_detail.nama", "klsSantri", "nmMurroby", \DB::raw("MONTH(detail_santri_tahfidz.tanggal)"))
        ->orderBy("bulan")
        ->get();

        // Siapkan data pivot dalam format array
        $data = [];

        foreach ($query as $item) {
            $nama = $item->nmSantri;

            if (!isset($data[$nama])) {
                $data[$nama] = [
                    'nmSantri' => $nama,
                    'nmMurroby' => $item->nmMurroby,
                    'klsSantri' => $item->klsSantri,
                    'bulan' => array_fill(1, 12, '-') // Inisialisasi semua bulan dengan "-"
                ];
            }

            $data[$nama]['bulan'][$item->bulan] = $item->nmJuz;
            }

        $tahunPembayaran = DetailSantriTahfidz::select(DB::raw('YEAR(detail_santri_tahfidz.tanggal) as year'))
            ->whereNotNull('detail_santri_tahfidz.tanggal')
            ->groupBy(DB::raw('YEAR(detail_santri_tahfidz.tanggal)'))
            ->orderByDesc('year')
            ->get()
            ->pluck('year');

        $tahuns = $tahunPembayaran
            ->mapWithKeys(function ($year) {
            return [$year => $year];
            })
            ->toArray();
            
        $var['tahun'] = $tahuns;

        return view('admin.show-ketahfidzan.index', compact('data', 'tahun', 'var', 'title'));
    } else {
        $query = DetailSantriTahfidz::select(
            "santri_detail.nama AS nmSantri",
            "santri_detail.kelas AS klsSantri",
            "employee_new.nama AS nmMurroby",
            \DB::raw("MONTH(detail_santri_tahfidz.tanggal) as bulan"),
            \DB::raw("MAX(detail_santri_tahfidz.kode_juz_surah) as maxJuzSurah"),
            \DB::raw("(SELECT kode_juz.nama 
                       FROM kode_juz 
                       WHERE kode_juz.kode = MAX(detail_santri_tahfidz.kode_juz_surah) 
                       LIMIT 1) AS nmJuz") // Mengambil nama juz berdasarkan MAX kode_juz_surah
            )
            ->leftJoin("ref_tahfidz", "ref_tahfidz.id", "=", "detail_santri_tahfidz.id_tahfidz")
            ->leftJoin("employee_new", "employee_new.id", "=", "ref_tahfidz.employee_id")
            ->leftJoin("santri_detail", "santri_detail.no_induk", "=", "detail_santri_tahfidz.no_induk")
            ->whereYear("detail_santri_tahfidz.tanggal", now()->year)
            ->groupBy("santri_detail.nama", "klsSantri", "nmMurroby", \DB::raw("MONTH(detail_santri_tahfidz.tanggal)"))
            ->orderBy("bulan")
            ->get();

        // Siapkan data pivot dalam format array
        $data = [];

        foreach ($query as $item) {
            $nama = $item->nmSantri;

            if (!isset($data[$nama])) {
                $data[$nama] = [
                    'nmSantri' => $nama,
                    'nmMurroby' => $item->nmMurroby,
                    'klsSantri' => $item->klsSantri,
                    'bulan' => array_fill(1, 12, '-') // Inisialisasi semua bulan dengan "-"
                ];
            }

            $data[$nama]['bulan'][$item->bulan] = $item->nmJuz;
            }

        $tahunPembayaran = DetailSantriTahfidz::select(DB::raw('YEAR(detail_santri_tahfidz.tanggal) as year'))
            ->whereNotNull('detail_santri_tahfidz.tanggal')
            ->groupBy(DB::raw('YEAR(detail_santri_tahfidz.tanggal)'))
            ->orderByDesc('year')
            ->get()
            ->pluck('year');

        $tahun = $tahunPembayaran
            ->mapWithKeys(function ($year) {
            return [$year => $year];
            })
            ->toArray();
            
        $var['tahun'] = $tahun;

        $tahun  = now()->year;
            
      return view('admin.show-ketahfidzan.index', compact('data', 'tahun', 'var', 'title'));
    }
  }
}
