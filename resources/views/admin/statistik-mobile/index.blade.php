@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
  <script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
  <script src="{{asset('assets/js/forms-editors.js')}}"></script>
@endsection
@section('content')
<div class="row mb-4">
    <!-- Ringkasan Belum Log In -->
    <div class="col-md-4">
        <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Belum Log In ke Mobile App</h5>
            <p class="card-text display-6">{{ $jmlUserBelumLogin }} Orang</p>
        </div>
        </div>
    </div>

    <!-- Ringkasan Belum Log In -->
    <div class="col-md-4">
        <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Sudah Log In ke Mobile App</h5>
            <p class="card-text display-6">{{ $jmlUserSudahLogin }} Orang</p>
        </div>
        </div>
    </div>
 
</div>

<div class="row">
    <div class="col-12 col-md-6">
        <div class="p-3 border rounded bg-white" id="card-block">
            <div class="card-header">
                <h4>User Log In</h4>
            </div>
            <div class="card-body" style="overflow-x:scroll">
            <div id="table_log">
                <table class="dataTable table">
                    <thead>
                        <tr>
                        <th style="width: 50px;">#</th>
                        <th style="min-width: 150px;">Nama</th>
                        <th style="min-width: 180px;">Sebanyak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($dataUserLogIn as $row)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $row->jumlahLogin }} x</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="p-3 border rounded bg-white" id="card-block">
            <div class="card-header">
                <h4>User Belum Log In</h4>
            </div>
            <div class="card-body" style="overflow-x:scroll">
            <div id="table_log">
                <table class="dataTable table">
                    <thead>
                        <tr>
                        <th style="width: 50px;">#</th>
                        <th style="min-width: 150px;">Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($dataUserBelumLogin as $row)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $row->nama ?? '-'}}</td>
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