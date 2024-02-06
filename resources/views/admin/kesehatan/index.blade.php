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
      <div class="card-body">
        <div id="table_kesehatan">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>Nama</td>
                <td>Kelas</td>
                <td>Murroby</td>
                <td>Sakit</td>
                <td>Keterangan</td>
                <td>Aksi</td>
              </tr>
            </thead>
            <tbody id="table_uang_saku">
              @php
              $i = 1;
              @endphp
              @foreach($santri as $row)
                <tr>
                  <td>{{$row->nama}}</td>
                  <td>{{$row->kelas}}</td>
                  <td>{{$row->kamar_id}}</td>
                  <td>{{$row->status_kesehatan}}</td>
                  <td><button type="button" id="status" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#status_kesehatan"><i class="mdi mdi-shield-edit me-1"></i></button></td>
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
<div class="modal fade" id="status_kesehatan"  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Status Kesehatan</h3>
          <p class="pt-1">Status kesehatan santri pondok</p>
        </div>
        <form id="formSakuMasuk" class="row g-4" onsubmit="return false">
          <input type="hidden" name="jenis" value="saku_masuk">
          <input type="hidden" name="id_uang_masuk" id="id_uang_masuk">
          <div class="col-6 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="santri_id" id="santri_id" class="form-control select2">
                @foreach($santri as $row)
                <option value="{{$row->id}}" >{{$row->nama}} - {{$row->kelas}}</option>
                @endforeach
              </select>
              <label for="santri_id">Nama Santri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='sakit' name="sakit" class="form-control">
              <label for="sakit">Sakit</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_sakit' name="tanggal_sakit" class="form-control">
              <label for="tanggal_sakit">Tanggal Sakit</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='keterangan_sakit' name="keterangan_sakit" class="form-control">
              <label for="keterangan_sakit">Keterangan Sakit</label>
            </div>
          </div>
          {{-- <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_sembuh' name="tanggal_sembuh" class="form-control">
              <label for="tanggal_sembuh">Tanggal Sembuh</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='keterangan_sembuh' name="keterangan_sembuh" class="form-control">
              <label for="keterangan_sembuh">Keterangan Sembuh</label>
            </div>
          </div> --}}
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
    dropdownParent: $("#status_kesehatan")
  });
  $('.dataTable').dataTable();
  $('#formSakuKeluar').submit(function(e) {
    e.preventDefault();
    const bulan = $("#bulan").val();
    const tahun = $("#tahun").val();
    var formData = new FormData(this);
    //showBlock();
    $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('admin/uang_keluar/store'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        //showUnblock();
        //hilangkan modal
        $('#uangKeluar').modal('hide');
        //reset form
        reload_table(bulan,tahun);
        resetFormUangKeluar();
        //refresh table
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Pengeluaran ', ' ').concat(' Berhasil Ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function error(err) {
        //showUnblock();
        Swal.fire({
          title: 'Duplicate Entry!',
          text: title + ' Not Saved !',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
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
});
function reload_table(bulan, tahun){
  showBlock();
  $.ajax({
      data: {'bulan' : bulan, "tahun" : tahun},
      url: ''.concat(baseUrl).concat('admin/akuntansi/get_all'),
      type: 'POST',
      success: function success(data) {
        $("#table_akuntansi").html(data);
        showUnblock();
      },
    });
}

</script>
