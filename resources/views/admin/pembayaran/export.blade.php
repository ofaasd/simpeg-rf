<table class="table" id="table-laporan">
  <thead>
      <tr>
          <td>ID Bayar</td>
          <td>No. Induk</td>
          <td>Nama Santri</td>
          <td>Kode Kelas</td>
          <td>Kode Murroby</td>
          <td>Tanggal Bayar</td>
          @foreach($data['jenis_pembayaran'] as $jenis)
            <td>{{$jenis->jenis}}</td>
          @endforeach
          <td>Total</td>
          <td>Status</td>
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
              <td>{{$s->id}}</td>
              <td>{{$s->no_induk}}</td>
              <td>{{$s->nama}}</td>
              <td>{{$s->kelas}}</td>
              <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>
              <td>{{$s->tanggal_bayar}}</td>
              @foreach($data['jenis_pembayaran'] as $jenis)
                <td>{{$data['detail'][$s->id][$jenis->id]}}</td>
              @endforeach
              <td>Rp.  {{number_format($s->jumlah, 0, ',', '.')}}</td>
              <td>{{$s->validasi}}</td>
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
              <td>0</td>
            @endforeach
            <td>Rp.  0</td>
            <td>0</td>
        </tr>
        @endforeach
  </tbody>
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
