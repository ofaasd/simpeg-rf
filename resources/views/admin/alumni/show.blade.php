@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

@endsection
@section('page-script')

@endsection
@section('content')
<style>
  table.dataTable td, table.dataTable th {
    font-size: 0.8em;
  }

  trix-toolbar [data-trix-button-group='file-tools']{
    display: none;
  }
</style>
<div class="row">
    <div class="col-2">
        <!-- Profile picture card-->
        <div class="card mb-4 mb-xl-0">
            <div class="card-header">Foto Profil</div>
            <div class="card-body text-center">
                <img class="rounded-circle mb-2 img-fluid" src="{{ asset('assets/img/upload/photo_alumni/'. $dataAlumni->photo) }}" alt="foto {{ $dataAlumni->nama }}">

                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                
                {{-- form --}}

                <button class="btn btn-sm btn-primary" type="button" onclick="document.getElementById('fileProfile').click();">
                    Upload new image
                </button>
            </div>
        </div>
    </div>
    <div class="col-xl-10">
        <!-- Account details card-->
        <div class="card mb-4">
            <div class="card-header">Alumni Detail</div>
            <div class="card-body">
                <form id="form-alumni-detail" enctype="multipart/form-data" method="POST" onsubmit="return false">
                    <input type="file" name="inputProfile" id="fileProfile" style="display:none;" />
                    <input type="hidden" name="noInduk" id="id-alumni-detail" value="{{ $dataAlumni->no_induk }}">
                    <div class="row gx-3 mb-3">
                        <div class="col-1">
                            <label class="small mb-1" for="no-induk">No Induk</label>
                            <input class="form-control" id="no-induk" type="text" placeholder="Masukkan Nama Alumni" value="{{ $dataAlumni->no_induk }}" disabled>
                        </div>
                        <div class="col">
                            <label class="small mb-1" for="nisn">NISN</label>
                            <input class="form-control" id="nisn" name="nisn" type="text" value="{{ $dataAlumni->nisn }}">
                        </div>
                        <div class="col">
                            <label class="small mb-1" for="nik">NIK</label>
                            <input class="form-control" id="nik" name="nik" type="number" value="{{ $dataAlumni->nik }}">
                        </div>
                        <div class="col">
                            <label class="small mb-1" for="angkatan">Angkatan</label>
                            <input class="form-control" id="angkatan" type="number" value="{{ $dataAlumni->angkatan }}" disabled>
                        </div>
                        <div class="col">
                            <label class="small mb-1" for="tahun-lulus">Tahun Lulus</label>
                            <input class="form-control" id="tahun-lulus" name="tahunLulus" type="number" value="{{ $dataAlumni->tahun_lulus }}">
                        </div>
                    </div>
                    
                    <!-- Form Row-->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="nama">Nama</label>
                            <input class="form-control" id="nama" name="nama" type="text" placeholder="Masukkan Nama Alumni" value="{{ $dataAlumni->nama }}">
                        </div>
                        <div class="col-md-3">
                            <label class="small mb-1" for="usia">Usia</label>
                            <input class="form-control" id="usia" name="usia" type="number" placeholder="Masukkan Usia Alumni" value="{{ $dataAlumni->usia }}">
                        </div>
                        <div class="col-md-3">
                            <label class="small mb-1" for="jenis-kelamin">Jenis Kelamin</label>
                            <select class="form-select" name="jenisKelamin" id="jenis-kelamin">
                                <option value="L" {{ $dataAlumni->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ $dataAlumni->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="tempat-lahir">Tempat Lahir</label>
                            <input class="form-control" id="tempat-lahir" name="tempatLahir" type="text" value="{{ $dataAlumni->tempat_lahir }}">
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="tanggal-lahir">Tanggal Lahir</label>
                            <input class="form-control" id="tanggal-lahir" name="tanggalLahir" type="date" value="{{ \Carbon\Carbon::parse($dataAlumni->tanggal_lahir)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="alamat">Alamat</label>
                            <input class="form-control" id="alamat" name="alamat" type="text" value="{{ $dataAlumni->alamat }}">
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="kelurahan">Kelurahan</label>
                            <input class="form-control" id="kelurahan" name="kelurahan" type="text" value="{{ $dataAlumni->kelurahan }}">
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="kecamatan">Kecamatan</label>
                            <input class="form-control" id="kecamatan" name="kecamatan" type="text" value="{{ $dataAlumni->kecamatan }}">
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="kabupaten-kota">Kabupaten/Kota</label>
                            <select class="form-select" name="kabupatenKota" id="kabupaten-kota">
                                @foreach ( $kabupatenKota as $row )
                                    <option value="{{ $row->id }}" {{ $dataAlumni->kabkota == $row->id ? 'selected' : '' }}>{{ $row->nama_kota_kab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="provinsi">Provinsi</label>
                            <select class="form-select" name="provinsi" id="provinsi">
                                @foreach ($provinsi as $row)
                                    <option value="{{ $row->prov_id }}" {{ $dataAlumni->provinsi == $row->prov_id ? 'selected' : '' }}>
                                        {{ $row->prov_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="kode-pos">Kode Pos</label>
                            <input class="form-control" id="kode-pos" name="kodePos" type="text" value="{{ $dataAlumni->kode_pos }}">
                        </div>
                    </div>
                    <div class="row align-items-center py-2">
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                        <div class="col text-center">
                            Keluarga
                        </div>
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col">
                            <label class="small mb-1" for="nik-kk">NIK KK</label>
                            <input class="form-control" id="nik-kk" name="nikKK" type="text" value="{{ $dataAlumni->nik_kk }}">
                        </div>
                        <div class="col">
                            <label class="small mb-1" for="no-hp">No HP</label>
                            <input class="form-control" id="no-hp" name="noHP" type="number" value="{{ $dataAlumni->no_hp }}">
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-4">
                            <label class="small mb-1" for="nama-ayah">Nama Ayah</label>
                            <input class="form-control" id="nama-ayah" name="namaAyah" type="text" value="{{ $dataAlumni->nama_lengkap_ayah }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="pendidikan-ayah">Pendidikan Ayah</label>
                            <input class="form-control" id="pendidikan-ayah" name="pendidikanAyah" type="text" value="{{ $dataAlumni->pendidikan_ayah }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="pekerjaan-ayah">Pekerjaan Ayah</label>
                            <input class="form-control" id="pekerjaan-ayah" name="pekerjaanAyah" type="text" value="{{ $dataAlumni->pekerjaan_ayah }}">
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-4">
                            <label class="small mb-1" for="nama-ibu">Nama Ibu</label>
                            <input class="form-control" id="nama-ibu" type="text" name="namaIbu" value="{{ $dataAlumni->nama_lengkap_ibu }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="pendidikan-ibu">Pendidikan Ibu</label>
                            <input class="form-control" id="pendidikan-ibu" type="text" name="pendidikanIbu" value="{{ $dataAlumni->pendidikan_ibu }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="pekerjaan-ibu">Pekerjaan Ibu</label>
                            <input class="form-control" id="pekerjaan-ibu" type="text" name="pekerjaanIbu" value="{{ $dataAlumni->pekerjaan_ibu }}">
                        </div>
                    </div>
                    <div class="row align-items-center py-2">
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                        <div class="col text-center">
                            Kelulusan
                        </div>
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-4">
                            <label class="small mb-1" for="tahun-masuk">Tahun Masuk MI</label>
                            <input class="form-control" id="tahun-masuk" name="tahunMasukMI" type="number" value="{{ $dataAlumni->tahun_msk_mi }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="nama-pondok">Nama Pondok</label>
                            <input class="form-control" id="nama-pondok" type="text" name="namaPondokMI" value="{{ $dataAlumni->nama_pondok_mi }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="tahun-masuk-pondok">Tahun Masuk Pondok</label>
                            <input class="form-control" id="tahun-masuk-pondok" type="text" name="tahunMasukPondokMI" value="{{ $dataAlumni->tahun_msk_pondok_mi }}">
                        </div>
                    </div>

                    <div class="row align-items-center py-2">
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                        <div class="col text-center">
                            Menengah Pertama
                        </div>
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-4">
                            <label class="small mb-1" for="tahun-masuk-menengah">Tahun Masuk Pendidikan Menengah Pertama</label>
                            <input class="form-control" id="tahun-masuk-menengah" type="number" name="tahunMasukMenengahP" value="{{ $dataAlumni->thn_msk_menengah }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="nama-menengah-pertama">Nama Sekolah Menengah Pertama</label>
                            <input class="form-control" id="nama-menengah-pertama" type="text" name="namaSekolahMenengahP" value="{{ $dataAlumni->nama_sekolah_menengah_pertama }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="nama-pondok-menengah-pertama">Nama Pondok Menengah Pertama</label>
                            <input class="form-control" id="nama-pondok-menengah-pertama" type="text" name="namaPondokMenengahP" value="{{ $dataAlumni->nama_pondok_menengah_pertama }}">
                        </div>
                    </div>

                    <div class="row align-items-center py-2">
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                        <div class="col text-center">
                            Menengah Atas
                        </div>
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-4">
                            <label class="small mb-1" for="tahun-masuk-menengah-atas">Tahun Masuk Pendidikan Menengah Atas</label>
                            <input class="form-control" id="tahun-masuk-menengah-atas" type="number" name="tahunMasukMenengahA" value="{{ $dataAlumni->tahun_msk_menengah_atas }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="nama-menengah-atas">Nama Sekolah Menengah Atas</label>
                            <input class="form-control" id="nama-menengah-atas" type="text" name="namaSekolahMenengahA" value="{{ $dataAlumni->nama_menengah_atas }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="nama-pondok-menengah-atas">Nama Pondok Menengah Atas</label>
                            <input class="form-control" id="nama-pondok-menengah-atas" type="text" name="namaPondokMenengahA" value="{{ $dataAlumni->nama_pondok_menengah_atas }}">
                        </div>
                    </div>

                    <div class="row align-items-center py-2">
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                        <div class="col text-center">
                            Perguruan Tinggi
                        </div>
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-4">
                            <label class="small mb-1" for="tahun-masuk-perguruan-tinggi">Tahun Masuk Perguruan Tinggi</label>
                            <input class="form-control" id="tahun-masuk-perguruan-tinggi" type="number" name="tahunMasukPT" value="{{ $dataAlumni->tahun_msk_pt }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="nama-perguruan-tinggi">Nama Perguruan Tinggi</label>
                            <input class="form-control" id="nama-perguruan-tinggi" type="text" name="namaPT" value="{{ $dataAlumni->nama_pt }}">
                        </div>
                        <div class="col-4">
                            <label class="small mb-1" for="nama-pondok-perguruan-tinggi">Nama Pondok Perguruan Tinggi</label>
                            <input class="form-control" id="nama-pondok-perguruan-tinggi" type="text" name="namaPondokPT" value="{{ $dataAlumni->nama_pondok_pt }}">
                        </div>
                    </div>

                    <div class="row align-items-center py-2">
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                        <div class="col text-center">
                            Profesi
                        </div>
                        <div class="col">
                            <div class="border-top"></div>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-3">
                            <label class="small mb-1" for="tahun-masuk-profesi">Tahun Masuk</label>
                            <input class="form-control" id="tahun-masuk-profesi" type="number" name="tahunMasukProfesi" value="{{ $dataAlumni->tahun_msk_profesi }}">
                        </div>
                        <div class="col-3">
                            <label class="small mb-1" for="nama-perusahaan">Nama Perusahaan/Usaha</label>
                            <input class="form-control" id="nama-perusahaan" type="text" name="namaPerusahaan" value="{{ $dataAlumni->nama_perusahaan }}">
                        </div>
                        <div class="col-3">
                            <label class="small mb-1" for="bidang-profesi">Bidang Profesi</label>
                            <input class="form-control" id="bidang-profesi" type="text" name="bidangProfesi" value="{{ $dataAlumni->bidang_profesi }}">
                        </div>
                        <div class="col-3">
                            <label class="small mb-1" for="posisi-profesi">Posisi</label>
                            <input class="form-control" id="posisi-profesi" type="text" name="posisiProfesi" value="{{ $dataAlumni->posisi_profesi }}">
                        </div>
                    </div>
                    <button class="btn btn-sm btn-primary" id="btn-submit" type="submit">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
<script>
    function insert_update(formData) {
        $('.loader-container').show(); // Menampilkan loader

        // Mengembalikan promise dari $.ajax
        return $.ajax({
            data: formData,
            url: ''.concat(baseUrl).concat('alumni'), // URL endpoint
            type: 'POST', // Metode HTTP
            cache: false, // Tidak menggunakan cache
            contentType: false, // Membiarkan content type default FormData
            processData: false, // Tidak memproses data
            success: function(response) {
                $('.loader-container').hide(); // Menyembunyikan loader
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Updated!',
                    text: response.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });

                location.reload(); // Memuat ulang halaman
            },
            error: function(err) {
                $('.loader-container').hide(); // Menyembunyikan loader
                console.log(err.responseText);
                Swal.fire({
                    title: 'Cannot Save Data!',
                    text: 'Data Not Saved!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function(event) {
        $('#form-alumni-detail').submit(function(e) {
            e.preventDefault(); // Mencegah reload halaman

            var formData = new FormData(this);

            $('#btn-submit').prop('disabled', true); // Menonaktifkan tombol submit

            console.log(formData);
            insert_update(formData)
                .done(function() {
                    $('#btn-submit').prop('disabled', false); // Mengaktifkan tombol submit kembali
                })x
                .fail(function() {
                    $('#btn-submit').prop('disabled', false); // Mengaktifkan tombol submit kembali
                });
        });

    });
</script>
