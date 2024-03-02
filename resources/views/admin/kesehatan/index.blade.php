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
        <h4>Catatan Kesehatan Santri</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
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
            <button type="button" id="btnTambahSantriSakit" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_sakit" style="float:right">+ Tambah</button>
          </div>
        </div>
        <div id="table_kesehatan">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>Nama</td>
                <td>Kelas</td>
                <td>Murroby</td>
                <td>Tgl Sakit</td>
                <td>Gangguan Kesehatan</td>
                <td>Keterangan</td>
                <td>Tindakan</td>
                <td>Deskripsi</td>
                <td>Keterangan</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_uang_saku">
              @php
              $i = 1;
              @endphp
              @foreach($kesehatan as $row)
                <tr>
                  <td>{{$list_santri[$row->santri_id]->nama}}</td>
                  <td>{{$list_santri[$row->santri_id]->kelas}}</td>
                  <td>{{$list_santri[$row->santri_id]->kamar_id}}</td>
                  <td>{{date('d-m-Y', $row->tanggal_sakit)}}</td>
                  <td>{{$row->sakit}}</td>
                  <td>{{$row->keterangan_sakit}}</td>
                  <td>{{$row->tindakan}}</td>
                  <td>{{$row->keterangan_sembuh}}</td>
                  <td>{{$row->keterangan_sembuh}}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                      {{-- <button type="button" id="btnSakit" data-id="{{$row->id}}" class="btn btn-primary edit_sakit waves-effect" data-bs-toggle="modal" data-bs-target="#modal_sakit" data-status="sakit"><i class="mdi mdi-pencil me-1"></i></button> --}}
                      <button type="button" id="btnSembuh" data-id="{{$row->id}}" class="btn btn-success edit_sakit waves-effect" data-bs-toggle="modal" data-bs-target="#modal_sakit" data-status="sembuh"><i class="mdi mdi-shield-edit me-1"></i></button>
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
<div class="modal fade" id="modal_sakit"  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Santri Sakit</h3>
          <p class="pt-1">Tambah santri yang sedang sakit</p>
        </div>
        <form id="formSakit" class="row g-4" onsubmit="return false">
          <input type="hidden" name="id" id="id_sakit">
          <div class="col-6 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="santri_id" id="santri_id" class="form-control select2">
                @foreach($santri as $row)
                <option value="{{$row->no_induk}}" >{{$row->nama}} - {{$row->kelas}}</option>
                @endforeach
              </select>
              <label for="santri_id">Nama Santri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_sakit' name="tanggal_sakit" class="form-control" value="{{date('Y-m-d')}}" placeholder="tanggal_sakit">
              <label for="tanggal_sakit">Tanggal Sakit</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='sakit' name="sakit" class="form-control" placeholder="memar, flu, demam, typus, DB, cacar, gatal kulit, infeksi dll">
              <label for="sakit">Item Gangguan Kesehatan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='keterangan_sakit' name="keterangan_sakit" class="form-control">
              <label for="keterangan_sakit">Keterangan Sakit</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='tindakan' name="tindakan" class="form-control">
              <label for="tindakan">Tindakan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='keterangan_sembuh' name="keterangan_sembuh" class="form-control">
              <label for="keterangan_sembuh">Deskripsi</label>
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
    dropdownParent: $("#modal_sakit")
  });
  $(".select2-sembuh").select2({
    disabled : true,
    dropdownParent: $("#modal_sembuh")
  });
  $('#modal_sakit').on('hidden.bs.modal', function () {
      $('#formSakit').trigger("reset");
      $(".sembuh_area").hide();
  });
  $('.dataTable').dataTable();
  $('#formSakit').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    //showBlock();
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
    const status = $(this).data('status');
    if(status == "sembuh"){
      $(".sembuh_area").show();
    }else{
      $(".sembuh_area").hide();
    }
    // get data
    $.get(''.concat(baseUrl).concat('kesehatan/').concat(id, '/edit'), function (data) {
    let date = '';
    Object.keys(data).forEach(key => {
        //console.log(key);

        if(key == 'id'){
          $('#id_sakit')
            .val(data[key])
            .trigger('change');
        }else if(key == 'tanggal_sakit'){
          tanggal = parseInt(data[key]) * 1000;
          date = new Date(tanggal).toLocaleString("sv-SE", {timeZone: "Asia/Jakarta"}).slice(0, 10);
          $('#' + key)
            .val(date)
            .trigger('change');
            //alert(date);
        }else if(key == 'tanggal_sembuh'){
          if(parseInt(data[key]) > 0){
            tanggal = parseInt(data[key]) * 1000;
            date = new Date(tanggal).toLocaleString("sv-SE", {timeZone: "Asia/Jakarta"}).slice(0, 10);
            $('#' + key)
              .val(date)
              .trigger('change');
              //alert(date);
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
          url: ''.concat(baseUrl).concat('kesehatan/').concat(id),
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
  const bulan = $("#bulan").val();
  const tahun = $("#tahun").val();
  $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('kesehatan'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        //showUnblock();
        //hilangkan modal
        $('#modal_sakit').modal('hide');
        //reset form
        reload_table(bulan,tahun);
        resetFormSakit();
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
function reload_table(bulan, tahun){
  showBlock();
  $.ajax({
    data: {'bulan' : bulan, "tahun" : tahun},
    url: ''.concat(baseUrl).concat('kesehatan/reload'),
    type: 'POST',
    success: function success(data) {
      $("#table_kesehatan").html(data);
      showUnblock();
    },
  });
}
function resetFormSakit(){
  $("#id_sakit").val("");
  $("#santri_id").val(0).trigger('change');
  $("#sakit").val("");''
  $("#tanggal_sakit").val("");
  $("#keterangan_sakit").val("");
}

</script>
