<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PsbPesertaOnline;
use App\Models\PsbSekolahAsal;
use App\Models\PsbWaliPesertum;
use App\Models\PsbBerkasPendukung;
use App\Models\PsbBuktiPembayaran;
use App\Models\PsbSeragam;
use App\Models\UserPsb;
use App\Models\PsbGelombang;
use App\Models\Province;
use App\Models\City;
use App\Models\ProvinsiTbl;
use App\Models\KotaKabTbl;
use App\Models\KecamatanTbl;
use App\Models\KelurahanTbl;
use App\Models\TemplatePesan;
use App\Models\TahunAjaran;
use App\Helpers\Helpers_wa;
use App\Exports\PsbExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use PDF;

use DateTime;

class psb extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $array_bulan = [
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
  public $indexed = ['', 'id', 'no_pendaftaran', 'nama', 'ttl', 'status', 'status_ujian', 'status_diterima'];
  public $indexed2 = ['', 'id', 'nama', 'no_pendaftaran', 'ttl', 'bayar'];
  public function index(Request $request, int $id=0)
  {
    //
    if($id == 0){
      $id_gelombang = PsbGelombang::orderBy('id','desc')->first()->id;
    }else{
      $id_gelombang = $id;
    }
    if (empty($request->input('length'))) {
      $title = 'Psb';
      $indexed = $this->indexed;
      $gelombang = PsbGelombang::orderBy('id','desc')->get();
      return view('admin.psb.index', compact('title', 'indexed','gelombang','id'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'no_pendaftaran',
        3 => 'nama',
        4 => 'ttl',
        5 => 'status',
        6 => 'status_ujian',
        7 => 'status_diterima',
      ];

      $search = [];

      $totalData = PsbPesertaOnline::where('gelombang_id',$id_gelombang)->count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $PsbPesertaOnline = PsbPesertaOnline::where('gelombang_id',$id_gelombang)->offset($start)
          ->limit($limit)
          ->orderBy('created_at', 'desc')
          ->get();
      } else {
        $search = $request->input('search.value');

        $PsbPesertaOnline = PsbPesertaOnline::where('gelombang_id',$id_gelombang)
        ->where(function ($query) use ($search) {
          $query
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
        })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = PsbPesertaOnline::where('gelombang_id',$id_gelombang)
        ->where(function ($query) use ($search) {
          $query
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
        })->count();
      }

      $data = [];

      if (!empty($PsbPesertaOnline)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($PsbPesertaOnline as $row) {
          $tanggal_lahir = date('Y-m-d', $row->tanggal_lahir);
          $dob = new DateTime($tanggal_lahir);
          $today = new DateTime('today');
          $year = $dob->diff($today)->y;
          $month = $dob->diff($today)->m;
          $day = $dob->diff($today)->d;
          $psbBerkas = PsbBerkasPendukung::where('psb_peserta_id', $row->id)->first();
          $user = UserPsb::where('no_pendaftaran', $row->no_pendaftaran)->first();
          $walisan = PsbWaliPesertum::where('psb_peserta_id', $row->id)->first();
          $PsbSeragam = PsbSeragam::where('psb_peserta_id', $row->id)->first();
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['no_pendaftaran'] = $row->no_pendaftaran . '';
          $nestedData['nama'] = substr($row->nama, 0, 15) ?? '';
          $nestedData['nama_lengkap'] = $row->nama;
          $nestedData['tempat_lahir'] = $row->tempat_lahir ?? '';
          $nestedData['tanggal_lahir'] = !empty($row->tanggal_lahir) ? date('d-m-Y', $row->tanggal_lahir) : '';
          $nestedData['status'] = $row->status ?? '';
          $nestedData['status_ujian'] = $row->status_ujian ?? '';
          $nestedData['status_diterima'] = $row->status_diterima ?? '';
          $nestedData['tanggal_daftar'] = date('d-m-Y H:i:s', strtotime($row->created_at));
          $nestedData['file_photo'] = $psbBerkas->file_photo ?? '';
          $nestedData['file_kk'] = $psbBerkas->file_kk ?? '';
          $nestedData['file_ktp'] = $psbBerkas->file_ktp ?? '';
          $nestedData['file_rapor'] = $psbBerkas->file_rapor ?? '';
          $nestedData['no_wa'] = $walisan->no_hp ?? '';
          $nestedData['password'] = $user->password_ori ?? '';
          $nestedData['umur_tahun'] = $year ?? '';
          $nestedData['umur_bulan'] = $year ?? '';
          $nestedData['jenis_kelamin'] = $row->jenis_kelamin ?? '';
          $nestedData['berat_badan'] = $PsbSeragam->berat_badan ?? '';
          $nestedData['tinggi_badan'] = $PsbSeragam->tinggi_badan ?? '';
          $nestedData['lingkar_dada'] = $PsbSeragam->lingkar_dada ?? '';
          $nestedData['lingkar_pinggul'] = $PsbSeragam->lingkar_pinggul ?? '';
          $nestedData['username'] = $user->username ?? '';
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
  public function validasi(Request $request, int $id = 0)
  {
    if($id == 0){
      $id_gelombang = PsbGelombang::orderBy('id','desc')->first()->id;
    }else{
      $id_gelombang = $id;
    }
    if (empty($request->input('length'))) {
      $title = 'Validasi';
      $indexed = $this->indexed2;
      $pesan = TemplatePesan::where('status', 1)->first();
      $gelombang = PsbGelombang::orderBy('id','desc')->get();
      return view('admin.psb.validasi', compact('title', 'pesan', 'indexed', 'gelombang', 'id'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'nama',
        3 => 'no_pendaftaran',
        4 => 'ttl',
        5 => 'bayar',
      ];

      $search = [];

      $totalData = PsbPesertaOnline::where('gelombang_id',$id_gelombang)
      ->count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $PsbPesertaOnline = PsbPesertaOnline::where('gelombang_id',$id_gelombang)
        ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $PsbPesertaOnline = PsbPesertaOnline::where('gelombang_id',$id_gelombang)
        ->where(function ($query) use ($search) {
          $query
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
        })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = PsbPesertaOnline::where('gelombang_id',$id_gelombang)
        ->where(function ($query) use ($search) {
          $query
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
        })->count();
      }

      $data = [];

      if (!empty($PsbPesertaOnline)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($PsbPesertaOnline as $row) {
          $bukti_bayar = 0;
          $bukti = PsbBuktiPembayaran::where('psb_peserta_id', $row->id);
          $tanggal_bayar = '';
          if ($bukti->count() > 0) {
            $bukti_row = $bukti->first();
            $bukti_bayar = $bukti_row->status;
            $tanggal_bayar = date('d-m-Y', strtotime($bukti_row->created_at));
          }
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['no_pendaftaran'] = $row->no_pendaftaran . '';
          $nestedData['pengumuman_warning'] = $row->warning_pembayaran_wa . '';
          $nestedData['nama'] = $row->nama ?? '';
          $nestedData['ttl'] = 'TD : ' . date('d-m-Y', strtotime($row->created_at)) . ' <br /> TB : ' . $tanggal_bayar;
          $nestedData['bayar'] = $bukti_bayar;
          $nestedData['pengumuman_validasi_wa'] = $row->pengumuman_validasi_wa;
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
  public function edit_validasi($id)
  {
    $validasi = PsbBuktiPembayaran::where('psb_peserta_id', $id);
    if ($validasi->count() == 0) {
      $array = [];
    } else {
      $array = $validasi->first();
    }
    return response()->json($array);
  }
  public function store_validasi(Request $request)
  {
    //
    $id = $request->id;

    if ($id) {
      // update the value
      $PsbBuktiPembayaran = PsbBuktiPembayaran::updateOrCreate(
        ['id' => $id],
        [
          'bank' => $request->bank,
          'no_rekening' => $request->no_rekening,
          'atas_nama' => $request->atas_nama,
          'status' => $request->status,
          'psb_peserta_id' => $request->psb_peserta_id,
        ]
      );
      if ($request->status == '2') {
        $peserta = PsbPesertaOnline::where('id', $request->psb_peserta_id)->first();
        $walisan = PsbWaliPesertum::where('psb_peserta_id', $request->psb_peserta_id)->first();
        $user = UserPsb::where('no_pendaftaran', $peserta->no_pendaftaran)->first();
        $template_pesan = TemplatePesan::where('status', 1)->first();

        $pesan = str_replace('{{nama}}', $peserta->nama, $template_pesan->pesan);
        $pesan = str_replace('{{tanggal_validasi}}', date('Y-m-d H:i:s', $peserta->tanggal_validasi), $pesan);
        $pesan = str_replace('{{no_test}}', $peserta->no_test, $pesan);
        $pesan = str_replace('{{username}}', $user->username, $pesan);
        $pesan = str_replace('{{password}}', $user->password_ori, $pesan);

        $data['no_wa'] = $request->no_hp;
        $data['no_hp'] = $walisan->no_hp;

        Helpers_wa::send_wa($data);
      }

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $PsbBuktiPembayaran = PsbBuktiPembayaran::updateOrCreate(
        ['id' => $id],
        [
          'bank' => $request->bank,
          'no_rekening' => $request->no_rekening,
          'atas_nama' => $request->atas_nama,
          'status' => $request->status,
          'psb_peserta_id' => $request->psb_peserta_id,
        ]
      );
      if ($PsbBuktiPembayaran) {
        if ($request->status == '2') {
          $peserta = PsbPesertaOnline::where('id', $request->psb_peserta_id)->first();
          $walisan = PsbWaliPesertum::where('psb_peserta_id', $request->psb_peserta_id)->first();
          $user = UserPsb::where('no_pendaftaran', $peserta->no_pendaftaran)->first();
          $template_pesan = TemplatePesan::where('status', 1)->first();

          $pesan = str_replace('{{nama}}', $peserta->nama, $template_pesan->pesan);
          $pesan = str_replace('{{tanggal_validasi}}', date('Y-m-d H:i:s', $peserta->tanggal_validasi), $pesan);
          $pesan = str_replace('{{no_test}}', $peserta->no_test, $pesan);
          $pesan = str_replace('{{username}}', $user->username, $pesan);
          $pesan = str_replace('{{password}}', $user->password_ori, $pesan);

          $data['no_wa'] = $request->no_hp;
          $data['no_hp'] = $walisan->no_hp;

          Helpers_wa::send_wa($data);
        }
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create Academic');
      }
    }
  }
  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
    $title = 'Psb';
    $indexed = $this->indexed;
    $provinsi = ProvinsiTbl::all();
    return view('admin.psb.create', compact('title', 'indexed', 'provinsi'));
  }

  /**
   * Store a newly created resource in storage.
   */

  public function validation(Request $request)
  {
    $no_hp = $request->no_hp;
    //cek no_hp sudah terdaftar pada sistem
    $array = [];
    $cek_hp = UserPsb::where('no_hp', $no_hp)->count();
    $hitung = 0;
    //cek umur peserta > 5 dan  < 12 tahun
    $tanggal_lahir = $request->tanggal_lahir;
    $dob = new DateTime($tanggal_lahir);
    $today = new DateTime('today');
    $year = $dob->diff($today)->y;
    $month = $dob->diff($today)->m;
    $day = $dob->diff($today)->d;
    //echo "Age is"." ".$year."year"." ",$month."months"." ".$day."days";
    if ($year < 5 || $year >= 13) {
      $array[] = [
        'code' => 2,
        'status' => 'error',
        'msg' => 'Usia minimal 5 tahun dan maksimal 12 tahun',
      ];
      //echo json_encode($array);
      $hitung++;
    }

    $nama = $request->nama_lengkap;
    $jenis_kelamin = $request->jenis_kelamin;
    $nik = $request->nik;
    $int_tanggal_lahir = strtotime($tanggal_lahir);
    $peserta = PsbPesertaOnline::where([
      'nama' => $nama,
      'tanggal_lahir' => strtotime($tanggal_lahir),
      'jenis_kelamin' => $jenis_kelamin,
      'nik' => $nik,
    ])->count();

    if ($peserta > 0) {
      $array[] = [
        'code' => 3,
        'status' => 'error',
        'msg' => 'Calon Santri sudah pernah di daftarkan',
      ];
      //echo json_encode($array);
      $hitung++;
    }
    if ($hitung > 0) {
      echo json_encode($array);
    } else {
      $array[] = [
        'code' => 0,
        'status' => 'success',
        'msg' => '',
      ];
      echo json_encode($array);
    }
  }
  public function store(Request $request)
  {
    //
    $gelombang = PsbGelombang::where('pmb_online', 1)->first();
    $user = new UserPsb();
    $tahunAjaran = TahunAjaran::where('id',$gelombang->tahun)->first();
    $user->nik = $request->nik;
    $user->nama = $request->nama_lengkap;
    $user->tanggal_lahir = strtotime($request->tanggal_lahir);
    $user->alamat = $request->alamat;
    $user->prov_id = $request->provinsi;
    $user->city_id = $request->kota;
    $user->kecamatan = $request->kecamatan;
    $user->kelurahan = $request->kelurahan;
    $user->kode_pos = $request->kode_pos;
    $user->no_hp = $request->no_hp;
    $username = '';
    if ($user->save()) {
      $nama = $request->nama_lengkap;
      $tgl_lahir = $request->tanggal_lahir;
      $id = $user->id;
      $get_id_pendaftar = (PsbPesertaOnline::where('gelombang_id',$gelombang->id)->orderBy('id_pendaftar','desc')->limit(1)->first()->id_pendaftar) + 1;
      $new_user = UserPsb::find($id);
      $str_id = "";
      if(strlen($get_id_pendaftar) == 1){
          $str_id = "00" . $get_id_pendaftar;
      }elseif(strlen($get_id_pendaftar) == 2){
          $str_id = "0" . $get_id_pendaftar;
      }else{
          $str_id = (string)$get_id_pendaftar;
      }
      $tahun_lahir = date('Y', strtotime($tgl_lahir));
      $new_nama = substr($nama,0,3);
      $nama_panggilan = explode(" ",$nama);
      $tanggal = date('dm',strtotime($tgl_lahir));
      $new_tanggal = date('d-m-Y',strtotime($tgl_lahir));

      //create username and password
      $username = $nama_panggilan[0] . "_" . $str_id;
      $password = $new_tanggal;
      $no_pendaftaran = "RF.ppatq." . $str_id . "." . substr($tahunAjaran->akhir, -2);

      $user->username = $username;
      $user->password = md5($password);
      $user->password_ori = $password;
      $user->no_pendaftaran = $no_pendaftaran;
      if ($user->save()) {
        //kirim pesan wa disini
        $pesan = "*Pesan ini dikirim dari sistem PSB PPATQ-RF*

Selamat
nama : " . $nama . "
no pendaftaran : " . $no_pendaftaran . "

telah terdaftar pada web sebagai peserta test seleksi  Peserta Didik Baru PPATQ Radlatul Falah Pati

Silahkan catat username dan password di bawah ini untuk dapat mengubah dan melengkapi data

username : " . $username . "
password : " . $password . "

Selanjutnya silahkan login di sistem dan melaporkan pembayaran Uang pendaftaran di rekening

BSI. 7141299818 a/n
PONPES ANAK TAHFIDZUL QUR'AN RF
melalui psb.ppatq-rf.id menu Pembayaran dan juga dapat melakukan pengkinian data - dokumen pelengkap.

terimakasih


#simpanWA_ini";
        $data['no_wa'] = $request->no_hp;
        $data['pesan'] = $pesan;

        Helpers_wa::send_wa($data);
      }

      $data = new PsbPesertaOnline();
      $data->nik = $request->nik;
      $data->nama = $request->nama_lengkap;
      $data->nama_panggilan = $request->nama_panggilan;
      $data->jenis_kelamin = $request->jenis_kelamin;
      $data->tempat_lahir = $request->tempat_lahir;
      $data->tanggal_lahir = strtotime($request->tanggal_lahir);
      $data->usia_bulan = $request->usia_bulan;
      $data->usia_tahun = $request->usia_tahun;
      $data->jumlah_saudara = $request->jumlah_saudara;
      $data->anak_ke = $request->anak_ke;
      $data->alamat = $request->alamat;
      $data->prov_id = $request->provinsi;
      $data->kota_id = $request->kota;
      $data->kecamatan = $request->kecamatan;
      $data->kelurahan = $request->kelurahan;
      $data->kode_pos = $request->kode_pos;
      $data->gelombang_id = $gelombang->id;
      $data->no_pendaftaran = $no_pendaftaran;
      $data->id_pendaftar = $get_id_pendaftar;
      $data->input_by = 2;
      $data->user_id = $id;
      if ($data->save()) {
        $walsan = new PsbWaliPesertum();
        $walsan->nama_ayah = $request->nama_ayah;
        $walsan->nama_ibu = $request->nama_ibu;
        $walsan->pendidikan_ayah = $request->pendidikan_ayah;
        $walsan->pendidikan_ibu = $request->pendidikan_ibu;
        $walsan->pekerjaan_ayah = $request->pekerjaan_ayah;
        $walsan->pekerjaan_ibu = $request->pekerjaan_ibu;
        $walsan->alamat_ayah = $request->alamat_ayah;
        $walsan->alamat_ibu = $request->alamat_ibu;
        $walsan->no_hp = $request->no_hp;
        $walsan->no_telp = $request->no_telp;
        $walsan->psb_peserta_id = $data->id;
        $walsan->save();

        $sekolahAsal = new PsbSekolahAsal();
        $sekolahAsal->jenjang = $request->jenjang;
        $sekolahAsal->kelas = $request->kelas;
        $sekolahAsal->nama_sekolah = $request->nama_sekolah;
        $sekolahAsal->nss = $request->nss;
        $sekolahAsal->npsn = $request->npsn;
        $sekolahAsal->nisn = $request->nisn;
        $sekolahAsal->psb_peserta_id = $data->id;
        $sekolahAsal->save();

        $psb_seragam = new PsbSeragam();
        $psb_seragam->tinggi_badan = $request->tinggi_badan;
        $psb_seragam->berat_badan = $request->berat_badan;
        $psb_seragam->lingkar_dada = $request->lingkar_dada;
        $psb_seragam->lingkar_pinggul = $request->lingkar_pinggul;
        $psb_seragam->psb_peserta_id = $data->id;
        $psb_seragam->save();

        $array[] = [
          'code' => 0,
          'status' => 'Success',
          'username' => $username,
          'password' => $password,
          'id' => $data->id,
          'msg' => '',
        ];
        echo json_encode($array);
      } else {
        $array[] = [
          'code' => 1,
          'status' => 'Error',
          'msg' => '',
        ];
        echo json_encode($array);
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id = "")
  {
    //
    $provinsi = ProvinsiTbl::all();
    $psb_peserta = PsbPesertaOnline::find($id);
    $var['santri_photo'] = asset('assets/img/avatars/1.png');
    $title = 'santri';
    $var['psb_peserta'] = $psb_peserta;
    $psb_wali = PsbWaliPesertum::where('psb_peserta_id', $psb_peserta->id)->first();
    $psb_asal = PsbSekolahAsal::where('psb_peserta_id', $psb_peserta->id)->first();
    $psb_seragam = PsbSeragam::where('psb_peserta_id', $psb_peserta->id)->first();
    $berkas_pendukung = PsbBerkasPendukung::where('psb_peserta_id', $psb_peserta->id);
    $foto = 'https://payment.ppatq-rf.id/assets/images/user.png';
    if ($berkas_pendukung->count() > 0 && !empty($berkas_pendukung->first()->file_photo)) {
      $foto = 'https://psb.ppatq-rf.id/assets/images/upload/foto_casan/' . $berkas_pendukung->first()->file_photo;
    }
    $kota = '';
    if (!empty($psb_peserta->prov_id)) {
      $kota = KotaKabTbl::where('id_provinsi', $psb_peserta->prov_id)->get();
    }
    $kecamatan = '';
    if (!empty($psb_peserta->kota_id) && !empty($psb_peserta->kecamatan)) {
      $kecamatan = KecamatanTbl::where('id_provinsi', $psb_peserta->prov_id)
        ->where('id_kota_kab', $psb_peserta->kota_id)
        ->get();
    }
    $kelurahan = '';
    if (!empty($psb_peserta->kota_id) && !empty($psb_peserta->kecamatan) && !empty($psb_peserta->kelurahan)) {
      $kelurahan = KelurahanTbl::where('id_provinsi', $psb_peserta->prov_id)
        ->where('id_kota_kab', $psb_peserta->kota_id)
        ->where('id_kecamatan', $psb_peserta->kecamatan)
        ->get();
    }
    $berkas = $berkas_pendukung->first();

    $var['menu'] = ['edit_data_diri', 'edit_wali', 'edit_asal', 'edit_berkas'];
    return view(
      'admin.psb.show',
      compact(
        'psb_seragam',
        'kecamatan',
        'kelurahan',
        'title',
        'var',
        'provinsi',
        'psb_peserta',
        'psb_wali',
        'psb_asal',
        'kota',
        'foto',
        'berkas'
      )
    );
  }
  public function berkas_pendukung(string $id)
  {
    $view_tab = 'berkas_pendukung';
    $provinsi = Province::all();
    $psb_peserta = PsbPesertaOnline::find($id);
    $var['santri_photo'] = asset('assets/img/avatars/1.png');
    $title = 'santri';
    $var['psb_peserta'] = $psb_peserta;
    $psb_wali = PsbWaliPesertum::where('psb_peserta_id', $psb_peserta->id)->first();
    $psb_asal = PsbSekolahAsal::where('psb_peserta_id', $psb_peserta->id)->first();
    $psb_seragam = PsbSeragam::where('psb_peserta_id', $psb_peserta->id)->first();
    $berkas_pendukung = PsbBerkasPendukung::where('psb_peserta_id', $psb_peserta->id);
    $foto = 'https://payment.ppatq-rf.id/assets/images/user.png';
    if ($berkas_pendukung->count() > 0 && !empty($berkas_pendukung->first()->file_photo)) {
      $foto = 'https://psb.ppatq-rf.id/assets/images/upload/foto_casan/' . $berkas_pendukung->first()->file_photo;
    }
    $kota = '';
    if (!empty($psb_peserta->prov_id)) {
      $kota = City::where('prov_id', $psb_peserta->prov_id)->get();
    }
    $berkas = $berkas_pendukung->first();

    $var['menu'] = ['edit_data_diri', 'edit_wali', 'edit_asal', 'edit_berkas'];
    return view(
      'admin.psb.show',
      compact(
        'psb_seragam',
        'title',
        'var',
        'provinsi',
        'psb_peserta',
        'psb_wali',
        'psb_asal',
        'kota',
        'foto',
        'berkas',
        'view_tab'
      )
    );
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
    echo $id;
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
    $academic = PsbPesertaOnline::where('id', $id)->delete();
  }

  public function update_data_pribadi(Request $request)
  {
    $id = $request->id;
    $data = PsbPesertaOnline::find($id);
    $data->nik = $request->nik;
    $data->nama = $request->nama;
    $data->nama_panggilan = $request->nama_panggilan;
    $data->jenis_kelamin = $request->jenis_kelamin;
    $data->tempat_lahir = $request->tempat_lahir;
    $data->tanggal_lahir = strtotime($request->tanggal_lahir);
    $data->usia_bulan = $request->usia_bulan;
    $data->usia_tahun = $request->usia_tahun;
    $data->jumlah_saudara = $request->jumlah_saudara;
    $data->anak_ke = $request->anak_ke;
    $data->alamat = $request->alamat;
    $data->prov_id = $request->provinsi;
    $data->kota_id = $request->kota;
    $data->kecamatan = $request->kecamatan;
    $data->kelurahan = $request->kelurahan;
    $data->kode_pos = $request->kode_pos;
    $data->user_id = $id;
    if ($data->save()) {
      $psb_wali_id = $request->psb_wali_id;
      $walsan = PsbWaliPesertum::find($psb_wali_id);
      $walsan->no_hp = $request->no_hp;
      $walsan->save();

      if ($request->file('photos')) {
        $photo = $request->file('photos');
        $filename = date('YmdHis') . $photo->getClientOriginalName();
        $kompres = Image::make($photo)
          ->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
          })
          ->save('assets/images/upload/foto_casan/' . $filename);
        if ($kompres) {
          //$file = $request->file->store('public/assets/img/upload/photo');
          $cek = PsbBerkasPendukung::where('psb_peserta_id', $id);
          if ($cek->count() > 0) {
            $cek = $cek->first();
            $psbBerkasPendukung = PsbBerkasPendukung::find($cek->id);
            $psbBerkasPendukung->file_photo = $filename;
            $psbBerkasPendukung->save();
          } else {
            $psbBerkasPendukung = new PsbBerkasPendukung();
            $psbBerkasPendukung->file_photo = $filename;
            $psbBerkasPendukung->psb_peserta_id = $id;
            $psbBerkasPendukung->save();
          }
          $array[] = [
            'code' => 1,
            'status' => 'Success',
            'msg' => 'Data Berhasil Disimpan',
            'photo' => $filename,
          ];
          echo json_encode($array);
        }
      } else {
        $array[] = [
          'code' => 1,
          'status' => 'Success',
          'msg' => 'Data Berhasil Disimpan',
        ];
        echo json_encode($array);
      }
    } else {
      $array[] = [
        'code' => 0,
        'status' => 'Error',
        'msg' => 'Data Gagal Disimpan',
      ];
      echo json_encode($array);
    }
  }
  public function update_data_walsan(Request $request)
  {
    $psb_wali_id = $request->psb_wali_id;
    $walsan = PsbWaliPesertum::find($psb_wali_id);
    $walsan->nama_ayah = $request->nama_ayah;
    $walsan->nama_ibu = $request->nama_ibu;
    $walsan->pendidikan_ayah = $request->pendidikan_ayah;
    $walsan->pendidikan_ibu = $request->pendidikan_ibu;
    $walsan->pekerjaan_ayah = $request->pekerjaan_ayah;
    $walsan->pekerjaan_ibu = $request->pekerjaan_ibu;
    $walsan->alamat_ayah = $request->alamat_ayah;
    $walsan->alamat_ibu = $request->alamat_ibu;
    $walsan->no_telp = $request->no_telp;
    if ($walsan->save()) {
      $array[] = [
        'code' => 1,
        'status' => 'Success',
        'msg' => 'Data Berhasil Disimpan',
      ];
      echo json_encode($array);
    } else {
      $array[] = [
        'code' => 0,
        'status' => 'Error',
        'msg' => 'Data Gagal Disimpan',
      ];
      echo json_encode($array);
    }
  }
  public function update_data_asal_sekolah(Request $request)
  {
    $id = $request->psb_asal_sekolah;

    $id_peserta = $request->id_peserta;
    $sekolahAsal = PsbSekolahAsal::find($id);
    $sekolahAsal->jenjang = $request->jenjang;
    $sekolahAsal->kelas = $request->kelas;
    $sekolahAsal->nama_sekolah = $request->nama_sekolah;
    $sekolahAsal->nss = $request->nss;
    $sekolahAsal->npsn = $request->npsn;
    $sekolahAsal->nisn = $request->nisn;
    $seragam = PsbSeragam::where('psb_peserta_id', $id_peserta);
    if ($seragam->count() > 0) {
      $id_seragam = $seragam->first()->id;
      $psb_seragam = PsbSeragam::find($id_seragam);
      $psb_seragam->tinggi_badan = $request->tinggi_badan;
      $psb_seragam->berat_badan = $request->berat_badan;
      $psb_seragam->lingkar_dada = $request->lingkar_dada;
      $psb_seragam->lingkar_pinggul = $request->lingkar_pinggul;
      $psb_seragam->save();
    } else {
      $psb_seragam = new PsbSeragam();
      $psb_seragam->tinggi_badan = $request->tinggi_badan;
      $psb_seragam->berat_badan = $request->berat_badan;
      $psb_seragam->lingkar_dada = $request->lingkar_dada;
      $psb_seragam->lingkar_pinggul = $request->lingkar_pinggul;
      $psb_seragam->psb_peserta_id = $id_peserta;
      $psb_seragam->save();
    }

    if ($sekolahAsal->save()) {
      $array[] = [
        'code' => 1,
        'status' => 'Success',
        'msg' => 'Data Berhasil Disimpan',
      ];
      echo json_encode($array);
    } else {
      $array[] = [
        'code' => 0,
        'status' => 'Error',
        'msg' => 'Data Gagal Disimpan',
      ];
      echo json_encode($array);
    }
  }
  public function update_data_berkas(Request $request)
  {
    $id = $request->id;
    $request->validate([
      'kk' => [File::types(['jpg', 'jpeg', 'png', 'pdf'])],
      'ktp' => [File::types(['jpg', 'jpeg', 'png', 'pdf'])],
      'rapor' => [File::types(['jpg', 'jpeg', 'png', 'pdf'])],
    ]);
    $nama_file = ['kk', 'ktp', 'rapor'];
    $array = [];
    foreach ($nama_file as $value) {
      if ($request->file($value)) {
        $file = $request->file($value);
        $ekstensi = $file->extension();
        if (strtolower($ekstensi) == 'jpg' || strtolower($ekstensi) == 'png' || strtolower($ekstensi) == 'jpeg') {
          $filename = date('YmdHis') . $file->getClientOriginalName();
          $kompres = Image::make($file)
            ->resize(800, null, function ($constraint) {
              $constraint->aspectRatio();
            })
            ->save('assets/images/upload/file_' . $value . '/' . $filename);
        } else {
          $filename = date('YmdHis') . $file->getClientOriginalName();
          $file->move('assets/images/upload/file_' . $value . '/', $filename);
        }
        $cek = PsbBerkasPendukung::where('psb_peserta_id', $id);
        if ($cek->count() > 0) {
          $cek = $cek->first();
          $psbBerkasPendukung = PsbBerkasPendukung::find($cek->id);
          if ($value == 'kk') {
            $psbBerkasPendukung->file_kk = $filename;
          } elseif ($value == 'ktp') {
            $psbBerkasPendukung->file_ktp = $filename;
          } elseif ($value == 'rapor') {
            $psbBerkasPendukung->file_rapor = $filename;
          }
          $psbBerkasPendukung->save();
        } else {
          $psbBerkasPendukung = new PsbBerkasPendukung();
          if ($value == 'kk') {
            $psbBerkasPendukung->file_kk = $filename;
          } elseif ($value == 'ktp') {
            $psbBerkasPendukung->file_ktp = $filename;
          } elseif ($value == 'rapor') {
            $psbBerkasPendukung->file_rapor = $filename;
          }
          $psbBerkasPendukung->psb_peserta_id = $id;
          $psbBerkasPendukung->save();
        }
        $array[] = [
          'code' => 1,
          'status' => 'Success',
          'msg' => 'Data Berhasil Disimpan',
          'location' => $value,
          'ekstensi' => strtolower($ekstensi),
          'photo' => $filename,
        ];
      }
    }
    echo json_encode($array);
  }
  public function get_kota(Request $request)
  {
    $id = $request->prov_id;
    $get_kota = KotaKabTbl::where('id_provinsi', $id)->get();
    echo json_encode($get_kota);
  }
  public function get_kecamatan(Request $request)
  {
    $id_provinsi = $request->prov_id;
    $id_kota = $request->kota_id;
    $get_kecamatan = KecamatanTbl::where('id_provinsi', $id_provinsi)
      ->where('id_kota_kab', $id_kota)
      ->get();
    echo json_encode($get_kecamatan);
  }
  public function get_kelurahan(Request $request)
  {
    $id_provinsi = $request->prov_id;
    $id_kota = $request->kota_id;
    $id_kecamatan = $request->kecamatan_id;
    $get_kelurahan = KelurahanTbl::where('id_provinsi', $id_provinsi)
      ->where('id_kota_kab', $id_kota)
      ->where('id_kecamatan', $id_kecamatan)
      ->get();
    echo json_encode($get_kelurahan);
  }
  public function generate_password()
  {
    // -- Generate Password--
    // $user = UserPsb::all();
    // foreach ($user as $row) {
    //   $psb_peserta = PsbPesertaOnline::where('no_pendaftaran', $row->username);
    //   //echo $psb_peserta->toSql() . 'RF.ppatq.011.23';
    //   if ($psb_peserta->count() > 0) {
    //     $psb_peserta = $psb_peserta->first();
    //     $update_user = UserPsb::find($row->id);
    //     $tahun = date('Y', $psb_peserta->tanggal_lahir);
    //     $bt = date('dm', $psb_peserta->tanggal_lahir);
    //     $nama = substr($psb_peserta->nama, 0, 3);
    //     $update_user->password_ori = $tahun . $nama . $bt;
    //     if ($update_user->save()) {
    //       echo 'berhasil';
    //       echo '<br />';
    //     }
    //   }
    // }
    // -- Generate id pendaftar --
    $gelombang = PsbGelombang::where('pmb_online',1)->first();
    $tahunAjaran = TahunAjaran::where('id',$gelombang->tahun)->first();
    $peserta = PsbPesertaOnline::where('gelombang_id',$gelombang->id)->where('id_pendaftar',0)->orderBy('id','asc')->get();
    $last = PsbPesertaOnline::where('gelombang_id',$gelombang->id)->where('id_pendaftar','<>','0')->orderBy('id_pendaftar','desc')->first()->id_pendaftar;
    echo "id terakhir : " . $last . "<br />";

    foreach($peserta as $row){
      $id_pendaftar = ++$last;
      $str_id = "";
      if(strlen($id_pendaftar) == 1){
          $str_id = "00" . $id_pendaftar;
      }elseif(strlen($id_pendaftar) == 2){
          $str_id = "0" . $id_pendaftar;
      }else{
          $str_id = (string)$id_pendaftar;
      }
      $no_pendaftaran = "RF.ppatq." . $str_id . "." . substr($tahunAjaran->akhir, -2);
      $password = date('d-m-Y', $row->tanggal_lahir);
      $new_peserta = PsbPesertaOnline::find($row->id);
      $new_peserta->no_pendaftaran = $no_pendaftaran;
      $new_peserta->id_pendaftar = $id_pendaftar;
      $new_peserta->save();
      //yanuar athalarik tidak ketemu

      $user = UserPsb::where('nama',$row->nama);
      $status = "tidak ketemu";
      if($user->count() > 0){
        $user = $user->first();
        $new_user = UserPsb::find($user->id);
        $new_user->no_pendaftaran = $no_pendaftaran;
        $new_user->password = md5($password);
        $new_user->password_ori = $password;
        $new_user->save();
        $status = "ketemu";
      }
      //

      echo $row->nama . " " . $id_pendaftar . " " . $no_pendaftaran . " - " . $password . " --- " . $status . "<br />";

    }
  }
  public function ujian(Request $request)
  {
    if (empty($request->input('length'))) {
      $title = 'Ujian';
      $indexed = $this->indexed2;
      $pesan = TemplatePesan::where('status', 1)->first();
      $psb_peserta = PsbPesertaOnline::where('status', 2)
        ->whereNotNull('no_test')
        ->get();
      foreach ($psb_peserta as $row) {
        $update_peserta = PsbPesertaOnline::find($row->id);
        $pecah = explode('.', $row->no_pendaftaran);
        $update_peserta->no_test = $pecah[2];
        $update_peserta->save();
      }
      return view('admin.psb.ujian', compact('title', 'indexed', 'pesan'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'no_pendaftaran',
        3 => 'nama',
        4 => 'ttl',
        5 => 'bayar',
      ];

      $search = [];

      $totalData = PsbPesertaOnline::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $PsbPesertaOnline = PsbPesertaOnline::where('status', 2)
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $PsbPesertaOnline = PsbPesertaOnline::where('status', 2)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
          })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = PsbPesertaOnline::where('status', 2)
          ->where('jabatan_new', 12)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
          })
          ->count();
      }

      $data = [];

      if (!empty($PsbPesertaOnline)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($PsbPesertaOnline as $row) {
          $bukti_bayar = 0;
          $bukti = PsbBuktiPembayaran::where('psb_peserta_id', $row->id);
          if ($bukti->count() > 0) {
            $bukti_bayar = $bukti->first()->status;
          }
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['no_pendaftaran'] = $row->no_pendaftaran . '';
          $nestedData['nama'] = $row->nama ?? '';
          $nestedData['ttl'] = $row->tempat_lahir . ', ' . date('d-m-Y', $row->tanggal_lahir) . '';
          $nestedData['bayar'] = $bukti_bayar;
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
  public function simpan_template_pesan(Request $request)
  {
    $id = $request->id;
    $pesan = $request->template_pesan;
    $get_pesan = TemplatePesan::find($id);
    $get_pesan->pesan = $pesan;
    return $get_pesan->save();
  }
  public function edit_ujian($id)
  {
    $peserta = PsbPesertaOnline::where('id', $id)->first();
    $walisan = PsbWaliPesertum::where('psb_peserta_id', $id)->first();
    $user = UserPsb::where('no_pendaftaran', $peserta->no_pendaftaran)->first();
    $template_pesan = TemplatePesan::where('status', 1)->first();

    $pesan = str_replace('{{nama}}', $peserta->nama, $template_pesan->pesan);
    $pesan = str_replace('{{tanggal_validasi}}', date('Y-m-d H:i:s', $peserta->tanggal_validasi), $pesan);
    $pesan = str_replace('{{no_test}}', $peserta->no_test, $pesan);
    $pesan = str_replace('{{username}}', $user->username, $pesan);
    $pesan = str_replace('{{password}}', $user->password_ori, $pesan);

    $data['pesan'] = $pesan;
    $data['nama'] = $peserta->nama;
    $data['no_hp'] = $walisan->no_hp;
    return $data;
  }
  public function kirim_wa(Request $request)
  {
    $no_hp = $request->no_hp;
    $pesan = $request->pesan;
    $data['no_wa'] = $request->no_hp;
    $data['pesan'] = $pesan;

    Helpers_wa::send_wa($data);
  }
  public function kirim_file_pengumuman($id)
  {
    $peserta = PsbPesertaOnline::where('id', $id)->first();
    $walisan = PsbWaliPesertum::where('psb_peserta_id', $id)->first();

    $data['no_wa'] = $walisan->no_hp;
    $data['file'] = 'https://manajemen.ppatq-rf.id/assets/file/pengumuman-2025.pdf';

    if (Helpers_wa::send_wa_file($data)) {
      $update_peserta = PsbPesertaOnline::find($id);
      $update_peserta->pengumuman_validasi_wa = 1;
      $update_peserta->save();
    } else {
      echo 'gagal Kirim File';
    }
  }
  public function kirim_file_warning($id)
  {
    $peserta = PsbPesertaOnline::where('id', $id)->first();
    $walisan = PsbWaliPesertum::where('psb_peserta_id', $id)->first();
    $user = UserPsb::where('no_pendaftaran', $peserta->no_pendaftaran)->first();
    $pesan = "*Otomatis dari Sistem Manajamen PSB PPATQ.*

Mohon maaf,
pada catatan Panitia Penerimaan Santri Baru PPATQ-RF, belum ada bukti bayar/transfer *biaya pendaftaran*

Untuk itu segera melakukan pembayaran atau jika sudah melakukan pembayaran *biaya pendaftaran*, mohon segera dilaporan melalui alamat psb.ppatq-rf.id dengan

    username : {{username}}
    password : {{password}}

    atau

kirim bukti bayar ke nomor
0822 9857 6026 (ust. Aris).

Karena akan digunakan rujukan dikirimnya syarat-syarat mengikuti test seleksi.

Kami sampaikan permohonan maaf, jika ada WA ini karena mengganggu dan abaikan apabila sudah melakukan pembayaran.

Silakan dipastikan telah mendapatkan syarat & informasi untuk mengikuti test seleksi santri baru PPATQ-RF.

Terimakasih.";
    $pesan = str_replace('{{username}}', $user->username, $pesan);
    $pesan = str_replace('{{password}}', $user->password_ori, $pesan);
    $data['no_wa'] = $walisan->no_hp;
    $data['pesan'] = $pesan;

    if (Helpers_wa::send_wa($data)) {
      $update_peserta = PsbPesertaOnline::find($id);
      $update_peserta->warning_pembayaran_wa = 1;
      $update_peserta->save();
      echo 'Berhasil';
    } else {
      echo 'gagal Kirim File';
    }
  }
  public function exportData()
  {
    return Excel::download(new PsbExport(), 'DataPSB.xlsx');
  }
  public function no_wa(String $id){
    $peserta[] = PsbPesertaOnline::where('id',$id)->first();
    $peserta[] = PsbWaliPesertum::where('psb_peserta_id', $id)->first();
    $user = UserPsb::where('no_pendaftaran',$peserta[0]->no_pendaftaran)->first();
    $peserta['pesan'] = "*Pesan ini dikirim dari sistem PSB PPATQ-RF*

Selamat
nama : " . $peserta[0]->nama . "
no pendaftaran : " . $peserta[0]->no_pendaftaran . "

telah terdaftar pada web sebagai peserta test seleksi  Peserta Didik Baru PPATQ Radlatul Falah Pati

Silahkan catat username dan password di bawah ini untuk dapat mengubah dan melengkapi data

username : " . $user->username . "
password : " . $user->password_ori . "

Selanjutnya silahkan login di sistem dan melaporkan pembayaran Uang pendaftaran di rekening

BSI. 7141299818 a/n
PONPES ANAK TAHFIDZUL QUR'AN RF
melalui psb.ppatq-rf.id menu Pembayaran dan juga dapat melakukan pengkinian data - dokumen pelengkap.

terimakasih


#simpanWA_ini";
    return $peserta;
  }
  public function resend_wa(Request $request){
    $data['no_wa'] = $request->no_telp;
    $data['pesan'] = $request->pesan;

    if (Helpers_wa::send_wa($data)) {
      return redirect()->back();
      //echo 'Berhasil';

    } else {
      return redirect()->back();
    }
  }
}
