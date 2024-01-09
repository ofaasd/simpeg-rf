@extends('layouts/layoutMaster')

@section('title', 'Gelombang PSB' . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-gelombang-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='gelombang'>
<input type="hidden" name="title" id='title' value='Gelombang'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>No Gel</th>
          <th>Nama Gel</th>
          <th>Keterangan</th>
          <th>Tgl Mulai</th>
          <th>Tgl Akhir</th>
          <th>Status</th>
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
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-no_gel" placeholder="No. Gelombang" name="no_gel" aria-label="Nomor Gelombang" />
          <label for="add-{{strtolower($title)}}-no_gel">No. Gelombang</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text"  class="form-control" id="add-{{strtolower($title)}}-nama_gel" placeholder="Nama Gelombang" name="nama_gel" >
          <label for="add-{{strtolower($title)}}-nama_gel">Nama Gelombang</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea  class="form-control" id="add-{{strtolower($title)}}-nama_gel_long" placeholder="Nama Gelombang Panjang" name="nama_gel_long" ></textarea>
          <label for="add-{{strtolower($title)}}-nama_gel_long">Keterangan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="date"  class="form-control" id="add-{{strtolower($title)}}-tgl_mulai" placeholder="tgl_mulai" name="tgl_mulai" >
          <label for="add-{{strtolower($title)}}-tgl_mulai">Tanggal Mulai</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="date"  class="form-control" id="add-{{strtolower($title)}}-tgl_akhir" placeholder="tgl_akhir" name="tgl_akhir" >
          <label for="add-{{strtolower($title)}}-tgl_akhir">Tanggal Akhir</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control" id="add-{{strtolower($title)}}-tahun" name="tahun" >
            @foreach($tahun as $row)
            <option value="{{$row->id}}">{{$row->awal}}-{{$row->akhir}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-tahun">Tahun Ajaran</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control" id="add-{{strtolower($title)}}-jenis" name="jenis" >
            <option value="1">Reguler</option>
            <option value="2">Khusus</option>
            <option value="3">Prestasi</option>
          </select>
          <label for="add-{{strtolower($title)}}-jenis">Jenis</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control" id="add-{{strtolower($title)}}-pmb_online" name="pmb_online" >
            <option value="0">Tidak Aktif</option>
            <option value="1">Aktif</option>
          </select>
          <label for="add-{{strtolower($title)}}-pmb_online">Status</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
