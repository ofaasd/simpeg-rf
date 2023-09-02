@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-absensi-management.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='absensi'>
<input type="hidden" name="title" id='title' value='Absensi'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>User</th>
          <th>Tanggal</th>
          <th>Masuk</th>
          <th>Selesai</th>
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
          <select  class="form-control select2" id="add-{{strtolower($title)}}-user_id"  name="user_id">
            @foreach($user as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-user_id">Pegawai</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type='date'  class="form-control" id="add-{{strtolower($title)}}-day" placeholder="Hari" name="day" value='{{date('Y-m-d')}}'/>
          <label for="add-{{strtolower($title)}}-day">Hari</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type='time'  class="form-control" id="add-{{strtolower($title)}}-start" placeholder="Jam masuk" name="start" />
          <label for="add-{{strtolower($title)}}-start">Jam Masuk</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
        <input type='time'  class="form-control" id="add-{{strtolower($title)}}-end" placeholder="Jam Keluar" name="end" />
          <label for="add-{{strtolower($title)}}-end">Jam Keluar</label>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Absen</button>

        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>

      </form>
    </div>
  </div>
</div>
@endsection
