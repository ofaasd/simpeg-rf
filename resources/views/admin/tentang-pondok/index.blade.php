@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
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
    <div class="col-xl-12">
        <!-- Account details card-->
        <div class="card mb-4">
            <div class="card-header">Tentang PPATQ</div>
            <div class="card-body">
                <form id="form-tentang-pondok" enctype="multipart/form-data" method="POST" onsubmit="return false">
                    <input type="hidden" name="idTentang" id="id-tentang-pondok" value="{{ $dataTentang->id }}">
                    <div class="row gx-3 mb-3">
                        <div class="col-12 col-md-12">
                            <label for="tentang" class="py-2">Tentang</label>
                            <input id="tentang" type="hidden" name="tentang" value="{{ $dataTentang->tentang }}">
                            <trix-editor id="trix_id" input="tentang" placeholder="ketik disini..."></trix-editor>
                        </div>
                        <div class="col-12 col-md-12 mt-4">
                            <label for="visi" class="py-2">Visi</label>
                            <input id="visi" type="hidden" name="visi" value="{{ $dataTentang->visi }}">
                            <trix-editor id="trix_id" input="visi" placeholder="ketik disini..."></trix-editor>
                        </div>
                        <div class="col-12 col-md-12 mt-4">
                            <label for="misi" class="py-2">Misi</label>
                            <input id="misi" type="hidden" name="misi" value="{{ $dataTentang->misi }}">
                            <trix-editor id="trix_id" input="misi" placeholder="ketik disini..."></trix-editor>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <label class="small mb-1" for="alamat">Alamat</label>
                                <input class="form-control" id="alamat" name="alamat" type="text" value="{{ $dataTentang->alamat }}">
                            </div>
                            <div class="col">
                                <label class="small mb-1" for="sekolah">Sekolah</label>
                                <input class="form-control" id="sekolah" name="sekolah" type="text" value="{{ $dataTentang->sekolah }}">
                            </div>
                            <div class="col">
                                <label class="small mb-1" for="nsm">NSM</label>
                                <input class="form-control" id="nsm" name="nsm" type="text" value="{{ $dataTentang->nsm }}">
                            </div>
                            <div class="col">
                                <label class="small mb-1" for="npsn">NPSN</label>
                                <input class="form-control" id="npsn" name="npsn" type="text" value="{{ $dataTentang->npsn }}">
                            </div>
                            <div class="col">
                                <label class="small mb-1" for="nara-hubung">Nara Hubung</label>
                                <input class="form-control" id="nara-hubung" name="naraHubung" type="text" value="{{ $dataTentang->nara_hubung }}">
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-primary d-none" id="btn-submit" type="submit">Save changes</button>
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
            url: ''.concat(baseUrl).concat('about-ppatq'), // URL endpoint
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

                // location.reload(); // Memuat ulang halaman
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
        $('input').on('input', function() {
            $('#btn-submit').removeClass('d-none');
        });

        $('trix-editor').on('trix-change', function() {
            $('#btn-submit').removeClass('d-none');
        });
        
        $('#form-tentang-pondok').submit(function(e) {
            e.preventDefault(); // Mencegah reload halaman

            var formData = new FormData(this);

            $('#btn-submit').prop('disabled', true); // Menonaktifkan tombol submit

            insert_update(formData)
                .done(function() {
                    $('#btn-submit').prop('disabled', false); // Mengaktifkan tombol submit kembali
                })
                .fail(function() {
                    $('#btn-submit').prop('disabled', false); // Mengaktifkan tombol submit kembali
                });
        });

    });
</script>