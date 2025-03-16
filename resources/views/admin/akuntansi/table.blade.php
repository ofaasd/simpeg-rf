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
            @foreach($var['dari_uang_masuk'][$value] as $key=>$isi)
              {{$isi}} <a href="javascript:void(0)" data-id="{{$key}}" class="edit_uang_masuk" data-bs-toggle="modal" data-bs-target="#uangMasuk"><span class="mdi mdi-pencil"></span></a> <a href="javascript:void(0)" data-id="{{$key}}" class="delete_uang_masuk"><span class="mdi mdi-delete text-danger"></span></a><br />
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
              @foreach($var['note_uang_keluar'][$value] as $key=>$isi)
                {{$isi}} <a href="javascript:void(0)" class="edit_uang_keluar" data-id="{{$key}}" data-bs-toggle="modal" data-bs-target="#uangKeluar"><span class="mdi mdi-pencil"></span></a> <a href="javascript:void(0)" data-id="{{$key}}" class="delete_uang_keluar"><span class="mdi mdi-delete text-danger"></span></a><br />
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
      <th colspan=4>Sisa Uang</th>
      <th>{{number_format(($jumlah_masuk - $jumlah_keluar),0,",",".")}}</th>
    </tr>
  </tfoot>
</table>

<script>

    title = "Pemasukan dan Pengeluaran Pondok Bulan {{$var['list_bulan'][$var['bulan']]}} {{$var['tahun']}}";
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
    $("#tambah").click(function(){
      $("#list-detail").append(`<div class='detail'  style="margin:10px 0;">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-floating form-floating-outline">
                      <input type="text" id='modalEditUsernote' name="note[]" class="form-control">
                      <label for="modalEditUsernote">Note</label>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="form-floating form-floating-outline">
                      <input type="number" id='modalEditUserjumlah' name="jumlah[]" class="form-control">
                      <label for="modalEditUserjumlah">Jumlah (Rp.)</label>
                    </div>
                  </div>
                </div>
              </div>`);
    });
    $("#remove").click(function(){
      $("#list-detail > .detail:last").remove();
    });
    
    $(".edit_uang_masuk").on("click",function(){
    const id = $(this).attr("data-id");
    $.ajax({
      data : {"id" : id},
      url: ''.concat(baseUrl).concat('admin/uang_masuk/get_id'),
      type:'post',
      success : function success(data) {
        data = JSON.parse(data);
        console.log(data);
        $("#id_uang_masuk").val(data.data.id)
        $("#sumber_uang_masuk").val(data.data.sumber);
        $("#jumlah_uang_masuk").val(data.data.jumlah);
        const tanggal_transaksi = data.tanggal;
        $("#tanggal_uang_masuk").val(tanggal_transaksi);
      }
    });
  });
  $(".delete_uang_masuk").on("click",function(){
    let text = "Apakah yakin ingin menghapus ? ";
    if (confirm(text) == true) {
      const id = $(this).attr("data-id");
      $.ajax({
        data : {"id" : id},
        url: ''.concat(baseUrl).concat('admin/uang_masuk/hapus'),
        type:'post',
        success : function success(data) {
          Swal.fire({
            icon: 'success',
            title: 'Successfully '.concat(' Deleted !'),
            text: ''.concat('Pemasukan ', ' ').concat(' berhasil Dihapus'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
          reload_table();
        }
      });
    }else{
      return false;
    }
  });
  $(".edit_uang_keluar").on("click",function(){
    const id = $(this).attr("data-id");
    $.ajax({
      data : {"id" : id},
      url: ''.concat(baseUrl).concat('admin/uang_keluar/get_id'),
      type:'post',
      success : function success(data) {
        data = JSON.parse(data);
        console.log(data);
        $("#id_uang_keluar").val(data.data.id)
        $("#keterangan_uang_keluar").val(data.data.keterangan);
        $("#jumlah_uang_keluar").val(data.data.jumlah);
        const tanggal_transaksi = data.tanggal;
        $("#tanggal_uang_keluar").val(tanggal_transaksi);
      }
    });
  });
  $(".delete_uang_keluar").on("click",function(){
    let text = "Apakah yakin ingin menghapus ? ";
    if (confirm(text) == true) {
      const id = $(this).attr("data-id");
      $.ajax({
        data : {"id" : id},
        url: ''.concat(baseUrl).concat('admin/uang_keluar/hapus'),
        type:'post',
        success : function success(data) {
          Swal.fire({
            icon: 'success',
            title: 'Successfully '.concat(' Updated !'),
            text: ''.concat('Pengeluaran ', ' ').concat(' berhasil Dihapus'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
          reload_table();
        }
      });
    }else{
      return false;
    }
  });


  </script>
