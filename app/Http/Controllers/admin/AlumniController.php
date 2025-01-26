<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KotaKabTbl;
use App\Models\Province;
use App\Models\ProvinsiTbl;
use Illuminate\Http\Request;
use App\Models\TbAlumniSantriDetail;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      //
      $santri = TbAlumniSantriDetail::all();
      $title = 'Alumni';
      return view('admin.alumni.index', compact('title', 'santri'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input file
        $request->validate([
            'inputProfile' => 'image|mimes:jpg,png|max:5120',
        ]);

        $noInduk = $request->noInduk;

        $dataAlumni = TbAlumniSantriDetail::where('no_induk', $noInduk)->first();

        // Jika ada file inputProfile
        if ($request->hasFile('inputProfile')) {

            // Hapus foto lama jika ada
            if (!empty($dataAlumni->photo)) {
                unlink(public_path('assets/img/upload/photo_alumni/' . $dataAlumni->photo));
            }

            // Proses unggah file baru
            $file = $request->file('inputProfile');
            $fileName = time() . $dataAlumni->no_induk . '.' . $file->getClientOriginalExtension(); // Membuat nama file unik
            $file->move(public_path('assets/img/upload/photo_alumni'), $fileName); // Menyimpan file ke direktori 'uploads/photo_alumni'

            // Update data alumni dengan foto baru
            $dataAlumni->update([
                'photo' => $fileName,
                'photo_location' => public_path('assets/img/upload/photo_alumni'),
                'no_induk' => $request->noInduk,
                'nama'  => $request->nama,
                'usia'  => $request->usia,
                'tahun_lulus'  => $request->tahunLulus,
                'jenis_kelamin' => $request->jenisKelamin,
                'tempat_lahir'  => $request->tempatLahir,
                'tanggal_lahir'  => $request->tanggalLahir,
                'alamat'    => $request->alamat,
                'kelurahan' => $request->kelurahan,
                'kecamatan' => $request->kecamatan,
                'kabkota'   => $request->kabupatenKota,
                'provinsi'   => $request->provinsi,
                'kode_pos'   => $request->kodePos,
                'nik_kk'   => $request->nikKK,
                'no_hp'   => $request->noHP,
                'nama_lengkap_ayah'   => $request->namaAyah,
                'pendidikan_ayah'   => $request->pendidikanAyah,
                'pekerjaan_ayah'   => $request->pekerjaanAyah,
                'nama_lengkap_ibu'   => $request->namaIbu,
                'pendidikan_ibu'   => $request->pendidikanIbu,
                'pekerjaan_ibu'   => $request->pekerjaanIbu,
                'tahun_msk_mi'   => $request->tahunMasukMI,
                'nama_pondok_mi'   => $request->namaPondokMI,
                'tahun_msk_pondok_mi'   => $request->tahunMasukPondokMI,
                'thn_msk_menengah'   => $request->tahunMasukMenengahP,
                'nama_sekolah_menengah_pertama'   => $request->namaSekolahMenengahP,
                'nama_pondok_menengah_pertama'   => $request->namaPondokMenengahP,
                'tahun_msk_menengah_atas'   => $request->tahunMasukMenengahA,
                'nama_menengah_atas'   => $request->namaSekolahMenengahA,
                'nama_pondok_menengah_atas'   => $request->namaPondokMenengahA,
                'tahun_msk_pt'   => $request->tahunMasukPT,
                'nama_pt'   => $request->namaPT,
                'nama_pondok_pt'   => $request->namaPondokPT,
                'tahun_msk_profesi'   => $request->tahunMasukProfesi,
                'nama_perusahaan'   => $request->namaPerusahaan,
                'bidang_profesi'   => $request->bidangProfesi,
                'posisi_profesi'   => $request->posisiProfesi,
            ]);
            
            // Menampilkan pesan sukses
            return response()->json(['message' => 'Berhasil mengubah data dan memperbarui foto profile.'], 200);
        } else {
            // Update tanpa foto
            $dataAlumni->update([
                'no_induk' => $request->noInduk,
                'nama'  => $request->nama,
                'usia'  => $request->usia,
                'tahun_lulus'  => $request->tahunLulus,
                'jenis_kelamin' => $request->jenisKelamin,
                'tempat_lahir'  => $request->tempatLahir,
                'tanggal_lahir'  => $request->tanggalLahir,
                'alamat'    => $request->alamat,
                'kelurahan' => $request->kelurahan,
                'kecamatan' => $request->kecamatan,
                'kabkota'   => $request->kabupatenKota,
                'provinsi'   => $request->provinsi,
                'kode_pos'   => $request->kodePos,
                'nik_kk'   => $request->nikKK,
                'no_hp'   => $request->noHP,
                'nama_lengkap_ayah'   => $request->namaAyah,
                'pendidikan_ayah'   => $request->pendidikanAyah,
                'pekerjaan_ayah'   => $request->pekerjaanAyah,
                'nama_lengkap_ibu'   => $request->namaIbu,
                'pendidikan_ibu'   => $request->pendidikanIbu,
                'pekerjaan_ibu'   => $request->pekerjaanIbu,
                'tahun_msk_mi'   => $request->tahunMasukMI,
                'nama_pondok_mi'   => $request->namaPondokMI,
                'tahun_msk_pondok_mi'   => $request->tahunMasukPondokMI,
                'thn_msk_menengah'   => $request->tahunMasukMenengahP,
                'nama_sekolah_menengah_pertama'   => $request->namaSekolahMenengahP,
                'nama_pondok_menengah_pertama'   => $request->namaPondokMenengahP,
                'tahun_msk_menengah_atas'   => $request->tahunMasukMenengahA,
                'nama_menengah_atas'   => $request->namaSekolahMenengahA,
                'nama_pondok_menengah_atas'   => $request->namaPondokMenengahA,
                'tahun_msk_pt'   => $request->tahunMasukPT,
                'nama_pt'   => $request->namaPT,
                'nama_pondok_pt'   => $request->namaPondokPT,
                'tahun_msk_profesi'   => $request->tahunMasukProfesi,
                'nama_perusahaan'   => $request->namaPerusahaan,
                'bidang_profesi'   => $request->bidangProfesi,
                'posisi_profesi'   => $request->posisiProfesi,
            ]);

            return response()->json(['message' => 'Berhasil mengubah data.'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $noInduk)
    {
        $dataAlumni = TbAlumniSantriDetail::select(
            'tb_alumni_santri_detail.*',
            'tb_alumni.angkatan'
            )
        ->where('tb_alumni_santri_detail.no_induk', $noInduk)
        ->leftJoin('tb_alumni', 'tb_alumni.no_induk', '=', 'tb_alumni_santri_detail.no_induk')
        ->first();
        $title = 'Alumni | ' . $dataAlumni->nama;

        $kabupatenKota = KotaKabTbl::all();
        $provinsi = Province::all();

        return view('admin.alumni.show', compact('title', 'dataAlumni', 'kabupatenKota', 'provinsi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
