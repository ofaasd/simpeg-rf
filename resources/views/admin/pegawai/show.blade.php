@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css')}}" />
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
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
@endsection

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="user-profile-header-banner">
        <img src="{{asset('assets/img/pages/profile-banner.png')}}" alt="Banner image" class="rounded-top">
      </div>
      <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
          <img src="{{ (empty($var['EmployeeNew']->photo))?asset('assets/img/avatars/1.png'):asset('assets/img/upload/photo/' . $var['EmployeeNew']->photo)}}" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
        </div>
        <div class="flex-grow-1 mt-3 mt-sm-5">
          <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
            <div class="user-profile-info">
              <h4>{{$var['EmployeeNew']->nama}}</h4>
              <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                <li class="list-inline-item">
                  <i class='mdi mdi-invert-colors me-1 mdi-20px'></i><span class="fw-semibold">{{$var['EmployeeNew']->jab->name}}</span>
                </li>
                <li class="list-inline-item">
                  <i class='mdi mdi-map-marker-outline me-1 mdi-20px'></i><span class="fw-semibold">{{$var['EmployeeNew']->lembaga_induk}}</span>
                </li>
                <li class="list-inline-item">
                  <i class='mdi mdi-calendar-blank-outline me-1 mdi-20px'></i><span class="fw-semibold"> Joined April 2021</span></li>
              </ul>
            </div>
            <a href="javascript:void(0)" class="btn btn-success">
              <i class='mdi mdi-account-check-outline me-1'></i>Verified
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>Edit Porfile Pegawai</h4>
      </div>
      <div class="card-body">
        <div class="nav-align-left">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-biodata" aria-controls="navs-left-biodata" aria-selected="true">Biodata</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-ruang" aria-controls="navs-left-ruang" aria-selected="false">Golongan</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-status" aria-controls="navs-left-status" aria-selected="false">Status Pegawai</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-struktural" aria-controls="navs-left-struktural" aria-selected="false">Jabatan Struktural</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-keluarga" aria-controls="navs-left-keluarga" aria-selected="false">Keluarga</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-pendidikan" aria-controls="navs-left-pendidikan" aria-selected="false">Riwayat Pendidikan</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-pelatihan" aria-controls="navs-left-pelatihan" aria-selected="false">Riwayat Pelatihan</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-pekerjaan" aria-controls="navs-left-pekerjaan" aria-selected="false">Riwayat Pekerjaan</button>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade show active" id="navs-left-biodata">
              @include('admin/pegawai/_form_biodata')
            </div>
            <div class="tab-pane fade" id="navs-left-ruang">
              @include('admin/pegawai/_form_golongan')
            </div>
            <div class="tab-pane fade" id="navs-left-status">
              @include('admin/pegawai/under_develop')
            </div>
            <div class="tab-pane fade" id="navs-left-struktural">
              @include('admin/pegawai/under_develop')
            </div>
            <div class="tab-pane fade" id="navs-left-keluarga">
              @include('admin/pegawai/under_develop')
            </div>
            <div class="tab-pane fade" id="navs-left-pendidikan">
              @include('admin/pegawai/under_develop')
            </div>
            <div class="tab-pane fade" id="navs-left-pelatihan">
              @include('admin/pegawai/under_develop')
            </div>
            <div class="tab-pane fade" id="navs-left-pekerjaan">
              @include('admin/pegawai/under_develop')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
