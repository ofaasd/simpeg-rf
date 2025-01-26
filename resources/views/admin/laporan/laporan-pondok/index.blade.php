@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Laporan Tahunan Pondok</h5>
  </div>
  <div class="card" style="overflow-x:scroll">
    <div class="col-md-12" style="padding:20px;">
      <form method="POST" action='' id="form-laporan">
          <div class="form-floating form-floating-outline mb-4 col-md-4">
            <select name="pilih-laporan" id="pilih-laporan" class='form-control col-md-4'>
              @foreach($var['pilihanLaporan'] as $key=>$value)
                @if($key == 0)
                <option value="0" {{(!empty($data['pilihanLaporan']) && $data['pilihanLaporan']==0)?"selected":""}}>Semua</option>
                @else
                  <option value="{{$key}}" {{(!empty($data['pilihanLaporan']) && $data['pilihanLaporan']==$key)?"selected":""}}>{{$value}}</option>
                @endif
              @endforeach
            </select>
            <label for="pilih-laporan">Pilih Laporan</label>
          </div>
          <div class="form-floating form-floating-outline mb-4 col-md-4 periode-keuangan">
            <select name="pilih-periode" id="pilih-periode" class='form-control col-md-4'>
              @foreach($var['pilihanPeriode'] as $key=>$value)
                  <option value="{{$key}}" {{(!empty($data['pilihanPeriode']) && $data['pilihanPeriode']==$key)?"selected":""}}>{{$value}}</option>
              @endforeach
            </select>
            <label for="pilih-periode">Pilih Periode Keuangan</label>
          </div>
          <div class="form-floating form-floating-outline col-md-12">
            <button type="submit" id="btn-submit" class="btn btn-primary waves-effect waves-light">Lihat</button>
          </div> 
      </form><br />
      @if(!empty($data))

          <h2 style='text-align:center'>Laporan Pondok {{ $data['bulan'][$data['periode']] }} {{ $data['tahun'] }}</h2>
              <table class="" id="table-laporan">
                  <thead>
                      <tr>
                          <td>No.</td>
                          <td>Kode Kelas</td>
                          <td>Kode Murroby</td>
                          <td>No. Induk</td>
                          <td>Nama Santri</td>
                          @foreach ($data['jenis_pembayaran'] as $row)
                              <td>{{$row->jenis}}</td>
                          @endforeach
                          <td>Total</td>
                      </tr>
                  </thead>
                  <tbody>
                      @php
                      $i = 1;
                      @endphp
                      @if($data['status'] == 2)
                        @foreach ($data['sisa_santri'] as $s)
                          @php
                            $total = 0;
                          @endphp

                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$s->kelas}}</td>
                                <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>
                                <td>{{$s->no_induk}}</td>
                                <td>{{$s->nama}}</td>
                                @foreach ($data['jenis_pembayaran'] as $row)
                                  <td>Rp. {{ number_format(
                                    $data['santri'][$s->no_induk][$row->id],
                                    0,
                                    ',',
                                    '.'
                                  ) }}</td>
                                  @php $total += $data['santri'][$s->no_induk][$row->id]; @endphp
                                @endforeach
                                <td>Rp.  {{number_format($total, 0, ',', '.')}}</td>
                            </tr>

                          @php $i++; @endphp
                        @endforeach
                      @elseif($data['status'] == 1)
                        @foreach ($data['santri_valid'] as $s)
                          @php
                            $total = 0;
                          @endphp

                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$s->kelas}}</td>
                                <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>
                                <td>{{$s->no_induk}}</td>
                                <td>{{$s->nama}}</td>
                                @foreach ($data['jenis_pembayaran'] as $row)
                                  <td>Rp. {{ number_format(
                                    $data['santri'][$s->no_induk][$row->id],
                                    0,
                                    ',',
                                    '.'
                                  ) }}</td>
                                  @php $total += $data['santri'][$s->no_induk][$row->id]; @endphp
                                @endforeach
                                <td>Rp.  {{number_format($total, 0, ',', '.')}}</td>
                            </tr>

                          @php $i++; @endphp
                        @endforeach
                      @else
                        @foreach ($data['siswa'] as $s)
                          @php
                            $total = 0;
                          @endphp

                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$s->kelas}}</td>
                                <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>
                                <td>{{$s->no_induk}}</td>
                                <td>{{$s->nama}}</td>
                                @foreach ($data['jenis_pembayaran'] as $row)
                                  <td>Rp. {{ number_format(
                                    $data['santri'][$s->no_induk][$row->id],
                                    0,
                                    ',',
                                    '.'
                                  ) }}</td>
                                  @php $total += $data['santri'][$s->no_induk][$row->id]; @endphp
                                @endforeach
                                <td>Rp.  {{number_format($total, 0, ',', '.')}}</td>
                            </tr>

                          @php $i++; @endphp
                        @endforeach
                      @endif
                  </tbody>
              </table>
      @endif
    </div>
  </div>
</div>
@endsection
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    $('#form-laporan').on('submit', function(e) {
      // Nonaktifkan tombol submit
      $('#btn-submit').prop('disabled', true);

      // Tampilkan loader
      $('.loader-container').show();

      // Pastikan form tetap disubmit
      return true; 
  });

    $('#pilih-laporan').on('change', function(e) {
        let valueLaporan = e.target.value;

        if (valueLaporan == 4 || valueLaporan == 0) {
            $('.periode-keuangan').show(); // Menampilkan elemen jika value adalah 1 atau 0
        } else {
            $('.periode-keuangan').hide(); // Menyembunyikan elemen jika value bukan 1 atau 0
        }
    });
  });
</script>