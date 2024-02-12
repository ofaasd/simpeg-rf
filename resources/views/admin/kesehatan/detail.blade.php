
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
    <a href="{{url('/kesehatan/santri')}}" class='btn btn-primary'>Kembali ke Daftar Santri</a>
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
              <h4>{{$var['santri']->nama}}</h4>
              <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                <li class="list-inline-item">
                  <i class='mdi mdi-invert-colors me-1 mdi-20px'></i><span class="fw-semibold">{{$var['santri']->no_induk}}</span>
                </li>
                <li class="list-inline-item">
                  <i class='mdi mdi-map-marker-outline me-1 mdi-20px'></i><span class="fw-semibold">{{$var['santri']->kelas}}</span>
                </li>
                {{-- <li class="list-inline-item"><i class='mdi mdi-calendar-blank-outline me-1 mdi-20px'></i><span class="fw-semibold"> Joined April 2021</span></li> --}}
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
        <h4>Pemeriksaan Santri</h4>
      </div>
      <div class="card-body">
        <div class="nav-align-left">
          <ul class="nav nav-tabs" role="tablist">
            @foreach($var['menu'] as $menu)
                <li class="nav-item">
                    <button type="button" class="nav-link {{($menu == 'pemeriksaan')?"active":""}}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-{{$menu}}" aria-controls="navs-left-{{$menu}}" aria-selected="{{($menu == 'biodata')?"true":""}}">{{strtoupper($menu)}}</button>
                </li>
            @endforeach
          </ul>
          <div class="tab-content">
            @foreach($var['menu'] as $menu)
            <div class="tab-pane fade  {{($menu == 'pemeriksaan')?"show active":""}}" id="navs-left-{{$menu}}">
              @include('admin/santri/_form_' . $menu)
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal_pemeriksaan" tabindex="-1" data-backdrop="false" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Pemeriksaan Santri</h3>
          <p class="pt-1">Tambah history pertumbuhan santri_photo</p>
        </div>
        <form id="formPemeriksaan" class="row g-4" onsubmit="return false">
          <input type="hidden" name="no_induk" id="no_induk" value="{{$var['santri']->no_induk}}">
          <input type="hidden" name="id" id="id_pemeriksaan">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_pemeriksaan' name="tanggal_pemeriksaan" class="form-control" value="{{date('Y-m-d')}}">
              <label for="tanggal_pemeriksaan">Tanggal Periksa</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="input-group input-group-merge">
              <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="tinggi_badan" id="tinggi_badan" placeholder="Tinggi Badan" aria-label="Tinggi Badan" aria-describedby="tinggi_badan">
                <label for="tinggi_badan">Tinggi Badan</label>
              </div>
              <span class="input-group-text">CM</span>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="input-group input-group-merge">
              <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="berat_badan" id="berat_badan" placeholder="Berat Badan" aria-label="Berat Badan" aria-describedby="berat_badan">
                <label for="berat_badan">Berat Badan</label>
              </div>
              <span class="input-group-text">KG</span>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="input-group input-group-merge">
              <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="lingkar_pinggul" id="lingkar_pinggul" placeholder="Lingkar Pinggul" aria-label="Lingkar Pinggul" aria-describedby="lingkar_pinggul">
                <label for="lingkar_pinggul">Lingkar Pinggul</label>
              </div>
              <span class="input-group-text">CM</span>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="input-group input-group-merge">
              <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="lingkar_dada" id="lingkar_dada" placeholder="Lingkar Dada" aria-label="Lingkar Dada" aria-describedby="lingkar_dada">
                <label for="lingkar_dada">Lingkar Dada</label>
              </div>
              <span class="input-group-text">CM</span>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='kondisi_gigi' name="kondisi_gigi" class="form-control">
              <label for="kondisi_gigi">Kondisi Gigi</label>
            </div>
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
