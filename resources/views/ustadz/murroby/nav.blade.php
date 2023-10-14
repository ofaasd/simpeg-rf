<div class="row">
  <div class="col-md-12">

    <ul class="nav nav-pills flex-column flex-sm-row mb-4">
      @if(empty($id))
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='murroby')?'active':'' }}" href="{{url('ustadz/murroby')}}"><i class='mdi mdi-account-outline me-1 mdi-20px'></i>List Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='uang-saku')?'active':'' }}" href="{{url('ustadz/uang-saku')}}"><i class='mdi mdi-account-multiple-outline me-1 mdi-20px'></i>Uang Saku</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='saku_masuk')?'active':'' }}" href="{{url('ustadz/saku_masuk')}}"><i class='mdi mdi-plus-circle-multiple me-1 mdi-20px'></i>Saku Masuk</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='saku_keluar')?'active':'' }}" href="{{url('ustadz/saku_keluar')}}"><i class='mdi mdi-minus-circle-multiple me-1 mdi-20px'></i>Saku Keluar</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='inventory')?'active':'' }}" href="{{url('ustadz/inventory')}}"><i class='mdi mdi-view-grid-outline me-1 mdi-20px'></i>Inventory Kamar</a></li>
      @else
      <li class="nav-item"><a class="nav-link {{ (Request::segment(1)=='murroby' && empty(Request::segment(2)))?'active':'' }}" href="{{url('murroby/' . $id)}}"><i class='mdi mdi-account-outline me-1 mdi-20px'></i>List Santri</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='uang-saku')?'active':'' }}" href="{{url('murroby/uang-saku/' . $id)}}"><i class='mdi mdi-account-multiple-outline me-1 mdi-20px'></i>Uang Saku</a></li>
      <li class="nav-item"><a class="nav-link {{ (Request::segment(2)=='inventory')?'active':'' }}" href="{{url('murroby/inventory/' . $id)}}"><i class='mdi mdi-view-grid-outline me-1 mdi-20px'></i>Inventory Kamar</a></li>
      @endif
    </ul>
  </div>
</div>
