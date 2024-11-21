@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-psb-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
@include('admin/psb/menu_psb')
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-md-4">
        <h5 class="card-title mb-0 mt-2">Daftar Peserta PSB</h5>
      </div>
      <div class="col-md-8 text-right">
        <div class="row align-items-center">

          <div class="col-md-8">
            <select name="gelombang" id="gelombang" class="form-control">
              @foreach($gelombang as $row)
                <option value="{{$row->id}}" {{$row->id == $id ? "selected":""}}>{{$row->nama_gel_long}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <a href="{{URL::to('/psb_new/export')}}" class="btn btn-info col-md-12" style="float:right"> Export Excel</a>
          </div>
        </div>


      </div>
    </div>

  </div>
  <div class="card-datatable table-responsive">

    <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
    <input type="hidden" name="page" id='page' value='psb'>
    <input type="hidden" name="title" id='title' value='Psb'>
    <input type="hidden" name="id_gelombang" id="id_gelombang" value="{{$id}}">
    <a href="{{URL::to('psb/create')}}" class="btn btn-primary" style="margin-left:15px">+ Santri Baru</a>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>TTL</th>
          <th>No. WA</th>
          <th>JK</th>
          <th>Daftar</th>
          <th>Uk. Baju</th>
          <th>Berkas</th>
          <th>Valid</th>
          {{-- <th>Verifikasi</th>
          <th>Ujian</th>
          <th>Diterima</th> --}}
          <th></th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    const url = "{!! url('/') !!}"
    $("#gelombang").change(function(){
      window.location = url.concat('/psb_filter/',$(this).val())
    })
  });
  </script>
