@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')


@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive" style="overflow-x:scroll">
    <div class="col-md-12">
        <h2 style='text-align:center'>Table Uang Masuk Keluar Santri</h2>
        <table class="table" id="table-laporan">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Induk</th>
                    <th>Nama</th>
                    <th>Total Belanja TGL 1-31 Mei</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($santri as $s)
                    @php
                    $total = 0;
                    @endphp

                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$s->no_induk}}</td>
                        <td>{{$s->nama}}</td>
                        <td>Rp.  {{number_format($list_santri[$s->no_induk], 0, ',', '.')}}</td>
                        <td>Rp.  {{number_format($list_saku[$s->no_induk], 0, ',', '.')}}</td>
                    </tr>

                    @php $i++; @endphp
                @endforeach
            </tbody>
        </table>
  </div>
</div>
@endsection
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    const title = 'Syahriyah';
    $("#table-laporan").DataTable({
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function render(data, type, full, meta) {
            return '';
          }
        },
      ],
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