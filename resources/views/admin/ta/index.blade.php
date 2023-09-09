@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-academic-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Manajemen Tahun Ajaran</h5>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='ta'>
<input type="hidden" name="title" id='title' value='TA'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>ID Tahun</th>
          <th>Awal</th>
          <th>Akhir</th>
          <th>Jenis</th>
          <th>Is Aktive</th>
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
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-id_tahun" placeholder="Academic Name; Ex : Operator" name="id_tahun" aria-label="John Doe" />
          <label for="add-{{strtolower($title)}}-id_tahun">ID Tahun Ajaran</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-awal" placeholder="Tahun Awal : 2023" name="awal" aria-label="John Doe" />
          <label for="add-{{strtolower($title)}}-awal">Tahun Akhir</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-akhir" placeholder="Tahun Akhir,  Ex : 2024" name="akhir" aria-label="John Doe" />
          <label for="add-{{strtolower($title)}}-akhir">Tahun Akhir</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control" id="add-{{strtolower($title)}}-jenis" name="jenis" >

              @foreach($jenis as $key=>$row)
                <option value='{{$key}}'>{{$row}}</option>
              @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-jenis">Jenis</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control" id="add-{{strtolower($title)}}-is_aktif" name="is_aktif" >

              @foreach($aktif as $key=>$row)
                <option value='{{$key}}'>{{$row}}</option>
              @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-is_aktif">Status</label>
        </div>


        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
      </form>
    </div>
  </div>
</div>
@endsection
