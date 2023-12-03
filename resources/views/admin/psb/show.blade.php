
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
<div class="row" style="margin-bottom:10px">
  <div class="col-12">
    <a href="{{url('/psb')}}" class='btn btn-primary'>Kembali ke Daftar Calon Santri</a>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="user-profile-header-banner">
        <img src="{{asset('assets/img/pages/profile-banner.png')}}" alt="Banner image" class="rounded-top">
      </div>
      <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
          <img src="{{ $var['santri_photo']}}" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
        </div>
        <div class="flex-grow-1 mt-3 mt-sm-5">
          <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
            <div class="user-profile-info">
              <h4>{{$var['psb_peserta']->nama}}</h4>
              <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                <li class="list-inline-item">
                  <i class='mdi mdi-invert-colors me-1 mdi-20px'></i><span class="fw-semibold">{{$var['psb_peserta']->no_peserta}}</span>
                </li>
                <li class="list-inline-item">
                  <i class='mdi mdi-map-marker-outline me-1 mdi-20px'></i><span class="fw-semibold">{{$var['psb_peserta']->tempat_lahir}}</span>
                </li>
                {{-- <li class="list-inline-item"><i class='mdi mdi-calendar-blank-outline me-1 mdi-20px'></i><span class="fw-semibold"> </span></li> --}}
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
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0)" id='data-diri'><i class='mdi mdi-account-outline me-1 mdi-20px'></i>Data Diri</a></li>
      <li class="nav-item"><a class="nav-link" href="javascript:void(0)" id='walsan'><i class='mdi mdi-account-multiple-outline me-1 mdi-20px'></i>Wali Santri</a></li>
      <li class="nav-item"><a class="nav-link" href="javascript:void(0)" id='asal-sekolah'><i class='mdi mdi-pencil me-1 mdi-20px'></i>Asal Sekolah</a></li>
      <li class="nav-item"><a class="nav-link" href="javascript:void(0)" id='berkas'><i class='mdi mdi-bulletin-board me-1 mdi-20px'></i>Berkas Pendukung</a></li>
    </ul>
  </div>
</div>
<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">

      <div class="card-body">
        <div class="row">
          <div class="col-md-12 my-form" style="padding:30px" id="edit_pribadi">
                  @include('admin/psb/_form_edit_data_diri')
          </div>
          <div class="col-md-12 my-form" style="padding:30px" id="edit_walsan">
                  @include('admin/psb/_form_edit_wali')
          </div>
          <div class="col-md-12 my-form" style="padding:30px" id="edit_asal_sekolah">
                  @include('admin/psb/_form_edit_asal')
          </div>
          <div class="col-md-12 my-form" style="padding:30px" id="edit_berkas">
                  @include('admin/psb/_form_edit_berkas')
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
      $(".my-form").hide();
      $("#edit_pribadi").show();

      $("#data-diri").click(function(){
          $(".my-form").hide();
          $(".nav-item a").attr('class','nav-link');
          $(this).attr('class','nav-link active');
          $("#edit_pribadi").fadeIn(500);
      });
      $("#walsan").click(function(){
          $(".my-form").hide();
          $(".nav-item a").attr('class','nav-link');
          $(this).attr('class','nav-link active');
          $("#edit_walsan").fadeIn();
      });
      $("#asal-sekolah").click(function(){
          $(".my-form").hide();
          $(".nav-item a").attr('class','nav-link');
          $(this).attr('class','nav-link active');
          $("#edit_asal_sekolah").fadeIn();
      });
      $("#berkas").click(function(){
          $(".my-form").hide();
          $(".nav-item a").attr('class','nav-link');
          $(this).attr('class','nav-link active');
          $("#edit_berkas").fadeIn();
      });
      // $("#provinsi").select2();
      // $("#kota").select2();
  });

</script>
@endsection
