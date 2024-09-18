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

</style>
<!--/ Navbar pills -->
<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>Master Lantai</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahLantai" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_lantai" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_lantai">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Nomor Lantai</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_lantai">
              @foreach($lantai as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->nama }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-lantai waves-effect" data-bs-toggle="modal" data-bs-target="#modal_lantai" data-status="lantai"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-lantai" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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
<form id="formLantai"  onsubmit="return false">
  <div class="modal fade" id="modal_lantai"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Lantai</h3>
            <p class="pt-1">Tambah lantai baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id_lantai">

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="number" id='nama' name="nama" class="form-control" placeholder="Nomor Lantai">
              <label for="nama">Nomor lantai</label>
            </div>
          </div>
          <div class="col-12 col-md-12 text-center" >
            <div class="form-floating form-floating-outline">
              <button type="submit" class="btn btn-primary me-sm-3 me-1 btn-submit">Submit</button>
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
function zeroPadded(val) {
  if (val >= 10)
    return val;
  else
    return '0' + val;
}
document.addEventListener("DOMContentLoaded", function(event) {
  $('.dataTable').dataTable();

  $('#formLantai').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    //showBlock();
    $('.btn-submit').prop('disabled', true);
    insert_update(formData);
    $('.btn-submit').prop('disabled', false);
  });

  $('#btnTambahLantai').on('click', function(){
    $('#nama').val('');
  })

  $(document).on('click', '.edit-lantai', function () {
    const id = $(this).data('id');
    $('#nama').val('');
    
    // get data
    $.get(''.concat(baseUrl).concat('master/aset/lantai/').concat(id, '/edit'), function (data) {
    Object.keys(data).forEach(key => {
        if(key == 'id'){
          $('#id_lantai')
            .val(data[key])
            .trigger('change');
        }else{
          $('#' + key)
            .val(data[key])
            .trigger('change');
        }
    });
    });
  });

  $(document).on('click', '.delete-lantai', function () {
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
        if (result.isConfirmed) {
            // Delete the data
            $.ajax({
                type: 'DELETE',
                url: ''.concat(baseUrl, 'master/aset/lantai/', id),
                success: function () {
                    // Success SweetAlert after successful deletion
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The Record has been deleted!',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                    location.reload();
                },
                error: function (_error) {
                    console.log(_error);
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

function insert_update(formData){
  $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('master/aset/lantai'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        //hilangkan modal
        $('#modal_lantai').modal('hide');
        //reset form
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Data ', ' ').concat(' Berhasil Ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });

        location.reload();
      },
      error: function error(err) {
        //showUnblock();
        console.log(err.responseText);
        Swal.fire({
          title: 'Cant Save Data !',
          text:  'Data Not Saved !',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
}
</script>
