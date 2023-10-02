@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-santri-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='santri'>
<input type="hidden" name="title" id='title' value='Santri'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>No. Induk</th>
          <th>NIK</th>
          <th>Nama</th>
          <th>Kelas</th>
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
        <input type="hidden" class="form-control" id="add-{{strtolower($title)}}-nik" placeholder="" name="nik" required />
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-no_induk" placeholder="No. Induk Santri" name="no_induk" required />
          <label for="add-{{strtolower($title)}}-no_induk">No. Induk</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-nama" placeholder="Nama Pegawa; Ex : Abdul Ghofar" name="nama" required />
          <label for="add-{{strtolower($title)}}-nama">Nama</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-nisn" placeholder="NISN" name="nisn" />
          <label for="add-{{strtolower($title)}}-nisn">NISN</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="number" class="form-control" id="add-{{strtolower($title)}}-anak_ke" placeholder="Anak ke" name="anak_ke" />
          <label for="add-{{strtolower($title)}}-anak_ke">Anak ke</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-tempat_lahir" placeholder="tempat lahir" name="tempat_lahir" />
          <label for="add-{{strtolower($title)}}-tempat_lahir">Tempat Lahir</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="date" class="form-control" id="add-{{strtolower($title)}}-tanggal_fix" placeholder="tanggal lahir" name="tanggal_fix" />
          <label for="add-{{strtolower($title)}}-tanggal_fix">Tanggal Lahir</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-jenis_kelamin" name="jenis_kelamin">
            <option value='L'>Laki-laki</option>
            <option value='P'>Perempuan</option>
          </select>
          <label for="add-{{strtolower($title)}}-jenis_kelamin">Jenis Kelamin</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea  class="form-control" id="add-{{strtolower($title)}}-alamat" placeholder="Alamat" name="alamat"></textarea>
          <label for="add-{{strtolower($title)}}-alamat">Alamat</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-provinsi" placeholder="provinsi" name="provinsi">
            <option value="0">---Pilih Provinsi---</option>
            @foreach($prov as $row)
            <option value="{{$row->prov_id}}">{{$row->prov_name}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-provinsi">Provinsi</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-kabkota" placeholder="kabkota" name="kabkota">
            <option value="0">---Pilih Kota---</option>
          </select>
          <label for="add-{{strtolower($title)}}-kabkota">Kab/Kota</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-kecamatan" placeholder="kecamatan" name="kecamatan" />
          <label for="add-{{strtolower($title)}}-kecamatan">Kecamatan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-kelurahan" placeholder="kelurahan" name="kelurahan" />
          <label for="add-{{strtolower($title)}}-kelurahan">Kelurahan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-kode_pos" placeholder="kode_pos" name="kode_pos" />
          <label for="add-{{strtolower($title)}}-kode_pos">Kode Pos</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-no_hp" placeholder="no_hp" name="no_hp" />
          <label for="add-{{strtolower($title)}}-no_hp">No. HP</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control select2" id="add-{{strtolower($title)}}-kamar_id" data-placeholder="All" placeholder="Kamar Santri"  name="kamar_id">
            <option value="0">--Pilih Kamar Santri</option>
            @foreach($kamar as $row)
              <option value="{{$row->id}}">{{$row->code}}-{{$row->name}}-{{$row->pegawai->nama}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-kamar_id">Kamar</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-tahfidz_id" name="tahfidz_id">
            <option value="0">--Pilih Kelompok Tahfidz</option>
            @foreach($tahfidz as $row)
              <option value="{{$row->id}}">{{$row->name}}-{{$row->pegawai->nama}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-tahfidz_id">Kelompok Tahfidz</label>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
