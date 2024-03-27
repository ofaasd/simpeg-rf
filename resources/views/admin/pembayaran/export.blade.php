<table class="table" id="table-laporan">

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

  @php
  $i = 1;
  @endphp
  @foreach ($pembayaran as $s)
    @php
      $total = 0;
    @endphp
      <tr>
          <td>{{$s->id}}</td>
          <td>{{$s->no_induk}}</td>
          <td>{{$s->nama}}</td>
          <td>{{$s->kelas}}</td>
          <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>
          <td>{{$s->tanggal_bayar}}</td>
          @foreach($data['jenis_pembayaran'] as $jenis)
            <td data-format="RP#,##0_-">Rp{{number_format($data['detail'][$s->id][$jenis->id],0,".",",")}}</td>
          @endforeach
          <td data-format="RP#,##0_-">Rp{{number_format($s->jumlah,0,".",",")}}</td>
          <td bgcolor="#2ecc71">{{$s->validasi}}</td>
          <td>{{$periode}}</td>
          <td>{{$tahun}}</td>
      </tr>
    @php $i++; @endphp
  @endforeach
  @foreach ($data['sisa_santri'] as $s)
      <tr>
        <td></td>
        <td>{{$s->no_induk}}</td>
        <td>{{$s->nama}}</td>
        <td>{{$s->kelas}}</td>
        <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>
        <td></td>
        @foreach($data['jenis_pembayaran'] as $jenis)
          <td data-format="RP#,##0_-" bgcolor="#2ecc71" >Rp0</td>
        @endforeach
        <td data-format="RP#,##0_-" bgcolor="#2ecc71">Rp0</td>
        <td bgcolor="#2ecc71">0</td>
        <td>{{$periode}}</td>
        <td>{{$tahun}}</td>
    </tr>
    @endforeach
</table>
<script
    src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script
    src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script>
  function exportCSVExcel() {
    $('#table-laporan').table2excel({
      exclude: ".no-export",
      filename: "download.xls",
      fileext: ".xls",
      exclude_links: true,
      exclude_inputs: true
    });
  }
  document.addEventListener("DOMContentLoaded", function(event) {
    exportCSVExcel()

    const periode = '{{$periode}}';
    const tahun = '{{$tahun}}';
    const kelas = '{{$kelas}}';
    location.href = `{{URL::to('/pembayaran')}}?periode=${periode}&tahun=${tahun}&kelas=${kelas}`;
  });
</script>
