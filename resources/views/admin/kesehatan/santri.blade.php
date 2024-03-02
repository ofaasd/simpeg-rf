@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection
@section('page-script')
  <script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
@endsection
@section('content')
<style>
  table.dataTable td, table.dataTable th {
    font-size: 0.8em;
  }
</style>
<!--/ Navbar pills -->
<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>Catatan Pemeriksaan Santri</h4>
      </div>
      <div class="card-body" style="overflow-x:scroll">
        <div class="row">
          <div class="col-md-6">
            <div class="row">

            </div>
          </div>
          {{-- <div class="col-md-6 text-right">
            <button type="button" id="btnTambahSantriSakit" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_sakit" style="float:right"> Tambah Santri Sakit</button>
          </div> --}}
        </div>
        <div id="table_kesehatan">
          <table class="dataTable table">
            <thead>
              <tr>
                <td>No.</td>
                <td>Nama</td>
                <td>Kelas</td>
                <td>Murroby</td>
                <td>BB</td>
                <td>TB</td>
                <td>LP</td>
                <td>LD</td>
                <td>Gigi</td>
                <td>Tgl periksa</td>

              </tr>
            </thead>
            <tbody id="table_uang_saku">
              @foreach($santri as $row)
                <tr>
                  <td>{{$row->no_induk}}</td>
                  <td><a href="{{URL::to('/kesehatan/santri/' . $row->id )}}">{{$row->nama}}</a></td>
                  <td>{{$row->kelas}}</td>
                  <td>{{$row->kamar_id}}</td>
                  <td>{{ $var['berat_badan'][$row->no_induk] }}</td>
                  <td>{{$var['tinggi_badan'][$row->no_induk] }}</td>
                  <td>{{$var['lingkar_pinggul'][$row->no_induk] }}</td>
                  <td>{{$var['lingkar_dada'][$row->no_induk] }}</td>
                  <td>{{$var['kondisi_gigi'][$row->no_induk] }}</td>
                  <td>{{$var['tanggal_periksa'][$row->no_induk] }}</td>
                </tr>
              @endforeach
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- uang Saku Masuk -->
<div class="modal fade" id="modal_sakit"  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Santri Sakit</h3>
          <p class="pt-1">Tambah santri yang sedang sakit</p>
        </div>
        <form id="formSakit" class="row g-4" onsubmit="return false">
          <input type="hidden" name="id" id="id_sakit">
          <div class="col-6 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="santri_id" id="santri_id" class="form-control select2">
                @foreach($santri as $row)
                <option value="{{$row->no_induk}}" >{{$row->nama}} - {{$row->kelas}}</option>
                @endforeach
              </select>
              <label for="santri_id">Nama Santri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='sakit' name="sakit" class="form-control">
              <label for="sakit">Sakit</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_sakit' name="tanggal_sakit" class="form-control" value="{{date('Y-m-d')}}">
              <label for="tanggal_sakit">Tanggal Sakit</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='keterangan_sakit' name="keterangan_sakit" class="form-control">
              <label for="keterangan_sakit">Keterangan Sakit</label>
            </div>
          </div>

            <div class="col-12 col-md-6 sembuh_area" style="display:none">
              <div class="form-floating form-floating-outline">
                <input type="date" id='tanggal_sembuh' name="tanggal_sembuh" class="form-control">
                <label for="tanggal_sembuh">Tanggal Sembuh</label>
              </div>
            </div>
            <div class="col-12 col-md-6 sembuh_area" style="display:none">
              <div class="form-floating form-floating-outline">
                <input type="text" id='keterangan_sembuh' name="keterangan_sembuh" class="form-control">
                <label for="keterangan_sembuh">Keterangan Sembuh</label>
              </div>
            </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
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
