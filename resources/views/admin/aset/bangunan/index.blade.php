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
        <h4>Aset Bangunan</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahBangunan" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_bangunan" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_bangunan">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Nama</td>
                <td>Gedung</td>
                <td>Jumlah Lantai</td>
                <td>Tanah</td>
                <td>Luas</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_bangunan">
              @foreach($bangunan as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->nama }}</td>
                  <td>{{ $row->gedung }}</td>
                  <td>{{ $row->lantai }}</td>
                  <td>{{ $row->tanah }}</td>
                  <td>{{ $row->luas }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                        <button type="button" id="btnView" data-id="{{$row->id}}" class="btn btn-success view-bangunan waves-effect" data-bs-toggle="modal" data-bs-target="#modal_view_bangunan" data-status="view_bangunan"><i class="mdi mdi-eye me-1"></i></button>
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-bangunan waves-effect" data-bs-toggle="modal" data-bs-target="#modal_bangunan" data-status="tanah"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-bangunan" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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

  <div class="modal fade" id="modal_view_bangunan"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Detail</h3>
          </div>
          <div class="row g-4">

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <p>Nama : <span id='view-nama'></span></p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <p>Gedung : <span id='view-gedung'></span></p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <p>Jumlah Lantai : <span id='view-lantai'></span></p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <p>Tanah : <span id='view-tanah'></span></p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <p>Luas : <span id='view-luas'></span> m<sup>2</sup></p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <p> Status : <span id='view-status'></span></p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <p>Kondisi : <span id='view-kondisi'></span></p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <p>Tanggal Pembangunan : <span id='view-tanggal_pembangunan'></span></p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <a class="btn btn-sm btn-primary" id='view-bukti_fisik' href="" target="_blank">Lihat Bukti</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<form id="formBangunan"  onsubmit="return false" enctype="multipart/form-data">
  <div class="modal fade" id="modal_bangunan"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Bangunan</h3>
            <p class="pt-1">Tambah bangunan baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id_bangunan">

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='nama' name="nama" class="form-control">
              <label for="nama">Nama</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <select name="gedung" class="form-control" id="id_gedung">
                    @foreach($gedung as $row)
                      <option value="{{$row->id}}">{{$row->nama}}</option>
                    @endforeach
                  </select>
              <label for="id_gedung">Gedung</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <select name="lantai" class="form-control" id="id_lantai">
                    @foreach($lantai as $row)
                      <option value="{{$row->id}}">{{$row->nama}}</option>
                    @endforeach
                  </select>
              <label for="id_lantai">Jumlah Lantai</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <select name="tanah" class="form-control" id="id_tanah">
                    @foreach($tanah as $row)
                      <option value="{{$row->id}}">{{$row->nama}}</option>
                    @endforeach
                  </select>
              <label for="id_tanah">Tanah</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="luas" class="form-label">Luas</label>
                <div class="input-group">
                    <input type="text" id="luas" name="luas" class="form-control" placeholder="cth: 3 / 3.2">
                    <span class="input-group-text">m<sup>2</sup></span>
                </div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="status" class="form-control" id="status">
                  <option value="hm">HM</option>
                  <option value="sewa">SEWA</option>
              </select>
              <label for="status">Status Bangunan</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="kondisi" class="form-control" id="kondisi">
                  <option value="sangat-bagus">Sangat Bagus</option>
                  <option value="bagus">Bagus</option>
                  <option value="kurang-bagus">Kurang Bagus</option>
                  <option value="buruk">Buruk</option>
              </select>
              <label for="kondisi">Kondisi Bangunan</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_pembangunan' name="tglPembangunan" class="form-control">
              <label for="tanggal_pembangunan">Tanggal Pembangunan</label>
            </div>
          </div>

          {{-- <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <a class="btn btn-sm btn-primary" id='bukti_fisik' href="#">Lihat Bukti Fisik</a>
              <label for="bukti_fisik">Lihat Bukti Fisik</label>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="file" id="buktiFisik" name="buktiFisik" class="form-control">
              <label>Bukti Fisik</label>
            </div>
          </div> --}}
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

  $('#formBangunan').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });

  $('#btnTambahBangunan').on('click', function(){
    $('#nama').val('');
    $('#no_sertifikat').val('');
    $('#tanggal_pembangunan').val('');
    $('#kondisi').val('');
    $('#luas').val('');
    $('#status').val('');
    $('#bukti_fisik')
    .attr('href', '#')
    .removeAttr('target'); 
    $('#buktiFisik').val(''); 

  })

  $(document).on('click', '.view-bangunan', function () {
    const id = $(this).data('id');
    // get data
    $.get(''.concat(baseUrl).concat('aset/bangunan/').concat(id), function (data) {
    Object.keys(data).forEach(key => {
        // if(key == ''){
        //   var dateOnly = data[key];
        //   $('#view' + key)
        //     .val(dateOnly)
        //     .trigger('change');
        // }else{
          $('#view-' + key)
            .text(data[key]);
        // }
    });
    });
  });

  $(document).on('click', '.edit-bangunan', function () {
    const id = $(this).data('id');
    $('#nama').val('');
    $('#no_sertifikat').val('');
    $('#tanggal_pembangunan').val('');
    $('#kondisi').val('');
    $('#luas').val('');
    $('#status').val('');
    $('#buktiFisik').val('');
    // get data
    $.get(''.concat(baseUrl).concat('aset/bangunan/').concat(id, '/edit'), function (data) {
    Object.keys(data).forEach(key => {
        if(key == 'id'){
          $('#id_bangunan')
            .val(data[key])
            .trigger('change');
        }else if(key == 'tanggal_pembangunan'){
          var dateOnly = data[key].split(' ')[0];
          $('#' + key)
            .val(dateOnly)
            .trigger('change');
        }else if(key == 'bukti_fisik'){
            var href = baseUrl + 'assets/img/upload/bukti_fisik_bangunan/' + data[key]
            $('#bukti_fisik')
            .attr('href', href)
            .attr('target', '_blank');;
        }else{
          $('#' + key)
            .val(data[key])
            .trigger('change');
        }
    });
    });
  });

  $(document).on('click', '.delete-bangunan', function () {
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
                url: ''.concat(baseUrl, 'aset/bangunan/', id),
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
      url: ''.concat(baseUrl).concat('aset/bangunan'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        //hilangkan modal
        $('#modal_bangunan').modal('hide');
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
