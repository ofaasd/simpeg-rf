<table class="dataTable table">
  <thead>
    <tr>
      <td>Tanggal</td>
      <td>Uang Masuk (Rp.)</td>
      <td>Keterangan</td>
      <td>Uang Keluar (Rp.)</td>
      <td>Keterangan</td>
    </tr>
  </thead>
  <tbody id="table_uang_saku">
    @php
    $i = 1;
    $jumlah_masuk = 0;
    $jumlah_keluar = 0;
    @endphp
    @foreach($var['tanggal'] as $value)
      @if(!empty($var['uang_masuk'][$value]))
        <tr>
          <td valign="top">{{$value}}</td>
          <td valign="top">
            @foreach($var['uang_masuk'][$value] as $isi)
              {{number_format($isi,0,",",".")}}<br />
            @php $jumlah_masuk += $isi @endphp
            @endforeach
          </td>
          <td valign="top">
            @foreach($var['dari_uang_masuk'][$value] as $isi)
              {{$isi}}<br />
            @endforeach
          </td>
          <td valign="top">
            @if(!empty($var['uang_keluar'][$value]))
              @foreach($var['uang_keluar'][$value] as $isi)
              {{number_format($isi,0,",",".")}}<br />
              @php $jumlah_keluar += $isi @endphp
              @endforeach
            @endif
          </td>
          <td valign="top">
            @if(!empty($var['note_uang_keluar'][$value]))
              @foreach($var['note_uang_keluar'][$value] as $isi)
                {{$isi}}<br />
              @endforeach
            @endif
          </td>
        </tr>
      @elseif(!empty($var['uang_keluar'][$value]))
      <tr>
        <td valign="top">{{$value}}</td>
        <td></td>
        <td></td>
        <td valign="top">
          @foreach($var['uang_keluar'][$value] as $isi)
          {{number_format($isi,0,",",".")}}<br />
          @php $jumlah_keluar += $isi @endphp
          @endforeach
        </td>
        <td valign="top">
          @if(!empty($var['note_uang_keluar'][$value]))
            @foreach($var['note_uang_keluar'][$value] as $isi)
              {{$isi}}<br />
            @endforeach
          @endif
        </td>
      </tr>
      @endif
    @php
    $i++;
    @endphp
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <th>
        Jumlah Uang Masuk
      </th>
      <th>
        {{number_format($jumlah_masuk,0,",",".")}}
      </th>
      <th>
        Jumlah Uang Keluar
      </th>
      <th>
        {{number_format($jumlah_keluar,0,",",".")}}
      </th>
      <th></th>
    </tr>
    <tr>
      <th colspan=4>Sisa Uang Saku</th>
      <th>{{number_format(($jumlah_masuk - $jumlah_keluar),0,",",".")}}</th>
    </tr>
  </tfoot>
</table>

<script>
  $('.dataTable').dataTable({
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
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
              },
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
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
              },
            },
            {
              extend: 'excel',
              title: title,
              text: '<i class="mdi mdi-file-excel-outline me-1" ></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
              },
            },
            {
              extend: 'pdf',
              title: title,
              text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
              },
            },
            {
              extend: 'copy',
              title: title,
              text: '<i class="mdi mdi-content-copy me-1" ></i>Copy',
              className: 'dropdown-item',

            }
          ]
        }
      ]
    });
</script>
