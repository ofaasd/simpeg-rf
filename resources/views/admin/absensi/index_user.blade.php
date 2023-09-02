@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
<script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Absensi</h5>
      </div>
      <div class="card-datatable table-responsive">
          <input type="hidden" name="page" id='page' value='absensi'>
          <input type="hidden" name="title" id='title' value='Absensi'>
          <form class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Form" action="javascript:void(0)">
            @csrf
            <div class="form-floating form-floating-outline mb-4">
              <div class="row">
                <div class="col-md-6">
                  <div id="my_camera" style="margin:auto;"></div>
                  <br/>
                  <input type="button" class="form-control btn btn-success text-success" value="Take Snapshot" onClick="take_snapshot()">
                  <input type="hidden" name="image" class="image-tag">
                </div>
                <div class="col-md-6">
                  <br/>
                  <div id="results" style="text-align:center">Hasil Photo</div>
                  <label for="kordinat">Kordinat</label>
                  <input type="text" name="lat" id="lat" class="form-control">
                  <input type="text" name="long" id="long" class="form-control">
                </div>

              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-center">
                @if(empty($absensi->start))
                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Absen Masuk</button>
                @elseif(empty($absensi->end))
                <button type="submit" class="btn btn-danger me-sm-3 me-1 data-submit">Absen Keluar</button>
                @else
                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" disabled>Sudah Absen</button>
                @endif
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        Log Absensi
      </div>
      <div class="card-content">
        <table class="table datatables">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>User</th>
              <th>Masuk</th>
              <th>Selesai</th>
            </tr>
          </thead>
          <tbody>
            @foreach($list_absensi as $row)
              <tr>
                <td>{{date('d-m-Y', strtotime($row->day))}}</td>
                <td>{{$row->user_id}}</td>
                <td>{{!empty($row->start) ? date('H:i:s', $row->start) : ''}}</td>
                <td>{{!empty($row->end) ? date('H:i:s', $row->end) : ''}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    $(".datatables").DataTable();
    Webcam.set({
        width: 200,
        height: 200,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

    Webcam.attach( '#my_camera' );
    $('#addNew{{$title}}Form').submit(function(e) {

      e.preventDefault();
      showBlock();

      $.ajax({
        data: $('#addNew{{$title}}Form').serialize(),
        url: ''.concat(baseUrl).concat('absensi'),
        type: 'POST',
        success: function success(status) {
          // sweetalert
          showUnblock();
          Swal.fire({
            icon: 'success',
            title: 'Successfully '.concat(status.nama, ' Updated !'),
            text: ''.concat('Absensi ', ' ').concat(' Updated Successfully.'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        },
        error: function error(err) {
          showUnblock();
          Swal.fire({
            title: 'Data Gagal Di Simpan',
            text: ' Harap Klik tombol Hingga Keluar hasil Photo',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        }
      });
      });

  });
  const res = document.getElementById('results');
  function take_snapshot() {
    Webcam.snap( function(data_uri) {
        $(".image-tag").val(data_uri);
        res.innerHTML = '<img src="'+data_uri+'" align="center" width="200" height="200" />';
    } );
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      res.innerHTML = "Geolocation is not supported by this browser.";
    }
  }
  function showPosition(position) {
    $("#lat").val(position.coords.latitude);
    $("#long").val(position.coords.longitude);
  }
</script>
@endsection
