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
        <h4>Aset Ruang</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahRuang" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_ruang" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_ruang">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Gedung</td>
                <td>Jenis Ruang</td>
                <td>Lantai</td>
                <td>Ruang</td>
                <td>Kapasitas</td>
                <td>Status</td>
                <td>Catatan</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_ruang">
              @foreach($ruang as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->gedung }}</td>
                  <td>{{ $row->jenisRuang }}</td>
                  <td>{{ $row->lantai }}</td>
                  <td>{{ $row->nama }}</td>
                  <td>{{ $row->kapasitas }}</td>
                  <td>{{ ucwords($row->status) }}</td>
                  <td>{{ $row->catatan }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-ruang waves-effect"data-status="lantai"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-ruang" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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
<form id="formRuang"  onsubmit="return false">
  <div class="modal fade" id="modal_ruang"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Ruang</h3>
            <p class="pt-1">Tambah Ruang baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id">

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="gedung" class="form-control" id="id_gedung">
                @foreach($refGedung as $row)
                  <option value="{{$row->id}}">{{$row->nama}}</option>
                @endforeach
              </select>
              <label for="id_gedung">Gedung</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="jenisRuang" class="form-control" id="id_jenis_ruang">
                @foreach($refJenisRuang as $row)
                  <option value="{{$row->id}}">{{$row->nama}}</option>
                @endforeach
              </select>
              <label for="id_jenis_ruang">Jenis Ruang</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="lantai" class="form-control" id="id_lantai">
                @foreach($refLantai as $row)
                  <option value="{{$row->id}}">{{$row->nama}}</option>
                @endforeach
              </select>
              <label for="id_lantai">Lantai</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='kode_ruang' name="kodeRuang" class="form-control">
              <label for="kode_ruang">Kode Ruang</label>
            </div>
          </div>
          
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='nama' name="nama" class="form-control">
              <label for="nama">Nama Ruang</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="number" id='kapasitas' name="kapasitas" class="form-control" placeholder="cth: 40">
              <label for="kapasitas">Kapasitas</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="status" class="form-control" id="status">
                  <option value="aktif">Aktif</option>
                  <option value="nonaktif">Nonaktif</option>
                  <option value="dialihfungsikan">Dialihfungsikan</option>
              </select>
              <label for="status">Status Ruang</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='catatan' name="catatan" class="form-control">
              <label for="catatan">Catatan</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='last_checking' name="lastChecking" class="form-control">
              <label for="last_checking">Pengecekan Terakhir</label>
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

  $('#formRuang').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });

  $('#btnTambahRuang').on('click', function(){
    $('#id').val('');
    $('#nama').val('');
    $('#kode_ruang').val('');
    $('#kapasitas').val('');
    $('#status').val('');
    $('#catatan').val('');
  })

  $(document).on('click', '.edit-ruang', function () {
    const id = $(this).data('id');
    $('#nama').val('');
    $('#kode_ruang').val('');
    $('#kapasitas').val('');
    $('#status').val('');
    $('#catatan').val('');
    $('.loader-container').show();
    // get data
    $.get(''.concat(baseUrl).concat('aset/ruang/').concat(id, '/edit'), function (data) {
    Object.keys(data).forEach(key => {
      if (data[key] == null) {
          data[key] = '';
      }
        if(key == 'last_checking'){
            var dateOnly = data[key].split(' ')[0];
            $('#' + key)
              .val(dateOnly)
              .trigger('change');
        }else{
          $('#' + key)
            .val(data[key])
            .trigger('change');
        }
    });
    $('#modal_ruang').modal('show');
    $('.loader-container').hide();
    });
  });

  $(document).on('click', '.delete-ruang', function () {
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
                url: ''.concat(baseUrl, 'aset/ruang/', id),
                success: function () {
                  $('#modal_ruang').modal('hide');
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
                    location.reload();
                },
                error: function (_error) {
                  console.log(_error);
                  $('#modal_ruang').modal('hide');
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
  $('.loader-container').show();
  $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('aset/ruang'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        //hilangkan modal
        $('#modal_ruang').modal('hide');
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
        console.log(err.responseText);
        $('#modal_ruang').modal('hide');
        $('.loader-container').hide();
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
