@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-murroby-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='ketahfidzan'>
<input type="hidden" name="title" id='title' value='Ketahfidzan'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>Jenis Kelamin</th>
          <th>Jabatan</th>
          <th>Alamat</th>
          <th>Pendidikan</th>
          <th>Jumlah Santri</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection
