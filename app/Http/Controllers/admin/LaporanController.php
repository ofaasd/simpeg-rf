<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefJenisPembayaran;
use App\Models\Santri;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\Kamar;
use App\Models\EmployeeNew;

class LaporanController extends Controller
{
  //
  public function pembayaran(Request $request)
  {
    if (!empty($request->input())) {
      $periode = $request->input('periode');
      $tahun = $request->input('tahun');
      $data['jenis_pembayaran'] = RefJenisPembayaran::all();
      $data['siswa'] = Santri::all();
      if ($periode == 0) {
        $where = [
          'tahun' => $tahun,
          'is_hapus' => 0,
        ];
      } else {
        $where = [
          'periode' => $periode,
          'tahun' => $tahun,
          'is_hapus' => 0,
        ];
      }
      $pembayaran = Pembayaran::where($where)->get();
      $santri = [];
      //inisiasi
      foreach ($data['siswa'] as $siswa) {
        foreach ($data['jenis_pembayaran'] as $jenis_pembayaran) {
          $santri[$siswa->no_induk][$jenis_pembayaran->id] = 0;
        }
      }
      //assign nilai di dalamnya
      foreach ($pembayaran as $row) {
        $detailPembayaran = DetailPembayaran::where('id_pembayaran', $row->id)->get();
        foreach ($detailPembayaran as $detail) {
          $santri[$row->nama_santri][$detail->id_jenis_pembayaran] += $detail->nominal;
        }
      }
      // var_dump($santri[5]);
      // exit;

      $kamar = Kamar::all();
      $data['nama_murroby'] = [];
      foreach ($kamar as $row) {
        // $data['nama_murroby'][$row->id] = $this->db
        //   ->get_where('employee_new', ['id' => $row->employee_id])
        //   ->row()->nama;
        $data['nama_murroby'][$row->id] = EmployeeNew::find($row->employee_id)->nama;
      }

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
      $data['bulan'] = $bulan;
      $data['santri'] = $santri;
      $data['periode'] = $periode;
      $data['tahun'] = $tahun;
      //$data['title'] = 'Syahriyah ' . $bulan[$periode] . ' ' . $tahun;
      $title = 'Laporan Syahriah';
      return view('admin.laporan.pembayaran', compact('title', 'data'));
    } else {
      $data = '';
      $title = 'Laporan Syahriah';
      return view('admin.laporan.pembayaran', compact('data', 'title'));
    }
  }
}
