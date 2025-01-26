@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-md-6">
        <h5 class="card-title mb-0">Buka Tutup Pelaporan Pembayaran</h5>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="col-md-12" style="padding:20px;">
      <section class="col-lg-12 connectedSortable">
        <div class="card">
            <div class="card-content">
            <div class="col-md-12">
            <p class="alert alert-primary">Klik Tombol di bawah untuk membuka / menutup laporan pembayaran</p>
            @if($bukatutup->status == 0)
              <a href="<?php echo url('admin/bukatutup/insert/') ?>" class="btn btn-success">Buka Pelaporan</a><br /><br />
            @else
              <a href="<?php echo url('admin/bukatutup/insert/') ?>" class="btn btn-danger">Tutup Pelaporan</a><br /><br />
            @endif
            <small><i>Terakhir Dilakukan perubahan pada tanggal {{ date('d-m-Y H:i:s', strtotime($bukatutup->tanggal_buat))}}</i></small>
            <br /><br />
                </div>
            </div>
        </div>
    </section>
    </div>
  </div>
</div>
@endsection
