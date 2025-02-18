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
        <h4>Prestasi</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahPrestasi" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prestasi" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div>
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Nama</td>
                <td>Deskripsi</td>
                <td>Jenis</td>
                <td>Prestasi</td>
                <td>Tingkat</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody>
              @foreach($dataPrestasi as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->namaSantri }}</td>
                  <td>{{ $row->deskripsi }}</td>
                  <td>{{ $row->jenis_text }}</td>
                  <td>{{ $row->prestasi_text }}</td>
                  <td>{{ $row->tingkat_text }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-prestasi waves-effect"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-prestasi" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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
<!-- Form Prestasi -->
<form id="formPrestasi"  onsubmit="return false">
  <div class="modal fade" id="modal-prestasi"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Prestasi</h3>
            <p class="pt-1">Tambah prestasi baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id-prestasi">

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <select name="noInduk" class="form-control" id="no-induk">
                    @foreach ($dataSantri as $row)
                        <option value="{{ $row->no_induk }}">{{ $row->nama }}</option>
                    @endforeach
                </select>
                <label for="no-induk">Nama</label>
              </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <select name="jenis" class="form-control" id="jenis">
                    <option value="olah-raga">Olah raga</option>
                    <option value="seni-ketrampilan">Seni / Ketrampilan</option>
                    <option value="science">Science</option>
                    <option value="agama">Agama</option>
                    <option value="matematik">Matematik</option>
                    <option value="sosial">Sosial</option>
                    <option value="umum">Umum</option>
                </select>
                <label for="jenis">Jenis</label>
              </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <select name="prestasi" class="form-control" id="prestasi">
                    <option value="juara-i">Juara I</option>
                    <option value="juara-ii">Juara II</option>
                    <option value="juara-iii">Juara III</option>
                    <option value="juara-umum">Juara Umum</option>
                    <option value="juara-favorit">Juara Favorit</option>
                    <option value="juara-harapan-i">Juara Harapan I</option>
                    <option value="juara-harapan-ii">Juara Harapan II</option>
                </select>
                <label for="prestasi">Prestasi</label>
              </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
                <select name="tingkat" class="form-control" id="tingkat">
                    <option value="kelurahan">Kelurahan</option>
                    <option value="kecamatan">Kecamatan</option>
                    <option value="kota">Kota</option>
                    <option value="provinsi">Provinsi</option>
                    <option value="nasional">Nasional</option>
                    <option value="internasional">Internasional</option>
                </select>
                <label for="tingkat">Tingkat</label>
              </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='deskripsi' name="deskripsi" class="form-control" placeholder="Deskripsi Prestasi">
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
      url: ''.concat(baseUrl).concat('prestasi'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        $('#modal-prestasi').modal('hide');
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

  $('#formPrestasi').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      // $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });

  $('#btnTambahPrestasi').on('click', function(){
    $('#id-prestasi').val('');
    $('#no-induk').val('');
    $('#deskripsi').val('');
    $('#jenis').val('');
    $('#prestasi').val('');
    $('#tingkat').val('');
    $('#foto').val('');

    $('#link-foto').off('click');
  })

  $(document).on('click', '.edit-prestasi', function () {
    const id = $(this).data('id');
    $('#no-induk').val('');
    $('#deskripsi').val('');
    $('#jenis').val('');
    $('#prestasi').val('');
    $('#tingkat').val('');
    $('#foto').val('');

    $('.loader-container').show();
    // get data
    $.get(''.concat(baseUrl).concat('prestasi/').concat(id, '/edit'), function (data) {
    Object.keys(data).forEach(key => {
        if (data[key] == null) {
            data[key] = '';
        }
        if(key == 'id'){
          $('#id-prestasi')
            .val(data[key])
            .trigger('change');
        }else if(key == 'no_induk'){
          $('#no-induk')
            .val(data[key])
            .trigger('change');
        }else if(key == 'foto'){
            var href = baseUrl + 'assets/img/upload/foto_prestasi/' + data[key]
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
    $('#modal-prestasi').modal('show');
    });
  });

  $(document).on('click', '.delete-prestasi', function () {
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
                url: ''.concat(baseUrl, 'prestasi/', id),
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