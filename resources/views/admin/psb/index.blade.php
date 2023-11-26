@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-psb-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
      <li class="nav-item"><a class="nav-link {{ (Request::segment(1)=='psb' && empty(Request::segment(2)))?'active':'' }}" href="{{url('psb')}}"><i class='mdi mdi-account-outline me-1 mdi-20px'></i>Dafar Calon Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='validasi')?'active':'' }}" href="{{url('psb/validasi')}}"><i class='mdi mdi-account-multiple-outline me-1 mdi-20px'></i>Validasi</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='ujian')?'active':'' }}" href="{{url('psb/ujian')}}"><i class='mdi mdi-pencil me-1 mdi-20px'></i>Ujian</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='pengumuman')?'active':'' }}" href="{{url('psb/pengumuman')}}"><i class='mdi mdi-bulletin-board me-1 mdi-20px'></i>Pengumuman</a></li>
    </ul>
  </div>
</div>
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Daftar Peserta PSB</h5>
  </div>
  <div class="card-datatable table-responsive">

    <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
    <input type="hidden" name="page" id='page' value='psb'>
    <input type="hidden" name="title" id='title' value='Psb'>
    <a href="{{URL::to('psb/create')}}" class="btn btn-primary" style="margin-left:15px">+ Santri Baru</a>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>No. Pendaftaran</th>
          <th>Nama</th>
          <th>TTL</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection
