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
          <th>Nama</th>
          <th>No. Pendaftaran</th>
          <th>TTL</th>
          <th>No. WA</th>
          <th>Umur</th>
          <th>Jenis Kelamin</th>
          <th>Verifikasi</th>
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
