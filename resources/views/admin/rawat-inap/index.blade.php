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
        <h4>Absen Kesehatan Rawat Inap</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row py-3">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-4">
                <select name="bulan" id="bulan" class="form-control form-control-sm">
                  @foreach($var['list_bulan'] as $key=>$value)
                  <option value={{$key}} {{($key == $var['bulan'])?"selected":""}}>{{$value}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <select name="tahun" id="tahun" class="form-control form-control-sm">
                  @for($i=date('Y'); $i>(int)(date('Y'))-5; $i--)
                  <option value={{$i}} {{($i == $var['tahun'])?"selected":""}}>{{$i}}</option >
                  @endfor
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-6 text-right">
            <button type="button" id="btnTambahSantriInap" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_rawat_inap" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_kesehatan">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>Nama</td>
                <td>Kelas</td>
                <td>Murroby</td>
                <td>Tanggal Masuk</td>
                <td>Keluhan</td>
                <td>Terapi</td>
                <td>Tanggal Keluar</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody>
              @foreach($rawatInap as $row)
                <tr>
                  <td>{{$row->namaSantri}}</td>
                  <td>{{$row->kelas}}</td>
                  <td>{{$row->namaMurroby}}</td>
                  <td>{{date('d-m-Y', $row->tanggal_masuk)}}</td>
                  <td>{{$row->keluhan}}</td>
                  <td>{{$row->terapi}}</td>
                  <td>{{ $row->tanggal_keluar == 0 ? '-' : date('d-m-Y', $row->tanggal_keluar) }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      <button type="button" id="btnSembuh" data-id="{{$row->id}}" class="btn btn-success edit_sakit waves-effect" data-bs-toggle="modal" data-bs-target="#modal_rawat_inap" data-status="sembuh"><i class="mdi mdi-shield-edit me-1"></i></button>
                      <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-record" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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
<div class="modal fade" id="modal_rawat_inap"  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Santri Sakit</h3>
          <p class="pt-1">Tambah santri yang sedang dirawat inap</p>
        </div>
        <form id="formSakit" class="row g-4" onsubmit="return false">
          <input type="hidden" name="id" id="id_sakit">
          <div class="col-6 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="santri_id" id="santri_id" class="form-control select2">
                @foreach($santri as $row)
                <option value="{{$row->no_induk}}" >{{ $row->no_induk }} - {{$row->nama}}</option>
                @endforeach
              </select>
              <label for="santri_id">No Induk - Nama Santri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_masuk' name="tanggal_masuk" class="form-control" value="{{date('Y-m-d')}}" placeholder="Tanggal Masuk">
              <label for="tanggal_masuk">Tanggal Masuk</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='keluhan' name="keluhan" class="form-control">
              <label for="keluhan">Keluhan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='terapi' name="terapi" class="form-control">
              <label for="terapi">Terapi</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_keluar' name="tanggal_keluar" class="form-control" placeholder="Tanggal Keluar">
              <label for="tanggal_keluar">Tanggal Keluar</label>
            </div>
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



@endsection
<script>
document.addEventListener("DOMContentLoaded", function(event) {
  $(".select2").select2({
    dropdownParent: $("#modal_rawat_inap")
  });
  $(".select2-sembuh").select2({
    disabled : true,
    dropdownParent: $("#modal_sembuh")
  });
  $('#modal_rawat_inap').on('hidden.bs.modal', function () {
      $('#formSakit').trigger("reset");
      $(".sembuh_area").hide();
  });
  $('.dataTable').dataTable();
  $('#formSakit').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    insert_update(formData);
  });
  $("#bulan").change(function(){
    const bulan = $(this).val();
    const tahun = $("#tahun").val();
    reload_table(bulan,tahun);
  });
  $("#tahun").change(function(){
    const bulan = $("#bulan").val();
    const tahun = $(this).val();
    reload_table(bulan,tahun);
  });
  $(document).on('click', '.edit_sakit', function () {
    const id = $(this).data('id');
    // get data
    $.get(''.concat(baseUrl).concat('rawat-inap/').concat(id, '/edit'), function (data) {
    let date = '';
    Object.keys(data).forEach(key => {
        console.log(data[key]);

        if(key == 'id'){
          $('#id_sakit')
            .val(data[key])
            .trigger('change');
        }else if(key == 'santri_no_induk'){
          $('#santri_id')
            .val(data[key])
            .trigger('change');
        }else if(key == 'tanggal_masuk'){
          tanggal = parseInt(data[key]) * 1000;
          date = new Date(tanggal).toLocaleString("sv-SE", {timeZone: "Asia/Jakarta"}).slice(0, 10);
          $('#' + key)
            .val(date)
            .trigger('change');
        }else if(key == 'tanggal_keluar'){
          if(parseInt(data[key]) > 0){
            tanggal = parseInt(data[key]) * 1000;
            date = new Date(tanggal).toLocaleString("sv-SE", {timeZone: "Asia/Jakarta"}).slice(0, 10);
            $('#' + key)
                .val(date)
                .trigger('change');
          }
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
    const bulan = $("#bulan").val();
    const tahun = $("#tahun").val();

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
          url: ''.concat(baseUrl).concat('rawat-inap/').concat(id),
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

function insert_update(formData)
{
  const bulan = $("#bulan").val();
  const tahun = $("#tahun").val();
  $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('rawat-inap'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        $('#modal_rawat_inap').modal('hide');
        reload_table(bulan,tahun);
        resetFormSakit();
        location.reload();
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
            let error = err.responseJSON;
            let errorMessage = error.errors.keluhan ? error.errors.keluhan.join(', ') : 'Terjadi kesalahan';

            Swal.fire({
                title: 'Data Not Saved!',
                text: errorMessage,
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
            });
        }
    });
}

function reload_table(bulan, tahun){
  showBlock();
  $.ajax({
    data: {'bulan' : bulan, "tahun" : tahun},
    url: ''.concat(baseUrl).concat('rawat-inap/reload'),
    type: 'POST',
    success: function success(data) {
        $("#table_kesehatan").html(data);
        showUnblock();
    },
  });
}

function resetFormSakit()
{
  $("#id_sakit").val("");
  $("#santri_id").val(0).trigger('change');
  $("#sakit").val("");
  $("#kelas_id").val("");
  $("#murroby_id").val("");
  $("#tanggal_masuk").val("");
  $("#tanggal_keluar").val("");
}

</script>