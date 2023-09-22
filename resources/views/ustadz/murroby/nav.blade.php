<div class="row">
  <div class="col-md-12">

    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='murroby')?'active':'' }}" href="{{url('ustadz/murroby')}}"><i class='mdi mdi-account-outline me-1 mdi-20px'></i>List Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='uang-saku')?'active':'' }}" href="{{url('ustadz/uang-saku')}}"><i class='mdi mdi-account-multiple-outline me-1 mdi-20px'></i>Uang Saku</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='inventory')?'active':'' }}" href="{{url('ustadz/inventory')}}"><i class='mdi mdi-view-grid-outline me-1 mdi-20px'></i>Inventory Kamar</a></li>
    </ul>
  </div>
</div>
