@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
  <script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
  <script src="{{asset('assets/js/forms-editors.js')}}"></script>
@endsection
@section('content')
<div class="row mb-4">
  <!-- Ringkasan Jumlah User Online -->
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">User Online/Baru-baru ini online</h5>
        <p class="card-text display-6">- Orang</p>
      </div>
    </div>
  </div>

  <!-- Daftar User Online -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <strong>User Online/Baru-baru ini online</strong>
      </div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          {{-- @forelse($onlineUsers as $user)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <i class="fas fa-user-circle me-2 text-success"></i> {{ $user->nama }}
              </div>
              <small class="text-muted">Aktif {{ $user->timestamp->diffForHumans() }}</small>
            </li>
          @empty --}}
            {{-- <li class="list-group-item text-center text-muted">Tidak ada user online</li> --}}
            <li class="list-group-item text-center text-muted">dalam tahap pengembangan</li>
          {{-- @endforelse --}}
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="card" id="card-block">
        <div class="card-header">
        <h4>Log Akses Mobile</h4>
        </div>
        <div class="card-body" style="overflow-x:scroll">
        <div id="table_log">
            <table class="table table-bordered table-hover table-striped table-sm">
                <thead>
                    <tr>
                    <th style="width: 50px;">#</th>
                    <th style="min-width: 250px;">Deskripsi</th>
                    <th style="min-width: 150px;">Nama</th>
                    <th style="min-width: 180px;">Terjadi pada</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach($aktifitas as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>
                            @php
                            $badgeClass = match(strtolower($row->description)) {
                                'login' => 'bg-success',
                                'logout' => 'bg-danger',
                                default => 'bg-primary',
                            };
                        @endphp

                        <span class="badge {{ $badgeClass }} text-uppercase">{{ $row->description }}</span>

                        </td>
                        <td>{{ $row->nama }}</td>
                        <td>
                            <i class="fas fa-clock text-secondary me-1"></i>
                            {{ date('d-m-Y H:i:s', strtotime($row->timestamp)) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-3">
                {{ $aktifitas->links() }}
            </div>
        </div>
        </div>
    </div>
</div>
@endsection