@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<div class="row">
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <a href="{{URL::to('/psb')}}">
              <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-bus-school mdi-20px"></i></span>
            </a>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">{{$jumlah_psb}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Pendaftar</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" >{{$jumlah_psb_baru}}</span>
          <small class="text-white fw-bold">Siswa Baru Pada Bulan ini</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-success">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <a href="{{URL::to('/santri')}}">
              <span class="avatar-initial rounded bg-label-success"><i class="mdi mdi-account-school mdi-20px"></i></span>
            </a>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">{{$jumlah_siswa}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Santri</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-secondary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <a href="{{URL::to('/pegawai')}}">
              <span class="avatar-initial rounded bg-label-secondary"><i class="mdi mdi-account-group mdi-20px"></i></span>
            </a>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">{{$jumlah_pegawai}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Pegawai</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-danger">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger"><i class="mdi mdi-account-off mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">0</h4>
        </div>
        <p class="mb-0 card-title text-white">Siswa Belum Lapor Pembayaran</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-6 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-warning">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning"><i class="mdi mdi-cash mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">Rp . {{number_format($jumlah_pembayaran,0,",",".")}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Pembayaran Bulan Ini</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-6 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-info">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-cash mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">Rp. {{number_format($jumlah_pembayaran_lalu,0,",",".")}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Pembayaran Bulan Lalu</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
      </div>
    </div>
  </div>


</div>

@endsection
