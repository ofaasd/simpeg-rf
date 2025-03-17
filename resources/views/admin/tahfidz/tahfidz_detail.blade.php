@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css')}}" />
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/autosize/autosize.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-profile.js')}}"></script>
<script src="{{asset('assets/js/forms-selects.js')}}"></script>
<script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
<script src="{{asset('js/laravel-detail-santri-admin.js')}}"></script>
@endsection

@section('content')

@include('ustadz/tahfidz/header')
<!-- Navbar pills -->
@include('ustadz/tahfidz/nav')
<!--/ Navbar pills -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Manajemen Tahfidz Santri</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" id="new_id_pegawai" name="new_id_pegawai" value="{{$id}}">
<input type="hidden" name="page" id='page' value='detail_ketahfidzan'>
<input type="hidden" name="title" id='title' value='{{strtolower($page)}}'>
    <table class="datatables-{{strtolower($page)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>No. Induk</th>
          <th>Bulan</th>
          <th>Kode Juz Surah</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new {{strtolower($title)}} -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAdd{{strtolower($page)}}" aria-labelledby="offcanvasAdd{{$page}}Label" >
    <div class="offcanvas-header">
      <h5 id="offcanvasAdd{{$page}}Label" class="offcanvas-title">Add {{$page}}</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-{{strtolower($page)}} pt-0" id="addNew{{strtolower($page)}}Form">
        <input type="hidden" name="id" id="{{strtolower($page)}}_id">
        <input type="hidden" name="id_tahfidz" id="{{strtolower($page)}}_id_tahfidz" value='{{$var['id_tahfidz']}}'>
        <input type="hidden" id="add-{{strtolower($page)}}-id_tahun_ajaran" name="id_tahun_ajaran" value="{{$ta->id}}">
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($page)}}-no_induk" name="no_induk" >
            @foreach($var['list_santri'] as $row)
              <option value='{{$row->no_induk}}'>{{$row->nama}} - {{$row->kelas}} - {{$row->nama_murroby}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-no_induk">Santri</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="date" name="tanggal" class="form-control" id="add-{{strtolower($page)}}-tanggal">
          <label for="add-{{strtolower($title)}}-tanggal">Tanggal</label>
        </div>
        <!-- <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($page)}}-bulan" name="bulan" >
            @foreach($var['bulan'] as $key=>$value)
              <option value='{{$key}}' {{($key == date('m'))?"selected":""}}>{{$value}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-bulan">Bulan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($page)}}-tahun" name="tahun" >
            @for($i=(int)date('Y'); $i >= ((int)date('Y') - 5); $i--)
              <option value='{{$i}}'>{{$i}}</option>
            @endfor
          </select>
          <label for="add-{{strtolower($title)}}-tahun">Tahun</label>
        </div> -->
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($page)}}-kode_juz_surah" name="kode_juz_surah" >
            @foreach($var['kode_juz'] as $row)
              <option value='{{$row->id}}'>{{$row->nama}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-kode_juz_surah">Kode Juz Surah</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
      </form>
    </div>
  </div>
</div>
@endsection
