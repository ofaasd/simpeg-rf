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
        <h4>Rekening</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahRekening" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_rekening" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_rekening">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>No rekening</td>
                <td>Atas Nama</td>
                <td>Bank</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_rekening">
              @foreach($data as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->noRek }}</td>
                  <td>{{ $row->atasNama }}</td>
                  <td>{{ $row->namaBank }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-rekening waves-effect" data-status="rekening"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-rekening" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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

<form id="formRekening"  onsubmit="return false">
  <div class="modal fade" id="modal_rekening"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Rekening</h3>
            <p class="pt-1">Tambah rekening baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="kode_rekening">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='no' name="no" class="form-control" placeholder="91283535">
              <label for="no">No Rekening</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='atas_nama' name="atasNama" class="form-control" placeholder="Salamudin">
              <label for="atas_nama">Atas Nama</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="kodeBank" class="form-control" id="kode_bank">
                @foreach($refBank as $row)
                  <option value="{{$row->kode}}">{{$row->nama}}</option>
                @endforeach
              </select>
              <label for="kode_bank">Bank</label>
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
      url: ''.concat(baseUrl).concat('rekening'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(message) {
        console.log(message);
        $('#modal_rekening').modal('hide');
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
          $('#modal_rekening').modal('hide');
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
      $('#formRekening').trigger("reset");
  });

  $('#btnTambahRekening').on('click', function(){
    $('#formRekening')[0].reset();
  });

  $('.dataTable').dataTable();

  $('#formRekening').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });


  $(document).on('click', '.edit-rekening', function () {
      const id = $(this).data('id');

      $('.loader-container').show();
      // Ambil data dengan GET request
      $.get(''.concat(baseUrl).concat('rekening/').concat(id, '/edit'), function (data) {
          // Loop untuk memasukkan data ke form
          Object.keys(data).forEach(key => {
              if (data[key] == null) {
                data[key] = '';
              }
              
              if (key == 'id') {
                  $('#kode_rekening')
                      .val(data[key])
                      .trigger('change');
              } else {
                  $('#' + key)
                      .val(data[key])
                      .trigger('change');
              }
          });
          $('.loader-container').hide();
          $('#modal_rekening').modal('show');
      });
  });

  $(document).on('click', '.delete-rekening', function () {
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
                url: ''.concat(baseUrl, 'rekening/', id),
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
                    // console.log(_error);
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
</script>
