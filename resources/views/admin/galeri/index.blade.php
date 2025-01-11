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
        <h4>Galeri</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahGaleri" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-galeri" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_jenis_barang">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Nama</td>
                <td>Deskripsi</td>
                <td>Status</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->nama }}</td>
                  <td>{{ $row->deskripsi }}</td>
                  <td><span class="p-1 rounded text-white bg-{{ $row->published == 1 ? 'success' : 'primary' }}">{{ $row->published == 1 ? 'Published' : 'Not Published' }}</span></td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-galeri waves-effect"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-galeri" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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
<form id="formGaleri"  onsubmit="return false">
  <div class="modal fade" id="modal-galeri"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Galeri</h3>
            <p class="pt-1">Tambah galeri baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id-galeri">

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='nama' name="nama" class="form-control" placeholder="cth: Senam">
              <label for="nama">Nama</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='deskripsi' name="deskripsi" class="form-control" placeholder="cth: Santri senam di pagi hari">
              <label for="deskripsi">Deskripsi</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <a class="btn btn-sm btn-primary" id='link-foto' href="#">Lihat Foto</a>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <input type="file" id="foto" name="foto" class="form-control">
                <label>Foto</label>
              </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <select name="status" class="form-control" id="status">
                    <option value="1">Published</option>
                  <option value="0">Not Published</option>
                </select>
                <label for="status">Status</label>
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
function insert_update(formData)
{
  $('.loader-container').show();
  $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('galeri'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        $('#modal-galeri').modal('hide');
        $('.loader-container').hide();
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
        $('.loader-container').hide();
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

document.addEventListener("DOMContentLoaded", function(event) {
  $('.dataTable').dataTable();

  $('#formGaleri').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });

  $('#btnTambahGaleri').on('click', function(){
    $('#id-galeri').val('');
    $('#nama').val('');
    $('#deskripsi').val('');
    $('#status').val('');
    $('#foto').val('');

    $('#link-foto').off('click');
  })

  $(document).on('click', '.edit-galeri', function () {
    const id = $(this).data('id');
    $('#nama').val('');
    $('#deskripsi').val('');
    $('#status').val('');
    $('#foto').val('');

    $('.loader-container').show();
    // get data
    $.get(''.concat(baseUrl).concat('galeri/').concat(id, '/edit'), function (data) {
    Object.keys(data).forEach(key => {
        if (data[key] == null) {
            data[key] = '';
        }
        if(key == 'id'){
          $('#id-galeri')
            .val(data[key])
            .trigger('change');
        }else if(key == 'published'){
          $('#status')
            .val(data[key])
            .trigger('change');
        }else if(key == 'foto'){
            var href = baseUrl + 'assets/img/upload/foto_galeri/' + data[key]
            $('#link-foto')
            .attr('href', href)
            .attr('target', '_blank');
        }else{
          $('#' + key)
            .val(data[key])
            .trigger('change');
        }
    });
    $('.loader-container').hide();
    $('#modal-galeri').modal('show');
    });
  });

  $(document).on('click', '.delete-galeri', function () {
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
            $.ajax({
                type: 'DELETE',
                url: ''.concat(baseUrl, 'galeri/', id),
                success: function () {
                    $('.loader-container').hide();
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
                    $('.loader-container').hide();
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
