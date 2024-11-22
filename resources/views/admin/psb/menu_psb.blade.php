<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
      <li class="nav-item"><a class="nav-link {{ (Request::segment(1)=='psb' && empty(Request::segment(2)))?'active':'' }}" href="{{url('psb')}}"><i class='mdi mdi-account-outline me-1 mdi-20px'></i>Dafar Calon Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='validasi')?'active':'' }}" href="{{url('psb_new/validasi')}}"><i class='mdi mdi-cash me-1 mdi-20px'></i>Pembayaran</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='ujian')?'active':'' }}" href="{{url('psb_new/ujian')}}"><i class='mdi mdi-pencil me-1 mdi-20px'></i>Ujian</a></li>
      {{-- <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='pengumuman')?'active':'' }}" href="{{url('psb_new/pengumuman')}}"><i class='mdi mdi-bulletin-board me-1 mdi-20px'></i>Pengumuman</a></li> --}}
    </ul>
  </div>
</div>
