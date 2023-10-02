<div class="row">
  <div class="col-md-12">

    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='tahfidz')?'active':'' }}" href="{{url('ustadz/tahfidz')}}"><i class='mdi mdi-account-outline me-1 mdi-20px'></i>List Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='uang-saku')?'active':'' }}" href="{{url('ustadz/detail_tahfidz')}}"><i class='mdi mdi-account-multiple-outline me-1 mdi-20px'></i>Tahfidz Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='inventory')?'active':'' }}" href="{{url('ustadz/grafik_tahfidz')}}"><i class='mdi mdi-view-grid-outline me-1 mdi-20px'></i>Grafik Tahfidz</a></li>
    </ul>
  </div>
</div>
