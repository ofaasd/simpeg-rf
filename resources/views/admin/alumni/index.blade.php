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
        <h4>Alumni</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div id="table_berita">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>No. Induk</td>
                <td>Nama</td>
                <td>Kelas</td>
                <td>Tahun Lulus</td>
              </tr>
            </thead>
            <tbody id="table_alumni">
              @foreach($santri as $row)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{ $row->no_induk }}</td>
                  <td>
                    <a href="{{ route('alumni.show', $row->no_induk) }}">
                        {{ $row->nama }}
                    </a>
                  </td>
                  <td>{{ $row->kelas }}</td>
                  <td>{{ $row->tahun_lulus}}</td>
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
