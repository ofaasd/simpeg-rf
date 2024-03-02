<div class="row">
  <div class="col-md-12">

    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
    @if(empty($id))
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='tahfidz')?'active':'' }}" href="{{url('ustadz/tahfidz')}}"><i class='mdi mdi-account-outline me-1 mdi-20px'></i>List Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='detail_tahfidz')?'active':'' }}" href="{{url('ustadz/detail_tahfidz')}}"><i class='mdi mdi-account-multiple-outline me-1 mdi-20px'></i>Tahfidz Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='grafik_tahfidz')?'active':'' }}" href="{{url('ustadz/grafik_tahfidz')}}"><i class='mdi mdi-view-grid-outline me-1 mdi-20px'></i>Grafik Tahfidz</a></li>
    @else
      <li class="nav-item"><a class="nav-link {{ (Request::segment(1)=='ketahfidzan')?'active':'' }}" href="{{url('ketahfidzan/' . $id)}}"><i class='mdi mdi-account-outline me-1 mdi-20px'></i>List Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(1)=='detail_ketahfidzan')?'active':'' }}" href="{{url('detail_ketahfidzan/')}}"><i class='mdi mdi-account-multiple-outline me-1 mdi-20px'></i>Tahfidz Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(1)=='grafik_ketahfidzan')?'active':'' }}" href="{{url('grafik_ketahfidzan/')}}"><i class='mdi mdi-view-grid-outline me-1 mdi-20px'></i>Grafik Tahfidz</a></li>
    @endif
    </ul>
  </div>
</div>
