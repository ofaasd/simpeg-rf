
@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')


@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card" style="overflow-x:scroll">
    <div class="col-md-12" style="padding:20px;">
      <form method="GET" action='{{URL::to('/pembayaran')}}'>
          <div class="form-floating form-floating-outline mb-4 col-md-4">
            <select name="periode" id="periode" class='form-control col-md-4'>
              <option value="0">Semua</option>
              @foreach($data['bulan'] as $key=>$value)
              <option value={{$key}} {{($data['periode'] == $key)?'selected':''}}>{{$value}}</option>
              @endforeach
            </select>
            <label for="periode">Periode</label>
          </div>
          <div class="form-floating form-floating-outline mb-4 col-md-4">
            <input type="number" name='tahun' id='tahun' class='form-control col-md-2' value='{{ date('Y') }}'>
            <label for="tahun">Tahun</label>
          </div>
          <div class="form-floating form-floating-outline mb-4 col-md-4">
            <select name="kelas" id="kelas" class='form-control col-md-4'>
              @foreach($kelas as $row)
                <option value="{{$row->kelas}}">{{$row->kelas}}</option>
              @endforeach
            </select>
            <label for="kelas">Kelas</label>
          </div>
          <div class="form-floating form-floating-outline col-md-12">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Lihat</button>
            <a href="javascript:void(0)" class="btn btn-success waves-effect waves-light" id="export">Export</a>

          </div>
      </form><br />
      @if(!empty($data))

          <h2 style='text-align:center'>Syahriyah {{ $data['bulan'][$data['periode']] }} {{ $data['tahun'] }}</h2>
              <table class="table" id="table-laporan">
                  <thead>
                      <tr>
                          <td>No.</td>
                          <td>No. Induk</td>
                          <td>Nama Santri</td>
                          <td>Kode Kelas</td>
                          <td>Kode Murroby</td>
                          <td>Tanggal Bayar</td>
                          <td>Status</td>
                          <td>Total</td>
                      </tr>
                  </thead>
                  <tbody>
                      @php
                      $i = 1;
                      @endphp
                      @foreach ($pembayaran as $s)
                        @php
                          $total = 0;
                        @endphp
                          <tr>
                              <td>{{$i}}</td>
                              <td>{{$s->no_induk}}</td>
                              <td>{{$s->nama}}</td>
                              <td>{{$s->kelas}}</td>
                              <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>

                              <td>{{$s->tanggal_bayar}}</td>
                              <td>@if($s->validasi == 0)
                                    <button class='btn btn-secondary btn-xs'>Belum Valid</button>
                                  @elseif($s->validasi == 1)
                                    <button class='btn btn-primary btn-xs'>Valid</button>
                                  @else
                                    <button class='btn btn-danger btn-xs'>Tidak Valid</button>
                                  @endif
                              </td>
                              <td>Rp.  {{number_format($s->jumlah, 0, ',', '.')}}</td>
                          </tr>
                        @php $i++; @endphp
                      @endforeach
                  </tbody>
              </table>
      @endif
    </div>
  </div>
</div>
@endsection
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    const title = 'Syahriyah';
    $("#table-laporan").DataTable();
    $("#export").click(function(){
     const periode = $("#periode").val();
     const tahun = $("#tahun").val();
     const kelas = $("#kelas").val();
     location.href = `{{URL::to('/pembayaran/export')}}?periode=${periode}&tahun=${tahun}&kelas=${kelas}`;
    });
  });
</script>
