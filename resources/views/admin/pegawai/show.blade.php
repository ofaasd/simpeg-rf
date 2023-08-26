@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')

@endsection

@section('content')

<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4">
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
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-left-ruang" aria-controls="navs-left-ruang" aria-selected="false">Golongan Ruang</button>
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
