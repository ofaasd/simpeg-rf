@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Ketahfidzan Santri {{ $tahun }}</h5>
  </div>
  <div class="card" style="overflow-x:scroll">
    <div class="col-md-12" style="padding:20px;">
      <form method="POST" action='' id="form-ketahfidzan">
          <div class="form-floating form-floating-outline mb-4 col-md-4 periode-keuangan">
            <select name="pilih-tahun" id="pilih-tahun" class='form-control col-md-4'>
              @foreach($var['tahun'] as $key=>$value)
                  <option value="{{$key}}" {{(!empty($tahun) && $tahun==$key)?"selected":""}}>{{$value}}</option>
              @endforeach
            </select>
            <label for="pilih-tahun">Pilih Tahun</label>
          </div>
          <div class="form-floating form-floating-outline col-md-12">
            <button type="submit" id="btn-submit" class="btn btn-primary waves-effect waves-light">Lihat</button>
          </div> 
      </form><br />
        <table class="dataTable table" id="table-ketahfidzan">
            <thead>
              @php
                  \Carbon\Carbon::setLocale('id');
              @endphp
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Murroby</th>
                    @foreach (range(1, 12) as $bulan)
                        <th>{{ \Carbon\Carbon::createFromFormat('!m', $bulan)->translatedFormat('F') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @php $no = 1; @endphp
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $row['nmSantri'] }} ({{ $row['klsSantri'] }})</td>
                    <td>{{ $row['nmMurroby'] }}</td>
                    @foreach ($row['bulan'] as $maxJuzSurah)
                        <td>{{ $maxJuzSurah }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
  </div>
</div>
@endsection
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    $('.dataTable').dataTable();

    $('#form-ketahfidzan').on('submit', function(e) {
      // Nonaktifkan tombol submit
      $('#btn-submit').prop('disabled', true);

      // Tampilkan loader
      $('.loader-container').show();

      // Pastikan form tetap disubmit
      return true; 
  });
  });
</script>