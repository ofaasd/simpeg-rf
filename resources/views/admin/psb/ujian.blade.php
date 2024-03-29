@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-ujian-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
@include('admin/psb/menu_psb')
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">PSB Pengumuman Ujian</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='ujian'>
<input type="hidden" name="url" id='url' value='psb_new/ujian'>
<input type="hidden" name="title" id='title' value='Ujian'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>No. Pendaftaran</th>
          <th>TTL</th>
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
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-no_hp" placeholder="No. HP" name="no_hp" />
          <label for="add-{{strtolower($title)}}-no_hp">No. HP</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea type="text" class="form-control" id="add-{{strtolower($title)}}-pesan" placeholder="Template Pesan" name="pesan" rows="30" style="height:200px"></textarea>
          <label for="add-{{strtolower($title)}}-pesan">Pesan</label>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
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
  });
</script>
@endsection
