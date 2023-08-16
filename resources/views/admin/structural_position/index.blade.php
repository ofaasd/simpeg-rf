@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-structure-position.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='structural-position'>
<input type="hidden" name="title" id='title' value='Structural-Position'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>Tipe</th>
          <th>Atasan</th>
          <th>Description</th>
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
          <select class="form-control" id="add-{{strtolower($title)}}-structural_position_id" name="structural_position_id" >
            <option value="0">Tanpa Atasan</option>
            @foreach($StructuralPosition as $row)
              <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-employee_status_id">Atasan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-name" placeholder="Name; Ex : Ketua Yayasan" name="name" />
          <label for="add-{{strtolower($title)}}-name">Nama</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-type" name="type" >
            @foreach($tipe as $key=>$row)
              <option value="{{$key}}">{{$row}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-type">Tipe Penilaian Kinerja</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-control" id="add-{{strtolower($title)}}-school_identity_id" name="school_identity_id" >
            @foreach($school as $row)
              <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-school_identity_id">Tempat Mengajar</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea class="form-control" id="add-{{strtolower($title)}}-description" placeholder="Description" name="description"></textarea>
          <label for="add-{{strtolower($title)}}-description">Deskripsi</label>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
