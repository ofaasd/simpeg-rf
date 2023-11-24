<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PsbPesertaOnline;
use App\Models\PsbSekolahAsal;
use App\Models\PsbWaliPesertum;
use App\Models\UserPsb;
use App\Models\PsbGelombang;
use App\Models\Province;
use App\Models\City;
use App\Helpers\Helpers_wa;
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
  public $indexed = ['', 'id', 'nik', 'no_pendaftaran', 'nama', 'usia', 'status'];
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $title = 'Psb';
      $indexed = $this->indexed;
      return view('admin.psb.index', compact('title', 'indexed'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'nik',
        3 => 'no_pendaftaran',
        4 => 'nama',
        5 => 'usia',
        6 => 'status',
      ];

      $search = [];

      $totalData = PsbPesertaOnline::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $PsbPesertaOnline = PsbPesertaOnline::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $PsbPesertaOnline = PsbPesertaOnline::where(function ($query) use ($search) {
          $query
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->orWhere('nik', 'LIKE', "%{$search}%")
            ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
        })
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = PsbPesertaOnline::where('jabatan_new', 12)
          ->where(function ($query) use ($search) {
            $query
              ->where('id', 'LIKE', "%{$search}%")
              ->orWhere('nama', 'LIKE', "%{$search}%")
              ->orWhere('nik', 'LIKE', "%{$search}%")
              ->orWhere('no_pendaftaran', 'LIKE', "%{$search}%");
          })
          ->count();
      }

      $data = [];

      if (!empty($PsbPesertaOnline)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($PsbPesertaOnline as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['nik'] = $row->nik ?? '';
          $nestedData['no_pendaftaran'] = $row->no_pendaftaran . '';
          $nestedData['nama'] = $row->nama ?? '';
          $nestedData['usia'] = $row->usia_tahun . ' Tahun ' . $row->usia_bulan . ' Bulan';
          $nestedData['status'] = $row->status ?? '';
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
    $title = 'Psb';
    $indexed = $this->indexed;
    $provinsi = Province::all();
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
    if ($cek_hp > 0) {
      $array[] = [
        'code' => 1,
        'status' => 'error',
        'msg' => 'No. HP sudah terdaftar pada sistem',
      ];
      //echo json_encode($array);
      $hitung++;
    }
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

    $nama = $request->nama_panggilan;
    $peserta = PsbPesertaOnline::where(['nama' => $nama, 'tanggal_lahir' => $tanggal_lahir])->count();
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
      $nama = $request->nama_panggilan;
      $tgl_lahir = $request->tanggal_lahir;
      $id = $user->id;
      $new_user = UserPsb::find($id);
      $str_id = '';
      if (strlen($id) == 1) {
        $str_id = '00' . $id;
      } elseif (strlen($id) == 2) {
        $str_id = '0' . $id;
      } else {
        $str_id = str($id);
      }
      $tahun_lahir = date('Y', strtotime($tgl_lahir));
      $new_nama = substr($nama, 0, 3);
      $tanggal = date('dm', strtotime($tgl_lahir));

      //create username and password
      $username = 'RF.ppatq.' . $str_id . '.' . date('y');
      $password = $tahun_lahir . $new_nama . $tanggal;

      $user->username = $username;
      $user->password = md5($password);
      if ($user->save()) {
        //kirim pesan wa disini
        $pesan =
          '*Pesan ini dikirim dari sistem*

Selamat anda sudah terdaftar pada web Penerimaan Peserta Didik Baru PPATQ Radlatul Falah Pati
Silahkan catat username dan password di bawah ini untuk dapat mengubah dan melengkapi data
username : ' .
          $username .
          '
password : ' .
          $password .
          '
Selanjutnya anda dapat melakukan pengkinian data calon santri baru di menu PSB setelah login melalui sistem
https://psb.ppatq-rf.id';
        $data['no_wa'] = $request->no_hp;
        $data['pesan'] = $pesan;

        Helpers_wa::send_wa($data);
      }

      $data = new PsbPesertaOnline();
      $data->nik = $request->nik;
      $data->nama = $request->nama_panggilan;
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
      $data->no_pendaftaran = $username;
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

        $array[] = [
          'code' => 0,
          'status' => 'Success',
          'username' => $username,
          'password' => $password,
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
  }
}
