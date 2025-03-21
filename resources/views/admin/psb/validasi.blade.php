@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-validasi-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
@include('admin/psb/menu_psb')
<div class="card">  
  <div class="card-header">
    <div class="row">
      <div class="col-md-8">
        <h5 class="card-title mb-0">PSB Validasi Pembayaran</h5>
      </div>
      <div class="col-md-4">
        <select name="gelombang" id="gelombang" class="form-control">
          @foreach($gelombang as $row)
            <option value="{{$row->id}}" {{$row->id == $id ? "selected":""}}>{{$row->nama_gel_long}}</option>
          @endforeach
        </select>
      </div>
    </div>

  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='validasi'>
<input type="hidden" name="url" id='url' value='psb_new/validasi'>
<input type="hidden" name="title" id='title' value='Validasi'>
<input type="hidden" name="id_gelombang" id="id_gelombang" value="{{$id}}">
  <div class="row p-3">
    <div class="col-md-12 text-start">
      <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#templatePesan">Template Pesan</a>
    </div>
  </div>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>No. Pendaftaran</th>
          <th>Daftar Bayar</th>
          <th>Pembayaran</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new {{strtolower($title)}} -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAdd{{$title}}" aria-labelledby="offcanvasAdd{{$title}}Label">
    <div class="offcanvas-header">
      <h5 id="offcanvasAdd{{$title}}Label" class="offcanvas-title">Add {{$title}}</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form enctype="multipart/form-data" class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Form">
        @csrf
        <input type="hidden" name="id" id="{{strtolower($title)}}_id">
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-bank" placeholder="bank Pegirim | Ex : BRI" name="bank" />
          <label for="add-{{strtolower($title)}}-bank">Bank Pengirim</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea  class="form-control" id="add-{{strtolower($title)}}-no_rekening" placeholder="No. Rekening" name="no_rekening" aria-label="John Doe"></textarea>
          <label for="add-{{strtolower($title)}}-no_rekening">No. Rekening</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea  class="form-control" id="add-{{strtolower($title)}}-atas_nama" placeholder="Atas Nama" name="atas_nama" aria-label="Atas Nama"></textarea>
          <label for="add-{{strtolower($title)}}-atas_nama">Atas Nama</label>
        </div>

        <input type="hidden"  class="form-control" id="add-{{strtolower($title)}}-psb_peserta_id" placeholder="Atas Nama" name="psb_peserta_id" aria-label="Atas Nama">

        <div class="form-floating form-floating-outline mb-4" id="bukti_file">

        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="file" class="form-control" id="add-{{strtolower($title)}}-file_bukti" placeholder="" name="bukti" />
          <label for="add-{{strtolower($title)}}-file_bukti">File Bukti</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select name="status" id="add-{{strtolower($title)}}-status" class='form-control'>
            <option value="0">Belum Ada</option>
            <option value="1">Diproses</option>
            <option value="2">Diterima</option>
          </select>
          <label for="add-{{strtolower($title)}}-status">Status</label>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="templatePesan" tabindex="-1" aria-labelledby="templatePesanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form enctype="multipart/form-data" class="template_pesan pt-0" id="template_pesan_form">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Template Pesan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          @csrf
          <input type="hidden" name="id" value="{{$pesan->id}}">
          <div class="form-floating form-floating-outline mb-4">
            <textarea  class="form-control" id="template_pesan_text" placeholder="Template Pesan" name="template_pesan" aria-label="" style="height:200px">{{$pesan->pesan}}</textarea>
            <label for="template_pesan_text">Template Pesan</label>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    $("#template_pesan_form").submit(function(e){
      e.preventDefault();
      const data = $(this).serialize();
      $.ajax({
        url: ''.concat(baseUrl).concat("psb_new").concat('/simpan_template_pesan'),
        data : data,
        method: "POST",
        success :function(data){
          alert("Data berhasil tersimpan");
          $(".modal").modal('hide');
        }
      });
    })
    const url = "{!! url('/') !!}"
    $("#gelombang").change(function(){
      window.location = url.concat('/psb_new/validasi_filter/',$(this).val())
    })
  });
</script>
@endsection
