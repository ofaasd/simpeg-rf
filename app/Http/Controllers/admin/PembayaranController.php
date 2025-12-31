<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\RefJenisPembayaran;
use App\Models\Kamar;
use App\Models\EmployeeNew;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\SakuMasuk;
use App\Models\UangSaku;
use App\Models\SendWaWarning;
use App\Models\GeneratePembayaran;
use App\Models\GenerateDetailPembayaran;
use App\Models\RefBank as Bank;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembayaranExport;
use App\Helpers\Helpers_wa;
use Intervention\Image\Facades\Image;

class PembayaranController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = [
    '',
    'id',
    'Nama Santri',
    'Kelas',
    'Jumlah(Rp)',
    'Tanggal',
    'Bulan',
    'Bank',
    'AN',
    'Note',
    'Validasi',
  ];
  public $bulan = [
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
  public function index(Request $request)
  {
    //

    $periode = (int) date('m');
    $tahun = (int) date('Y');
    $kelas = 0;
    $data['kelas'] = 0;
    if (empty($request->periode)) {
      $where = [
        'periode' => $periode,
        'tahun' => $tahun,
        'is_hapus' => 0,
      ];
    } else {
      $where = [
        'periode' => $request->periode,
        'tahun' => $request->tahun,
        'kelas' => $request->kelas,
        'is_hapus' => 0,
      ];
      if($request->status > 0 && $request->status <=3){
        $where['validasi'] = ($request->status-1);
      }
      $periode = $request->periode;
      $tahun = $request->tahun;
      $data['kelas'] = $request->kelas;
    }
    $kelas = Santri::select('kelas')
      ->groupBy('kelas')
      ->orderBy('kelas')
      ->get();
    $pembayaran = Pembayaran::select(
      'tb_pembayaran.*',
      'santri_detail.nama',
      'santri_detail.no_induk',
      'santri_detail.kelas',
      'santri_detail.kamar_id'
    )
      ->where($where)
      ->join('santri_detail', 'santri_detail.no_induk', '=', 'tb_pembayaran.nama_santri')
      ->get();
    $id_sudah = [];
    foreach($pembayaran as $row){
      $id_sudah[] = $row->nama_santri;
    }
    if(!empty($request->kelas)){
      $data['sisa_santri'] = Santri::where('kelas', $request->kelas)
        ->whereNotIn('no_induk', $id_sudah)
        ->orderBy('no_induk')
        ->get();
    }else{
      $data['sisa_santri'] = Santri::whereNotIn('no_induk', $id_sudah)
        ->orderBy('no_induk')
        ->get();
    }
    $title = 'Pembayaran';
    $kamar = Kamar::all();
    $data['nama_murroby'] = [];
    $data['bulan'] = $this->bulan;
    $data['periode'] = $periode;
    $data['tahun'] = $tahun;

    $data['status'] = [
      'semua',
      'belum valid',
      'valid',
      'tidak_valid',
      'belum lapor',
    ];
    $status = 0;
    if(!empty($request->status)){
      $status = $request->status;
    }

    foreach ($kamar as $row) {
      // $data['nama_murroby'][$row->id] = $this->db
      //   ->get_where('employee_new', ['id' => $row->employee_id])
      //   ->row()->nama;
      $data['nama_murroby'][$row->id] = EmployeeNew::find($row->employee_id)->nama;
    }
    return view('admin.pembayaran.index', compact('title', 'status', 'pembayaran', 'data', 'kelas'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //

    $title = "Tambah Pembayaran";
    $santri = Santri::all();
    $list_bulan = $this->bulan;
    $ref_bank = Bank::all();
    $jenis_pembayaran = RefJenisPembayaran::all();
    return view('admin.pembayaran.create', compact('title', 'santri','list_bulan','ref_bank','jenis_pembayaran'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //

    $jumlah = str_replace(".", "", $request->jumlah);

    $tipe = $request->tipe;
    $bank_pengirim = 0;
    if($tipe == "Bank")
      $bank_pengirim = $request->bank_pengirim;

    $id = $request->id;
    if($id){
      //update the value

      $pembayaran = Pembayaran::updateOrCreate(
        ['id' => $id],
        [
          'nama_santri' => $request->nama_santri,
          'jumlah' => $jumlah,
          'tanggal_bayar' => $request->tanggal_bayar,
          'periode' => $request->periode,
          'tahun' => $request->tahun,
          'bank_pengirim' => $bank_pengirim,
          'atas_nama' => $request->atas_nama,
          'catatan' => $request->catatan,
          'no_wa' => $request->no_wa,
          'validasi' => $request->validasi,
          'note_validasi' => $request->note_validasi,
          'tipe' => $tipe,
          'input_by' => 4,
        ]

      );

      if ($request->file('bukti')) {
        $photo = $request->file('bukti');
        $filename = date('YmdHi') . $photo->getClientOriginalName();
        $kompres = Image::make($photo)
          ->resize(400, 400)
          ->save('assets/img/upload/bukti/' . $filename);
        if ($kompres) {
          //$file = $request->file->store('public/assets/img/upload/photo');
          $Pembayaran = Pembayaran::find($id);
          $Pembayaran->bukti = $filename;
          $Pembayaran->save();
        }
      }

      $delete_jenis = DetailPembayaran::where('id_pembayaran',$id)->delete();

      $jenis_pembayaran = $request->jenis_pembayaran;
      $id_jenis_pembayaran = $request->id_jenis_pembayaran;
      foreach($jenis_pembayaran as $key=>$value){
        if($value != 0 && !empty($value)){
          $nominal = str_replace(".", "", $value);
          $data_detail = array(
              'id_pembayaran'=>$id,
              'id_jenis_pembayaran' => $id_jenis_pembayaran[$key],
              'nominal' => $nominal,
          );
          //$query = $this->db->insert('tb_detail_pembayaran',$data_detail);
          $detail = DetailPembayaran::create($data_detail);

          $saku_masuk = SakuMasuk::where('id_pembayaran',$id);
          $uang_saku = UangSaku::where('no_induk',$request->nama_santri)->first();
          // if($id_jenis_pembayaran[$key] == 3 && $request->validasi == 1 && $saku_masuk->count() > 0){
          //   $old_saku_masuk = $saku_masuk->first()->jumlah;

          //   $data = array(
          //     'dari' => 1,
          //     'jumlah' => $nominal,
          //     'tanggal' => $request->tanggal_bayar,
          //     'no_induk' => $request->nama_santri,
          //   );
          //   $update_saku_masuk = SakuMasuk::where('id_pembayaran',$id)->update($data);
          //   $data2 = array(
          //     'jumlah' => $uang_saku->jumlah - $old_saku_masuk + $nominal
          //   );
          //   $update_tb_uang_saku = UangSaku::where('no_induk',$request->nama_santri)->update($data2);
          // }
        }
      }
      $get_kelas = Santri::where('no_induk',$request->nama_santri)->first();
      $hasil = [
        'status' => 1,
        'kelas' => $get_kelas->kelas,
        'periode' => $request->periode,
        'tahun' => $request->tahun,
      ];
      return response()->json($hasil);
    }else{

      //create new record
      $pembayaran = Pembayaran::updateOrCreate(
        ['id' => $id],
        [
          'nama_santri' => $request->nama_santri,
          'jumlah' => $jumlah,
          'tanggal_bayar' => $request->tanggal_bayar,
          'periode' => $request->periode,
          'tahun' => $request->tahun,
          'bank_pengirim' => $bank_pengirim,
          'atas_nama' => $request->atas_nama,
          'catatan' => $request->catatan,
          'no_wa' => $request->no_wa,
          'validasi' => $request->validasi,
          'note_validasi' => $request->note_validasi,
          'tipe' => $tipe,
          'input_by' => 4,
        ]

      );
      $id = Pembayaran::orderBy('id','desc')->limit(1)->first()->id;
      if ($request->file('bukti')) {
        $photo = $request->file('bukti');
        $filename = date('YmdHi') . $photo->getClientOriginalName();
        $kompres = Image::make($photo)
          ->resize(400, 400)
          ->save('assets/img/upload/bukti/' . $filename);
        if ($kompres) {
          //$file = $request->file->store('public/assets/img/upload/photo');
          $Pembayaran = Pembayaran::find($id);
          $Pembayaran->bukti = $filename;
          $Pembayaran->save();
        }
      }

      $jenis_pembayaran = $request->jenis_pembayaran;
      $id_jenis_pembayaran = $request->id_jenis_pembayaran;
      foreach($jenis_pembayaran as $key=>$value){
        if($value != 0 && !empty($value)){
          $nominal = str_replace(".", "", $value);
          $data_detail = array(
              'id_pembayaran'=>$id,
              'id_jenis_pembayaran' => $id_jenis_pembayaran[$key],
              'nominal' => $nominal,
          );
          //$query = $this->db->insert('tb_detail_pembayaran',$data_detail);
          $detail = DetailPembayaran::create($data_detail);

          $uang_saku = UangSaku::where('no_induk',$request->nama_santri)->first();
          // if($id_jenis_pembayaran[$key] == 3 && $request->validasi == 1){

          //   $data = array(
          //     'dari' => 1,
          //     'jumlah' => $nominal,
          //     'tanggal' => $request->tanggal_bayar,
          //     'no_induk' => $request->nama_santri,
          //     'id_pembayaran' => $id
          //   );
          //   $update_saku_masuk = SakuMasuk::create($data);
          //   $data2 = array(
          //     'jumlah' => ($uang_saku->jumlah ?? 0) + $nominal
          //   );
          //   $update_tb_uang_saku = UangSaku::where('no_induk',$request->nama_santri)->update($data2);
          // }
        }
      }
      if($pembayaran){
        $get_kelas = Santri::where('no_induk',$request->nama_santri)->first();
        $hasil = [
          'status' => 1,
          'kelas' => $get_kelas->kelas,
          'periode' => $request->periode,
          'tahun' => $request->tahun,
          'id_pembayaran' => $pembayaran->id,
        ];
        return response()->json($hasil);
      }else{
        return response()->json('Error');
      }
    }

  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
    $title = "Edit Pembayaran";
    $santri = Santri::all();
    $list_bulan = $this->bulan;
    $ref_bank = Bank::all();
    $jenis_pembayaran = RefJenisPembayaran::all();
    $pembayaran = Pembayaran::where('id',$id)->first();
    $detailPembayaran = DetailPembayaran::where('id_pembayaran',$id)->get();
    $list_detail = [];
    foreach($jenis_pembayaran as $jenis){
      $list_detail[$jenis->id] = 0;
    }
    foreach($detailPembayaran as $row){
      $list_detail[$row->id_jenis_pembayaran] = number_format($row->nominal,0,',','.');
    }
    $curr_santri = Santri::where('no_induk',$pembayaran->nama_santri)->first();
    return view('admin.pembayaran.create', compact('title', 'santri','list_bulan','ref_bank','jenis_pembayaran','pembayaran','list_detail','curr_santri'));
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
    $pembayaran = Pembayaran::where('id', $id)->delete();
    $detail = DetailPembayaran::where('id_pembayaran', $id)->delete();

  }
  public function export(Request $request)
  {
    return Excel::download(
      new PembayaranExport($request->tahun, $request->periode, $request->kelas),
      'DataPembayaran' . $request->tahun . '-' . $request->periode . '-' . $request->kelas . '.xlsx'
    );
  }
  public function review(Request $request)
  {
    $hasil = json_decode($request->hasil, true);
    $title = 'Pembayaran';
    $data['bulan'] = $this->bulan;
    $data['periode'] = $hasil[0]['bulan'];
    $data['tahun'] = $hasil[0]['tahun'];
    $data['kelas'] = $hasil[0]['kode_kelas'];
    $data['jenis_pembayaran'] = RefJenisPembayaran::all();

    return view('admin.pembayaran.review', compact('title', 'hasil', 'data'));
  }
  public function detail_bayar(Request $request)
  {
    $id = $request->id;
    $pembayaran = Pembayaran::select(
      'tb_pembayaran.*',
      'santri_detail.nama',
      'santri_detail.no_induk',
      'santri_detail.kelas',
      'santri_detail.kamar_id',
      'employee_new.nama as nama_murroby'
      )->join('santri_detail', 'santri_detail.no_induk', '=', 'tb_pembayaran.nama_santri')
      ->join('ref_kamar','ref_kamar.id','=','santri_detail.kamar_id')
      ->join('employee_new','employee_new.id','=','ref_kamar.employee_id')
      ->where('tb_pembayaran.id', $id)
      ->first();
    $detail_pembayaran = DetailPembayaran::where('id_pembayaran', $id)->get();
    $gabung = [];
    $gabung[0] = $pembayaran;
    $get_detail = [];
    foreach ($detail_pembayaran as $row) {
      $jenis = RefJenisPembayaran::where('id', $row->id_jenis_pembayaran)->first();
      $get_detail[$jenis->jenis] = number_format($row->nominal, 0, ',', '.');
    }
    $gabung[1] = $get_detail;
    return json_encode($gabung);
  }
  public function update_status(Request $request){
    $id = $request->id;
    $status = $request->status;
    $data = [
      'validasi' => $status,
    ];
    $update = Pembayaran::where('id',$id)->update($data);
    if( $update){
      $hasil = [
        'status' => 1,
      ];
      return response()->json($hasil);
    }
  }
  public function get_pesan_warning(Request $request){
    $no_induk = $request->no_induk;
    $santri = Santri::where('no_induk',$no_induk)->first();
    $pesan = '[ Admin Bendahara PPATQRF ]

Yth. Bp/Ibu ' . $santri->nama_lengkap_ayah . '/' . $santri->nama_lengkap_ibu . ', Wali Santri ' . $santri->nama . ' kelas ' . $santri->kelas . '.

Mohon maaf kami belum menerima laporan pembayaran bulan ' . $request->periode . ' tahun ' . $request->tahun .
'. Jika merasa sudah melakukan pembayaran, harap segera melaporkan pembayaran melalui https://payment.ppatq-rf.id atau dapat menghubungi bagian tata usaha PPATQ-RF.
Kami ucapkan banyak terima kasih kepada (Bp/Ibu) ' . $santri->nama_lengkap_ayah . '/' . $santri->nama_lengkap_ibu . ', salam kami kepada keluarga.
Semoga pekerjaan dan usahanya diberikan kelancaran dan menghasilkan Rizqi yang banyak dan berkah, aamiin.
';
    $data[] = $santri;
    $data[] = $pesan;
    $cek_data = SendWaWarning::where(['id_santri'=>$no_induk,'periode'=>$request->bulan,'tahun'=>$request->tahun])->count();
    $data[] = $cek_data;
    return response()->json($data);
  }
  public function send_warning(Request $request){
    $no_wa = $request->no_wa;
    $periode = $request->periode;
    $tahun = $request->tahun;
    $pesan = $request->pesan;
    $create = SendWaWarning::create([
      'no_wa' => $no_wa,
      'periode' => $periode,
      'pesan' => $pesan,
      'id_santri' => $request->id_santri,
      'tahun' => $tahun,
    ]);



    //return response()->json($hasil);
    if($create){
      $hasil = [
        'status' => 1,
      ];
      $data_wa['no_wa'] = $no_wa;
      $data_wa['pesan'] = $pesan;
      $kirim = Helpers_wa::send_wa($data_wa);
      if($kirim){
        return response()->json($hasil);
      }else{
        $hasil = [
          'status' => 3,
        ];
        return response()->json($hasil);
      }
    }else{
      $hasil = [
        'status' => 0,
      ];
      return response()->json($hasil);
    }
  }
  public function generate_pembayaran(){
    $title = "Generate Pembayaran Santri";
    $santri = Santri::orderBy('kelas','asc')->get();
    $list_bulan = $this->bulan;
    $kelas = Kelas::all();
    $ref_bank = Bank::all();
    $jenis_pembayaran = RefJenisPembayaran::all();
    $new_kelas_already = GeneratePembayaran::select('kelas')->join("santri_detail","santri_detail.no_induk","generate_pembayaran.no_induk")->distinct()->get();
    $kelas_already = [];
    $i = 0;
    foreach($new_kelas_already as $row){
      $kelas_already[$i] = $row->kelas;
      $i++;
    }
    //var_dump($kelas_already);
    $generate = [];
    $bulan = date('m');
    $pembayaran = GeneratePembayaran::where('bulan',$bulan)->get();
    foreach($santri as $row){
      $generate[$row->no_induk] = [];
    }
    foreach($pembayaran as $row){
      $generate[$row->no_induk] = $row;
    }
    //var_dump($total);
    return view('admin.pembayaran.generate', compact('kelas_already','generate','title','kelas', 'santri','list_bulan','ref_bank','jenis_pembayaran'));
  }
  public function generate_pembayaran_single(Request $request){
    $no_induk = $request->no_induk;
    $tahun = $request->tahun;
    $bulan = $request->bulan_input2;
    $total_bayar = (int)str_replace(".","",$request->total_bayar);

    $cek = GeneratePembayaran::where('no_induk',$no_induk)->where('tahun',$tahun)->where("bulan",$bulan);
    if($cek->count() > 0){
      $generate = $cek->first();
      $new_generate = GeneratePembayaran::find($generate->id);
      $new_generate->total_bayar = $total_bayar;
      $new_generate->save();
      $detail_generate = GenerateDetailPembayaran::where('id_generate_pembayaran',$generate->id)->delete();
      foreach($request->id_jenis_pembayaran as $key=>$value){
        $jumlah = (int)str_replace(".","",$request->jenis_pembayaran[$key]);
        if($jumlah != 0){
          $detail_new = new GenerateDetailPembayaran;
          $detail_new->id_generate_pembayaran = $generate->id;
          $detail_new->id_jenis = $value;
          $detail_new->jumlah = $jumlah;
          $detail_new->save();
        }
      }
    }else{
      $new_generate = new GeneratePembayaran;
      $new_generate->total_bayar = $total_bayar;
      $new_generate->bulan = $bulan;
      $new_generate->tahun = $tahun;
      $new_generate->status = '0';
      $new_generate->no_induk = $no_induk;
      $new_generate->save();

      foreach($request->id_jenis_pembayaran as $key=>$value){
        $jumlah = (int)str_replace(".","",$request->jenis_pembayaran[$key]);
        if($jumlah != 0){
          $detail_new = new GenerateDetailPembayaran;
          $detail_new->id_generate_pembayaran = $new_generate->id;
          $detail_new->id_jenis = $value;
          $detail_new->jumlah = $jumlah;
          $detail_new->save();
        }
      }
    }
    return redirect('pembayaran/generate');
  }
  public function get_generate(Request $request){
    $bulan = $request->bulan;
    $tahun = $request->tahun;
    $no_induk = $request->no_induk;
    $generate = [];
    $generate_pembayaran = GeneratePembayaran::where(['bulan'=>$bulan,'tahun'=>$tahun,'no_induk'=>$no_induk])->first();
    if($generate_pembayaran){
      $detail_generate = GenerateDetailPembayaran::where('id_generate_pembayaran',$generate_pembayaran->id)->get();
      $generate = $generate_pembayaran;
    }
    $all_data[0] = $generate;
    $all_data[1] = $detail_generate;
    return response()->json($all_data);
  }
  public function set_pembayaran(Request $request){
    $arr_kelas = $request->kelas;
    $bulan = $request->bulan;
    $tahun = date('Y');
    $total_bayar = (int)str_replace(".","",$request->total_bayar);
    if(!empty($arr_kelas)){
      foreach($arr_kelas as $value){
        $santri = Santri::where("kelas",$value)->get();
        foreach($santri as $row){
          $generate = GeneratePembayaran::where('no_induk',$row->no_induk)->delete();
          $generate_new = new GeneratePembayaran;
          $generate_new->no_induk = $row->no_induk;
          $generate_new->total_bayar = (int)str_replace(".","",$total_bayar);
          $generate_new->bulan = $bulan;
          $generate_new->tahun = $tahun;
          $generate_new->status = '0';
          $generate_new->save();
          foreach($request->id_jenis_pembayaran as $key=>$value){
            $jumlah = (int)str_replace(".","",$request->jenis_pembayaran[$key]);
            if($jumlah != 0){
              $detail_new = new GenerateDetailPembayaran;
              $detail_new->id_generate_pembayaran = $generate_new->id;
              $detail_new->id_jenis = $value;
              $detail_new->jumlah = $jumlah;
              $detail_new->save();
            }
          }
        }
      }
    }else{
      $santri = Santri::all();
      foreach($santri as $row){
        $generate = GeneratePembayaran::where('no_induk',$row->no_induk)->delete();
        $generate_new = new GeneratePembayaran;
        $generate_new->no_induk = $row->no_induk;
        $generate_new->total_bayar = (int)str_replace(".","",$total_bayar);
        $generate_new->bulan = $bulan;
        $generate_new->tahun = $tahun;
        $generate_new->status = '0';
        $generate_new->save();
        foreach($request->id_jenis_pembayaran as $key=>$value){
          $jumlah = (int)str_replace(".","",$request->jenis_pembayaran[$key]);
          if($jumlah != 0){
            $detail_new = new GenerateDetailPembayaran;
            $detail_new->id_generate_pembayaran = $generate_new->id;
            $detail_new->id_jenis = $value;
            $detail_new->jumlah = $jumlah;
            $detail_new->save();
          }
        }
      }
    }

    return redirect('pembayaran/generate');
  }
  public function publish(Request $request){
    $bulan = $request->bulan;
    $tahun = $request->tahun;
    $generate = GeneratePembayaran::where("bulan",$bulan)->where("tahun",$tahun)->update(['publish'=>1]);
    return response()->json($generate);
  }
}
