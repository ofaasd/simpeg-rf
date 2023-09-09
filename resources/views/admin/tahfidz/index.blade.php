@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-academic-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Manajemen Ruang Tahfidz</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='tahfidz'>
<input type="hidden" name="title" id='title' value='Tahfidz'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Code</th>
          <th>Name</th>
          <th>Employee</th>
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
        <input type="hidden" id="add-{{strtolower($title)}}-tahun_ajaran_ids" name="tahun_ajaran_id" value="{{$ta->id}}">
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-name" placeholder="Academic Name; Ex : Operator" name="name" aria-label="John Doe" />
          <label for="add-{{strtolower($title)}}-name">Name</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control" id="add-{{strtolower($title)}}-code" name="code" >
            @for($i=1;$i<=15;$i++)
              @foreach($code as $value)
                <option value='{{$i}}{{$value}}'>{{$i}}{{$value}}</option>
              @endforeach
            @endfor
          </select>
          <label for="add-{{strtolower($title)}}-description">Code</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control select2" id="add-{{strtolower($title)}}-employee_id" name="employee_id" >

              @foreach($employee as $row)
                <option value='{{$row->id}}'>{{$row->nama}}</option>
              @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-employee_id">Employee</label>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
      </form>
    </div>
  </div>
</div>
@endsection
