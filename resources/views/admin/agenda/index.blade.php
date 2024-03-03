@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
@endsection
@section('page-script')
  <script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
  <script src="{{asset('assets/js/forms-editors.js')}}"></script>
@endsection
@section('content')
<style>
  table.dataTable td, table.dataTable th {
    font-size: 0.8em;
  }
  #editor-container {
    height: 100%;
    /* added these styles */
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  #full-editor {
    height: 100%;
    /* added these styles */
    flex: 1;
    overflow-y: auto;
    width: 100%;
  }
</style>
<!--/ Navbar pills -->
<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>Agenda Kegiatan</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">

          <div class="col-md-12 text-right">
            <button type="button" id="btnTambahAgenda" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_agenda" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_agenda">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Nama Agenda</td>
                <td>Tanggal Mulai</td>
                <td>Tanggal Selesai</td>
                <td>Kategori</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_agen">
              @php
              $i = 1;
              @endphp
              @foreach($agenda as $row)
                <tr>
                  <td>{{$i}}</td>
                  <td>{{$row->judul}}</td>
                  <td>{{date('d-m-Y', strtotime($row->tanggal_mulai))}}</td>
                  <td>{{date('d-m-Y', strtotime($row->tanggal_selesai))}}</td>
                  <td>{{$kategori[$row->kategori]}}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit_agenda waves-effect" data-bs-toggle="modal" data-bs-target="#modal_agenda" data-status="agenda"><i class="mdi mdi-pencil me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-record" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
                    </div>
                  </td>
                </tr>
              @php
              $i++;
              @endphp
              @endforeach
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- uang Saku Masuk -->
<form id="formAgenda"  onsubmit="return false">
  <div class="modal fade" id="modal_agenda"  aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Agenda</h3>
            <p class="pt-1">Tambah agenda kegiatan baru</p>
          </div>
          <div class="row g-4">
          <input type="hidden" name="id" id="id_agenda">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='judul' name="judul" class="form-control" placeholder="Nama Agenda">
              <label for="judul">Nama Agenda</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="kategori" class="form-control">
                @foreach($kategori as $key=>$value)
                  <option value="{{$key}}">{{$value}}</option>
                @endforeach
              </select>
              <label for="kategori">Kategori</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="datetime-local" id='tanggal_mulai' name="tanggal_mulai" class="form-control" value="{{date('Y-m-d H:i')}}" step="any">
              <label for="tanggal_mulai">Tanggal Mulai</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="datetime-local" id='tanggal_selesai' name="tanggal_selesai" class="form-control" value="{{date('Y-m-d H:i')}}" step="any">
              <label for="tanggal_selesai">Tanggal Selesai</label>
            </div>
          </div>

          <div class="col-12 col-md-12">
            <div id="editor-container">
              <div id="full-editor"></div>
            </div>
          </div>
          <div class="col-12 col-md-6" >
            <div class="form-floating form-floating-outline">
              <input type="file" id='gambar' name="gambar" class="form-control">
              <label for="gambar">Gambar</label>
              <div class="alert alert-primary" id="link_gambar"></div>
            </div>
          </div>
          <div class="col-12 col-md-12 text-center" >
            <div class="form-floating form-floating-outline">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
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
  $(".select2").select2({
    dropdownParent: $("#modal_sakit")
  });
  $(".select2-sembuh").select2({
    disabled : true,
    dropdownParent: $("#modal_sembuh")
  });
  $('#modal_sakit').on('hidden.bs.modal', function () {
      $('#formAgenda').trigger("reset");
  });
  $('.dataTable').dataTable();
  $('#formAgenda').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append('isi',$("#full-editor").html());
    //showBlock();
    insert_update(formData);
  });

  $(document).on('click', '.edit_agenda', function () {
    const id = $(this).data('id');
    // get data
    $.get(''.concat(baseUrl).concat('agenda/').concat(id, '/edit'), function (data) {
    let date = '';
    Object.keys(data).forEach(key => {
        //console.log(key);

        if(key == 'id'){
          $('#id_agenda')
            .val(data[key])
            .trigger('change');
        }else if(key == 'isi'){
          $("#full-editor").html(data[key]);
        }else if(key == 'gambar'){
          $("#link_gambar").html('<a href="' + baseUrl + 'assets/img/upload/foto_agenda/' + data[key] + '" target="_blank">Link Gambar</a>');
        }else{
          $('#' + key)
              .val(data[key])
              .trigger('change');
        }
    });
    });
  });
  $(document).on('click', '.delete-record', function () {
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
          url: ''.concat(baseUrl).concat('agenda/').concat(id),
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
      url: ''.concat(baseUrl).concat('agenda'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        //showUnblock();
        //hilangkan modal
        $('#modal_agenda').modal('hide');
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
        Swal.fire({
          title: 'Duplicate Entry!',
          text:  'Data Not Saved !',
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
    url: ''.concat(baseUrl).concat('agenda/reload'),
    type: 'POST',
    success: function success(data) {
      $("#table_agenda").html(data);
      showUnblock();
    },
  });
}


</script>
