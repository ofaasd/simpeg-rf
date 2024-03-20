<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\RefJenisPembayaran;
use App\Models\Kamar;
use App\Models\EmployeeNew;
use App\Models\Santri;
use App\Models\Kelas;

class PembayaranExport implements FromCollection
{
  /**
   * @return \Illuminate\Support\Collection
   */

  public function view($tahun, $periode, $kelas): View
  {
    $periode = $this->periode;
    $tahun = $this->tahun;
    $kelas = $this->kelas;
    $where = [
      'periode' => $this->periode,
      'tahun' => $this->tahun,
      'kelas' => $this->kelas,
      'is_hapus' => 0,
    ];
    $pembayaran = Pembayaran::select(
      'tb_pembayaran.*',
      'santri_detail.nama',
      'santri_detail.no_induk',
      'santri_detail.kelas',
      'santri_detail.kamar_id'
    )
      ->where($where)
      ->join('santri_detail', 'santri_detail.no_induk', '=', 'tb_pembayaran.nama_santri')
      ->orderBy('no_induk')
      ->get();
    $data['jenis_pembayaran'] = RefJenisPembayaran::all();
    $data['detail'] = [];
    $id_sudah = [];
    foreach ($pembayaran as $pem) {
      $id_sudah[] = $pem->nama_santri;
      foreach ($data['jenis_pembayaran'] as $jenis) {
        $where = [
          'id_pembayaran' => $pem->id,
          'id_jenis_pembayaran' => $jenis->id,
        ];
        $detail = DetailPembayaran::where($where);
        if ($detail->count() > 0) {
          $data['detail'][$pem->id][$jenis->id] = $detail->first()->nominal;
        } else {
          $data['detail'][$pem->id][$jenis->id] = 0;
        }
      }
    }
    $data['sisa_santri'] = Santri::where('kelas', $this->kelas)
      ->whereNotIn('no_induk', $id_sudah)
      ->orderBy('no_induk')
      ->get();
    $kamar = Kamar::all();
    $data['nama_murroby'] = [];
    $data['bulan'] = $this->bulan;
    $data['periode'] = $periode;
    $data['tahun'] = $tahun;
    foreach ($kamar as $row) {
      // $data['nama_murroby'][$row->id] = $this->db
      //   ->get_where('employee_new', ['id' => $row->employee_id])
      //   ->row()->nama;
      $data['nama_murroby'][$row->id] = EmployeeNew::find($row->employee_id)->nama;
    }
    return view('admin.pembayaran.export', compact('periode', 'tahun', 'kelas', 'data', 'pembayaran'));
  }
}
