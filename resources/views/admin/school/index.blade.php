@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-academic-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='school'>
<input type="hidden" name="title" id='title' value='School'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>Grade</th>
          <th>Address</th>
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
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-name" placeholder="Academic Name; Ex : Operator" name="name" aria-label="John Doe" />
          <label for="add-{{strtolower($title)}}-name">Name</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-grade" placeholder="Grade; Ex : SD/SMP" name="grade" aria-label="grade" />
          <label for="add-{{strtolower($title)}}-grade">Grade</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea  class="form-control" id="add-{{strtolower($title)}}-address" placeholder="Address" name="address" aria-label="Address"></textarea>
          <label for="add-{{strtolower($title)}}-address">Address</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-phone" placeholder="Phone; Ex : 0823123123" name="phone" aria-label="phone" />
          <label for="add-{{strtolower($title)}}-phone">Phone</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-fax" placeholder="fax; Ex : 0823123123" name="fax" aria-label="fax" />
          <label for="add-{{strtolower($title)}}-fax">Fax</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="email" class="form-control" id="add-{{strtolower($title)}}-email" placeholder="email; Ex : ofaasd@gmail.com" name="email" aria-label="email" />
          <label for="add-{{strtolower($title)}}-email">Email</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-website" placeholder="website; Ex : https://ppatq-rf.sch.id" name="website" aria-label="website" />
          <label for="add-{{strtolower($title)}}-website">Website</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-latitude" placeholder="" name="latitude" aria-label="latitude" />
          <label for="add-{{strtolower($title)}}-website">Latitude</label>
        </div>
        <!-- <div class="form-floating form-floating-outline mb-4"> -->
          <input type="hidden" class="form-control" id="add-{{strtolower($title)}}-employee_id" placeholder="" name="employee_id" aria-label="employee_id" value=0 />
          <!-- <label for="add-{{strtolower($title)}}-website">Employee</label> -->
        <!-- </div> -->

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
