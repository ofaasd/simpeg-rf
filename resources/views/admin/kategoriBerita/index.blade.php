@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection
@section('page-script')
  <script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
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
        <h4>Kategori Berita</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">

          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahAgenda" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_kategori" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_kategori">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Nama Kategori</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_kategori">
              @foreach($kategori as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$row->nama_kategori}}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit_kategori waves-effect" data-bs-toggle="modal" data-bs-target="#modal_kategori" data-status="agenda"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete_kategori" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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
<form id="formKategori"  onsubmit="return false">
  <div class="modal fade" id="modal_kategori"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Kategori</h3>
            <p class="pt-1">Tambah kategori baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id_kategori">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='namaKategori' name="nama_kategori" class="form-control" placeholder="Nama Kategori">
              <label for="namaKategori">Nama Kategori</label>
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
function zeroPadded(val) {
  if (val >= 10)
    return val;
  else
    return '0' + val;
}
document.addEventListener("DOMContentLoaded", function(event) {
$('.dataTable').dataTable();

  $('#formKategori').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });

  $(document).on('click', '.edit_kategori', function () {
    const id = $(this).data('id');
    // get data
    $.get(''.concat(baseUrl).concat('kategori-berita/').concat(id, '/edit'), function (data) {
    Object.keys(data).forEach(key => {
        if(key == 'id'){
          $('#id_kategori')
            .val(data[key])
            .trigger('change');
        }else if(key == 'nama_kategori'){
          $('#namaKategori')
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
  
  $(document).on('click', '.delete_kategori', function () {
    const id = $(this).data('id');
    // sweetalert for confirmation of delete
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
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: ''.concat(baseUrl).concat('kategori-berita/').concat(id),
          success: function success() {
            reload_table(bulan,tahun);
          },
          error: function error(_error) {
            console.log(_error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The Record has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
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
      url: ''.concat(baseUrl).concat('kategori-berita'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        //showUnblock();
        //hilangkan modal
        $('#modal_kategori').modal('hide');
        //reset form
        reload_table();
        //refresh table
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Data ', ' ').concat(' Berhasil Ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function error(err) {
        //showUnblock();
        console.log("error : ", err.responseText)
        Swal.fire({
          title: 'Data Not Saved!',
          text:  'Yout data is failed to save !',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
}
function reload_table(){
  showBlock();
  $.ajax({
    url: ''.concat(baseUrl).concat('kategori/reload'),
    type: 'GET',
    success: function success(data) {
      $("#table_kategori").html(data);
      showUnblock();
    },
  });
}


</script>
