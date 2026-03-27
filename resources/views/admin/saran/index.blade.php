@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

@endsection
@section('page-script')

@endsection
@section('content')
<style>
  table.dataTable td, table.dataTable th {
    font-size: 0.8em;
  }

  trix-toolbar [data-trix-button-group='file-tools']{
    display: none;
  }
</style>
<!--/ Navbar pills -->
<div class="row">
  <div class="col-xl-12">
    <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>Media Saran</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div id="table_berita">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Nama</td>
                <td>Email</td>
                <td>No. HP</td>
                <td>Masukan</td>
                <td>Saran</td>
                <td>Tanggal</td>
              </tr>
            </thead>
            <tbody id="table_alumni">
              @foreach($keluhan as $row)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $row->nama_pelapor }}</td>
                  <td>{{ $row->email }}</td>
                  <td>{{ $row->no_hp }}</td>
                  <td>{{ $row->masukan }}</td>
                  <td>{{ $row->saran }}</td>
                  <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function(event) {
  $('.dataTable').dataTable();
});



</script>
