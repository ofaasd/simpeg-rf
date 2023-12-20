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
      <form method="POST" action=''>
          <div class="form-floating form-floating-outline mb-4 col-md-4">
            <select name="periode" id="periode" class='form-control col-md-4'>
              <option value="0">Semua</option>
              <option value="1">Januari</option>
              <option value="2">Februari</option>
              <option value="3">Maret</option>
              <option value="4">April</option>
              <option value="5">Mei</option>
              <option value="6">Juni</option>
              <option value="7">Juli</option>
              <option value="8">Agustus</option>
              <option value="9">September</option>
              <option value="10">Oktober</option>
              <option value="11">November</option>
              <option value="12">Desember</option>
            </select>
            <label for="periode">Periode</label>
          </div>
          <div class="form-floating form-floating-outline mb-4 col-md-4">
            <input type="number" name='tahun' class='form-control col-md-2' value='{{ date('Y') }}'>
            <label for="tahun">Tahun</label>
          </div>
          <div class="form-floating form-floating-outline col-md-12">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Lihat</button>
          </div>
      </form><br />
      @if(!empty($data))

          <h2 style='text-align:center'>Syahriyah {{ $data['bulan'][$data['periode']] }} {{ $data['tahun'] }}</h2>
              <table class="" id="table-laporan">
                  <thead>
                      <tr>
                          <td>No.</td>
                          <td>Kode Kelas</td>
                          <td>Kode Murroby</td>
                          <td>No. no_induk</td>
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
    $("#table-laporan").DataTable({
      dom:
        '<"row mx-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      buttons: [
      {
        extend: 'collection',
        className: 'btn btn-label-primary dropdown-toggle mx-3',
        text: '<i class="mdi mdi-export-variant me-sm-1"></i>Export',
        buttons: [
          {
            extend: 'print',
            title: title,
            text: '<i class="mdi mdi-printer-outline me-1" ></i>Print',
            className: 'dropdown-item',
            customize: function customize(win) {
              //customize print view for dark
              $(win.document.body)
                .css('color', config.colors.headingColor)
                .css('border-color', config.colors.borderColor)
                .css('background-color', config.colors.body);
              $(win.document.body)
                .find('table')
                .addClass('compact')
                .css('color', 'inherit')
                .css('border-color', 'inherit')
                .css('background-color', 'inherit');
            }
          },
          {
            extend: 'csv',
            title: title,
            text: '<i class="mdi mdi-file-document-outline me-1" ></i>Csv',
            className: 'dropdown-item',
          },
          {
            extend: 'excel',
            title: title,
            text: '<i class="mdi mdi-file-excel-outline me-1" ></i>Excel',
            className: 'dropdown-item',

          },
          {
            extend: 'pdf',
            title: title,
            text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
            className: 'dropdown-item',

          },
          {
            extend: 'copy',
            title: title,
            text: '<i class="mdi mdi-content-copy me-1" ></i>Copy',
            className: 'dropdown-item',

          }
        ]
      },
    ]
    });
  });
</script>
