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
<script src="{{asset('js/laravel-detail-santri.js')}}"></script>
@endsection

@section('content')

@include('ustadz/murroby/header')
<!-- Navbar pills -->
@include('ustadz/murroby/nav')
<!--/ Navbar pills -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Manajemen Saku Masuk</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='saku_masuk'>
<input type="hidden" name="title" id='title' value='{{strtolower($page)}}'>
    <table class="datatables-{{strtolower($page)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>Dari</th>
          <th>Jumlah (Rp.)</th>
          <th>Tanggal</th>
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
        <div class="form-floating form-floating-outline mb-4">
          <select id="add-{{strtolower($page)}}-no_induk" name="no_induk" class="form-control">
            @foreach($var['list_santri'] as $santri)
            <option value='{{$santri->no_induk}}'>{{$santri->nama}}</option>
            @endforeach
          </select>
          <label for="NamaSantriMasuk">Santri</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select id="add-{{strtolower($page)}}-dari" name="dari" class="form-control">
            <option value='2'>Kunjungan Walsan</option>
            <option value='3'>Sisa Bulan Kemarin</option>
          </select>
          <label for="add-{{strtolower($page)}}-dari">Asal Saku Masuk</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" onkeyup="splitInDots(this)" id='add-{{strtolower($page)}}-jumlah' name="jumlah" class="form-control">
          <label for="add-{{strtolower($page)}}-jumlah">Jumlah (Rp.)</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="date" id='add-{{strtolower($page)}}-tanggal' name="tanggal" class="form-control" value="{{date('Y-m-d')}}">
          <label for="add-{{strtolower($page)}}-tanggal">Tanggal</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
      </form>
    </div>
  </div>
  <!-- Filter Canvas -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilter" aria-labelledby="offcanvasFilterLabel" >
    <div class="offcanvas-header">
      <h5 id="offcanvasAdd{{$page}}Label" class="offcanvas-title">Add {{$page}}</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="update-bulan pt-0" id="updateBulanForm" action="javascript:void(0)">
        <div class="form-floating form-floating-outline mb-4">
          <select id="bulan" name="bulan" class="form-control">
            @foreach($var['bulan'] as $key=>$value)
            <option value='{{$key}}' {{($key==$bulan)?"selected":""}}>{{$value}}</option>
            @endforeach
          </select>
          <label for="bulan">Bulan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select id="tahun" name="tahun" class="form-control">
            @for($i=date('Y'); $i>=(int)date('Y')-5; $i--)
            <option value='{{$i}}' {{($i==$tahun)?"selected":""}}>{{$i}}</option>
            @endfor
          </select>
          <label for="tahun">Tahun</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
      </form>
    </div>
  </div>
</div>
@endsection
