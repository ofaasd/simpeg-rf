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
        <h4>Dakwah</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahDakwah" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_dakwah" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_dakwah">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Judul</td>
                <td>Penulis</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_dakwah">
              @foreach($dakwah as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->judul }}</td>
                  <td>{{ $row->user->name ?? '' }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-dakwah waves-effect" data-status="dakwah"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-dakwah" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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
<form id="formDakwah"  onsubmit="return false">
  <div class="modal fade" id="modal_dakwah"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Dakwah</h3>
            <p class="pt-1">Tambah dakwah baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id_dakwah">
          <div class="col-12 col-md-8">
            <div class="form-floating form-floating-outline">
              <input type="text" id='judul' name="judul" class="form-control" placeholder="Judul Dakwah">
              <label for="judul">Judul Dakwah</label>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <label for="isi_dakwah">Isi Dakwah</label>
            <input id="isi_dakwah" type="hidden" name="isi_dakwah">
            <trix-editor id="trix_id" input="isi_dakwah" placeholder="ketik disini..."></trix-editor>
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
      url: ''.concat(baseUrl).concat('dakwah'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(message) {
        console.log(message);
        $('#modal_dakwah').modal('hide');
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
          $('#modal_dakwah').modal('hide');
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
      $('#formDakwah').trigger("reset");
  });

  $('#btnTambahDakwah').on('click', function(){
    $('#id_dakwah').val('');
    $('#judul').val('');
    $('#isi_dakwah').val('');
    $('#trix_id').val('');
  });

  $('.dataTable').dataTable();

  $('#formDakwah').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });


  $(document).on('click', '.edit-dakwah', function () {
      const id = $(this).data('id');

      $('.loader-container').show();
      // Ambil data dengan GET request
      $.get(''.concat(baseUrl).concat('dakwah/').concat(id, '/edit'), function (data) {
          // Loop untuk memasukkan data ke form
          Object.keys(data).forEach(key => {
              if (data[key] == null) {
                data[key] = '';
              }
              if (key == 'id') {
                  $('#id_dakwah')
                      .val(data[key])
                      .trigger('change');
              } else if (key == 'isi_dakwah') {
                  $('#isi_dakwah').val(data[key]).trigger('change');
                  document.querySelector("trix-editor").editor.loadHTML(data[key]);
              } else {
                  $('#' + key)
                      .val(data[key])
                      .trigger('change');
              }
          });
          $('.loader-container').hide();
          $('#modal_dakwah').modal('show');
      });
  });

  $(document).on('click', '.delete-dakwah', function () {
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
                url: ''.concat(baseUrl, 'dakwah/', id),
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
    url: ''.concat(baseUrl).concat('dakwah/reload'),
    type: 'GET',
    success: function success(data) {
      $("#table_dakwah").html(data);
    },
  });
}


</script>
