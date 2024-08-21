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
use PhpParser\Node\Expr\Isset_;

class LaporanController extends Controller
{
  //
  public function pembayaran(Request $request)
  {
    $bulan = [
      0 => '',
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
    if (!empty($request->input())) {
      $periode = $request->input('periode');
      $tahun = $request->input('tahun');
      $data['jenis_pembayaran'] = RefJenisPembayaran::all();

      $kelas = $request->input('kelas');
      $status = $request->input('status');
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
      if($kelas != 0){
        $where['kelas'] = $kelas;
        $data['siswa'] = Santri::where('kelas',$kelas)->get();
      }else{
        $data['siswa'] = Santri::all();
      }
      if($status != 0){
        if($status == 1){
          $where['validasi'] = 1;
        }
      }

      $pembayaran = Pembayaran::select('tb_pembayaran.*')
        ->join('santri_detail','santri_detail.no_induk','=','tb_pembayaran.nama_santri')
        ->where($where)
        ->get();
      $santri = [];
      //inisiasi
      foreach ($data['siswa'] as $siswa) {
        foreach ($data['jenis_pembayaran'] as $jenis_pembayaran) {
          $santri[$siswa->no_induk][$jenis_pembayaran->id] = 0;
        }
      }
      $id_sudah = [];
      if($pembayaran){
        //assign nilai di dalamnya
        foreach ($pembayaran as $row) {
          $id_sudah[] = $row->nama_santri;
          $detailPembayaran = DetailPembayaran::where('id_pembayaran', $row->id)->get();
          foreach ($detailPembayaran as $detail) {
            if(!empty($detail->id_jenis_pembayaran) && $detail->id_jenis_pembayaran != 0 && $row->nama_santri != 0){
              $santri[$row->nama_santri][$detail->id_jenis_pembayaran] += $detail->nominal;
            }
          }
        }
      }
      $data['santri_valid'] = Santri::where('kelas', $kelas)
      ->whereIn('no_induk', $id_sudah)
      ->orderBy('no_induk')
      ->get();

      $data['sisa_santri'] = Santri::where('kelas', $kelas)
      ->whereNotIn('no_induk', $id_sudah)
      ->orderBy('no_induk')
      ->get();
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


      $var['bulan'] = $bulan;
      $data['santri'] = $santri;
      $data['periode'] = $periode;
      $data['tahun'] = $tahun;
      $data['kelas'] = $kelas;
      $data['status'] = $status;
      $var['kelas'] = Santri::select('kelas')
      ->groupBy('kelas')
      ->orderBy('kelas')
      ->get();
      $var['status'] = [
        0 => 'Semua',
        'Sudah Valid Lapor',
        'Belum Lapor'
      ];
      $data['bulan'] = $bulan;
      //$data['title'] = 'Syahriyah ' . $bulan[$periode] . ' ' . $tahun;
      $title = 'Laporan Syahriah';

      return view('admin.laporan.pembayaran', compact('title', 'data','var'));
    } else {
      $var['bulan'] = $bulan;
      $data = '';
      $var['kelas'] = Santri::select('kelas')
      ->groupBy('kelas')
      ->orderBy('kelas')
      ->get();
      $var['status'] = [
        0 => 'Semua',
        'Sudah Valid Lapor',
        'Belum Lapor'
      ];
      $title = 'Laporan Syahriah';
      return view('admin.laporan.pembayaran', compact('data','var', 'title'));
    }
  }
  public function export(Request $request){
    return Excel::download(
      new PembayaranExport($request->tahun, $request->periode, $request->kelas, $request->validasi),
      'DataPembayaran' . $request->tahun . '-' . $request->periode . '-' . $request->kelas . '.xlsx'
    );
  }

}
