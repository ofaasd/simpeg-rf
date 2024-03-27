
@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')


@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Review Pembayaran</h5>
  </div>
  <div class="card-body" style="overflow-x:scroll">
    <div class="col-md-12" style="padding:20px;">
      <h2 style='text-align:center; margin-top:40px;'>Laporan Pembayaran {{ $data['bulan'][$data['periode']] }} {{ $data['tahun'] }}</h2>
      <table class="table">
        <thead>
          <tr>
            <td>id_bayar</td>
            <td>no_induk</td>
            <td>nama_santri</td>
            <td>kode_kelas</td>
            <td>kode_murroby</td>
            <td>tanggal_bayar</td>
            @foreach($data['jenis_pembayaran'] as $jenis)
              <td>{{$jenis->jenis}}</td>
            @endforeach
            <td>total</td>
            <td>status</td>
            <td>bulan</td>
            <td>tahun</td>
        </tr>
        </thead>
        <tbody>
          @foreach($hasil as $row)
            <tr>
              <td>{{(empty($row['id_bayar']))?"":$row['id_bayar']}}</td>
              <td>{{(empty($row['no_induk']))?"":$row['no_induk']}}</td>
              <td>{{(empty($row['nama_santri']))?"":$row['nama_santri']}}</td>
              <td>{{(empty($row['kode_kelas']))?"":$row['kode_kelas']}}</td>
              <td>{{(empty($row['kode_murroby']))?"":$row['kode_murroby']}}</td>
              <td>{{(empty($row['tanggal_bayar']))?"":$row['tanggal_bayar']}}</td>
              @foreach($data['jenis_pembayaran'] as $jenis)
                <td>{{(empty($row[$jenis->jenis]))?"":$row[$jenis->jenis]}}<input type="number" name="jenis[]" value="{{(empty($row[$jenis->jenis]))?"":preg_replace("/([^0-9\\.])/i", "", $row[$jenis->jenis])}}"></td>
              @endforeach
              <td>{{(empty($row['total']))?"":$row['total']}}</td>
              <td>{{(empty($row['status']))?"":$row['status']}}</td>
              <td>{{(empty($row['bulan']))?"":$row['bulan']}}</td>
              <td>{{(empty($row['tahun']))?"":$row['tahun']}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
