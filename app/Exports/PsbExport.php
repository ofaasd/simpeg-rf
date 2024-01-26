<?php

namespace App\Exports;

use App\Models\PsbPesertaOnline;
use App\Models\PsbWaliPesertum;
use App\Models\PsbSekolahAsal;
use App\Models\PsbBerkasPendukung;
use App\Models\ProvinsiTbl;
use App\Models\KotaKabTbl;
use App\Models\KecamatanTbl;
use App\Models\KelurahanTbl;
use App\Models\PsbSeragam;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DateTime;

class PsbExport implements FromView
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function view(): View
  {
    $psb = PsbPesertaOnline::all();
    $psb_wali = [];
    $psb_berkas = [];
    $psb_asal = [];
    $psb_seragam = [];
    $tahun = [];
    foreach ($psb as $row) {
      $tanggal_lahir = $row->tanggal_lahir;
      $dob = new DateTime(date('Y-m-d', $tanggal_lahir));
      $today = new DateTime('today');
      $year = $dob->diff($today)->y;
      $month = $dob->diff($today)->m;
      $day = $dob->diff($today)->d;
      $tahun[$row->id] = $year . ',' . $month;
      $psb_wali[$row->id] = PsbWaliPesertum::where('psb_peserta_id', $row->id)->first();
      $psb_berkas[$row->id] = PsbBerkasPendukung::where('psb_peserta_id', $row->id)->first();
      $psb_asal[$row->id] = PsbSekolahAsal::where('psb_peserta_id', $row->id)->first();
      $psb_seragam[$row->id] = PsbSeragam::where('psb_peserta_id', $row->id)->first();
    }

    return view('exports.psb', [
      'psb' => PsbPesertaOnline::all(),
      'psb_wali' => $psb_wali,
      'psb_berkas' => $psb_berkas,
      'psb_asal' => $psb_asal,
      'psb_seragam' => $psb_seragam,
      'tahun' => $tahun,
    ]);
  }
}
