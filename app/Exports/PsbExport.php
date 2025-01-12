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
use App\Models\UserPsb;
use App\Models\PsbGelombang;
use App\Models\PsbBuktiPembayaran;
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
    $gelombang = PsbGelombang::orderBy('id','desc')->limit(1)->first();
    $psb = PsbPesertaOnline::where('gelombang_id',$gelombang->id)->orderBy('id','asc')->get();
    $psb_wali = [];
    $psb_berkas = [];
    $psb_asal = [];
    $psb_seragam = [];
    $tahun = [];
    $psb_user = [];
    $bukti_bayar = [];
    $tanggal_bayar = [];
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
      $psb_user[$row->id] = UserPsb::where('no_pendaftaran', $row->no_pendaftaran)->first();
      $bukti_bayar[$row->id] = 0;
      $bukti = PsbBuktiPembayaran::where('psb_peserta_id', $row->id);
      $tanggal_bayar[$row->id] = '';
      if ($bukti->count() > 0) {
        $bukti_row = $bukti->first();
        $bukti_bayar[$row->id] = $bukti_row->status;
        $tanggal_bayar[$row->id] = (!empty($bukti_row->created_at)) ? date('d-m-Y', strtotime($bukti_row->created_at)) : "";
      }
    }
    $status_bayar = ['Belum Bayar','Belum di Validasi','Pembayaran Tervalidasi'];

    return view('exports.psb', [
      'psb' => $psb,
      'psb_wali' => $psb_wali,
      'psb_berkas' => $psb_berkas,
      'psb_asal' => $psb_asal,
      'psb_seragam' => $psb_seragam,
      'psb_user' => $psb_user,
      'tahun' => $tahun,
      'bukti_bayar' => $bukti_bayar,
      'tanggal_bayar' => $tanggal_bayar,
      'status_bayar' => $status_bayar,
    ]);
  }
}
