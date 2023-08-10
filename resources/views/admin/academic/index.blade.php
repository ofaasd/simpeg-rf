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
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) '{{$value}}'
@endforeach</textarea>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
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
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-name" placeholder="Academic Name; Ex : Operator" name="name" aria-label="John Doe" />
          <label for="add-{{strtolower($title)}}-name">Name</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <textarea  class="form-control" id="add-{{strtolower($title)}}-description" placeholder="Description" name="description" aria-label="John Doe"></textarea>
          <label for="add-{{strtolower($title)}}-description">Description</label>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
