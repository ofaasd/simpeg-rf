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
<!--/ Navbar pills -->
<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>Post Berita</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-12">
            <form id="form-sinkronisasi" action="{{ route('sinkronisasi') }}" method="GET">
                @csrf
                <button type="submit" id="btn-sinkronisasi" class="btn btn-secondary btn-sm">
                    Sinkronkan Berita
                    @if(session()->has('isNotif') && session('isNotif')) 
                        <span class="bg-danger rounded-pill ms-2 p-2"></span> 
                    @endif
                </button>
                <small>*Membutuhkan waktu yang cukup lama</small>
            </form>
          </div>
          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahBerita" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_berita" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_berita">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Judul</td>
                <td>Kategori</td>
                <td>Penulis</td>
                <td>Status</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_berita">
              @foreach($berita as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->judul }}</td>
                  <td>{{ $row->kategori->nama_kategori ?? '' }}</td>
                  <td>{{ $row->user->name ?? '' }}</td>
                  <td>{{ $row->status }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-berita waves-effect" data-status="berita"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-berita" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- uang Saku Masuk -->
<form id="formBerita"  onsubmit="return false">
  <div class="modal fade" id="modal_berita"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Berita</h3>
            <p class="pt-1">Tambah berita baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id_berita">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='judul' name="judul" class="form-control" placeholder="Judul Berita">
              <label for="judul">Judul Berita</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="kategori" class="form-control" id="kategori_id">
                @foreach($kategori as $value)
                  <option value="{{$value->id}}">{{$value->nama_kategori}}</option>
                @endforeach
              </select>
              <label for="kategori">Kategori</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="status" class="form-control" id="status_id">
                  <option value="1">Publish</option>
                  <option value="0">Unpublish</option>
              </select>
              <label for="status_id">Status</label>
            </div>
          </div>
          <div class="col-12 col-md-12">
            <label for="isi_berita">Isi Berita</label>
            <input id="isi_berita" type="hidden" name="isi_berita">
            <trix-editor id="trix_id" input="isi_berita" placeholder="ketik disini..."></trix-editor>
          </div>
          <div class="col-12 col-md-6" >
            <div class="form-floating form-floating-outline">
              <input type="file" id='thumbnail' name="thumbnail" class="form-control">
              <label for="thumbnail">Thumbnail</label>
              <div class="alert alert-primary" id="link_thumbnail"></div>
            </div>
          </div>
          <div class="col-12 col-md-6" >
            <div class="form-floating form-floating-outline">
              <input type="file" id='foto_isi' name="foto_isi" class="form-control">
              <label for="foto_isi">Foto Isi</label>
              <div class="alert alert-primary" id="link_foto_isi"></div>
            </div>
          </div>
          <div class="col-12 col-md-12 text-center" >
            <div class="form-floating form-floating-outline">
              <button type="submit" class="btn btn-primary me-sm-3 me-1" id="btn-submit">Submit</button>
              <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>



@endsection
<script>
document.addEventListener('trix-file-accept', function(e){
  e.preventDefault();
});
  
function zeroPadded(val) {
  if (val >= 10)
    return val;
  else
    return '0' + val;
};

function insert_update(formData)
{
  $('.loader-container').show();
  $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('post-berita'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(message) {
        console.log(message);
        $('#modal_berita').modal('hide');
        $('.loader-container').hide();
        $('#btn-submit').prop('disabled', false);
        Swal.fire({
          icon: 'success',
          title: 'Successfully!!',
          text: ''.concat('Data ', ' ') + message,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
        reload_table();
      },
      error: function error(err) {
          $('#modal_berita').modal('hide');
          $('#btn-submit').prop('disabled', false);
          $('.loader-container').hide();

          let errorMessage = "Terjadi kesalahan!";
          
          if (err.responseJSON && err.responseJSON.message) {
              errorMessage = err.responseJSON.message;
          } else if (err.responseText) {
              try {
                  let parsedError = JSON.parse(err.responseText);
                  errorMessage = parsedError.message || "Terjadi kesalahan!";
              } catch (e) {
                  console.error("Error parsing responseText:", e);
              }
          }

          Swal.fire({
              title: 'Gagal Menyimpan Data!',
              text: errorMessage,
              icon: 'error',
              customClass: {
                  confirmButton: 'btn btn-success'
              }
          });
      }
    });
}

document.addEventListener("DOMContentLoaded", function(event) {
  $(".select2").select2({
    dropdownParent: $("#modal_sakit")
  });
  $(".select2-sembuh").select2({
    disabled : true,
    dropdownParent: $("#modal_sembuh")
  });
  $('#modal_sakit').on('hidden.bs.modal', function () {
      $('#formBerita').trigger("reset");
  });

  $('#btnTambahBerita').on('click', function(){
    $('#id_berita').val('');
    $('#judul').val('');
    $('#kategori_id').val('');
    $('#status_id').val('');
    $('#isi_berita').val('');
    $('#trix_id').val('');
    $('#thumbnail').val('');
    $('#foto_isi').val('');
  });

  $('.dataTable').dataTable();

  $('#formBerita').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });

  $('#form-sinkronisasi').on('submit', function(e) {
      e.preventDefault();

      var $btn = $('#btn-sinkronisasi');
      var originalText = $btn.html();
      
      $btn.prop('disabled', true);
      $('.loader-container').show();

      // Send AJAX request
      $.ajax({
          url: $(this).attr('action'), 
          type: 'GET', 
          data: $(this).serialize(),
          success: function(response) {
            $('.loader-container').hide();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data Berita berhasil di sinkronkan',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
            });

            $btn.prop('disabled', false);
          },
          error: function(xhr) {
              console.error('Error occurred:', xhr);
              $('.loader-container').hide();
              Swal.fire('Error', 'Terjadi kesalahan saat sinkronisasi!', 'error');

              $btn.prop('disabled', false);
          }
      });
  });

  $(document).on('click', '.edit-berita', function () {
      const id = $(this).data('id');

      $('.loader-container').show();
      // Ambil data dengan GET request
      $.get(''.concat(baseUrl).concat('post-berita/').concat(id, '/edit'), function (data) {
          // Loop untuk memasukkan data ke form
          Object.keys(data).forEach(key => {
              if (data[key] == null) {
                data[key] = '';
              }
              if (key == 'id') {
                  $('#id_berita')
                      .val(data[key])
                      .trigger('change');
              } else if (key == 'thumbnail') {
                  $("#link_thumbnail").html('<a href="' + baseUrl + 'assets/img/upload/berita/thumbnail/' + data[key] + '" target="_blank">Link Gambar</a>');
              } else if (key == 'gambar_dalam') {
                  $("#link_foto_isi").html('<a href="' + baseUrl + 'assets/img/upload/berita/foto_isi/' + data[key] + '" target="_blank">Link Gambar</a>');
              } else if (key == 'isi_berita') {
                  $('#isi_berita').val(data[key]).trigger('change');
                  document.querySelector("trix-editor").editor.loadHTML(data[key]);
              } else {
                  $('#' + key)
                      .val(data[key])
                      .trigger('change');
              }
          });
          $('.loader-container').hide();
          $('#modal_berita').modal('show');
      });
  });

  $(document).on('click', '.delete-berita', function () {
    const id = $(this).data('id');
    // SweetAlert for confirmation of delete
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
            confirmButton: 'btn btn-primary me-3',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then(function (result) {
        $('.loader-container').show();
        if (result.isConfirmed) {
            // Delete the data
            $.ajax({
                type: 'DELETE',
                url: ''.concat(baseUrl, 'post-berita/', id),
                success: function () {
                    $('.loader-container').hide();
                    // Success SweetAlert after successful deletion
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The Record has been deleted!',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                },
                error: function (_error) {
                    console.log(_error);
                    $('.loader-container').hide();
                    // Error SweetAlert in case of failure
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error deleting the record.',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          $('.loader-container').hide();
            Swal.fire({
                title: 'Cancelled',
                text: 'The record is not deleted!',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
            });
        }
    });
});
});

function reload_table(){
  showBlock();
  $.ajax({
    url: ''.concat(baseUrl).concat('post-berita/reload'),
    type: 'GET',
    success: function success(data) {
      $("#table_berita").html(data);
    },
  });
}


</script>
