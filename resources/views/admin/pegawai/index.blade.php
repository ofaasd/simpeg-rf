@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-pegawai-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='pegawai'>
<input type="hidden" name="title" id='title' value='Pegawai'>
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
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new {{strtolower($title)}} -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAdd{{$title}}" aria-labelledby="offcanvasAdd{{$title}}Label">
    <div class="offcanvas-header">
      <h5 id="offcanvasAdd{{$title}}Label" class="offcanvas-title">Add {{$title}}</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Form">
        <input type="hidden" name="id" id="{{strtolower($title)}}_id">
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-nama" placeholder="Nama Pegawa; Ex : Abdul Ghofar" name="nama" />
          <label for="add-{{strtolower($title)}}-nama">Nama</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-tempat_lahir" placeholder="tempat lahir" name="tempat_lahir" />
          <label for="add-{{strtolower($title)}}-tempat_lahir">Tempat Lahir</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="date" class="form-control" id="add-{{strtolower($title)}}-tanggal_lahir" placeholder="tanggal lahir" name="tanggal_lahir" />
          <label for="add-{{strtolower($title)}}-tanggal_lahir">Tanggal Lahir</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-jenis_kelamin" name="jenis_kelamin">
            <option value='Laki-laki'>Laki-laki</option>
            <option value='Perempuan'>Perempuan</option>
          </select>
          <label for="add-{{strtolower($title)}}-jenis_kelamin">Jenis Kelamin</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-jabatan_new" name="jabatan_new">
            @foreach($var['structural'] as $jabatan)
              <option value='{{$jabatan->id}}'>{{$jabatan->name}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-jabatan_new">Jabatan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea  class="form-control" id="add-{{strtolower($title)}}-alamat" placeholder="Alamat" name="alamat"></textarea>
          <label for="add-{{strtolower($title)}}-alamat">Alamat</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-pendidikan" name="pendidikan">
            @foreach($var['Grades'] as $grade)
              <option value='{{$grade->id}}'>{{$grade->name}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-pendidikan">Pendidikan Terakhir</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="date" class="form-control" id="add-{{strtolower($title)}}-pengangkatan" placeholder="pengangkatan" name="pengangkatan" />
          <label for="add-{{strtolower($title)}}-pengangkatan">Tanggal Pengangkatan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-lembaga_induk" placeholder="lembaga induk" name="lembaga_induk" />
          <label for="add-{{strtolower($title)}}-lembaga_induk">Lembaga Induk</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-lembaga_diikuti" placeholder="tempat lahir" name="lembaga_diikuti" />
          <label for="add-{{strtolower($title)}}-lembaga_diikuti">Lembaga yang Diikuti</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
