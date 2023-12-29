@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('content')
<!--/ Navbar pills -->
<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>Catatan Pemasukan dan Pengeluaran Pondok {{$var['list_bulan'][$var['bulan']]}} {{$var['tahun']}}</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <select name="bulan" id="bulan" class="form-control">
                  @foreach($var['list_bulan'] as $key=>$value)
                  <option value={{$key}} {{($key == $var['bulan'])?"selected":""}}>{{$value}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <select name="bulan" id="bulan" class="form-control">
                  @for($i=date('Y'); $i>(int)(date('Y'))-5; $i--)
                  <option value={{$i}} {{($i == $var['tahun'])?"selected":""}}>{{$i}}</option >
                  @endfor
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uangMasuk"> Tambah Uang Masuk</button>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uangKeluar"> Tambah Uang Keluar</button>
          </div>
        </div>
        <div id="table_akuntansi">
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
                <th colspan=4>Sisa Uang</th>
                <th>{{number_format(($jumlah_masuk - $jumlah_keluar),0,",",".")}}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- uang Saku Masuk -->
<div class="modal fade" id="uangMasuk" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Tambah Uang Masuk</h3>
          <p class="pt-1">Masukan detail pemasukan pondok / jumlah pemasukan dari laporan dapat dilihat di pojok sebelah kanan halaman</p>
        </div>
        <form id="formSakuMasuk" class="row g-4" onsubmit="return false">
          <input type="hidden" name="jenis" value="saku_masuk">

          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id='modalEditUserSumber' name="sumber" class="form-control">
              <label for="modalEditUserSumber">Sumber Dana</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="number" id='modalEditUserjumlah' name="jumlah" class="form-control">
              <label for="modalEditUserjumlah">Jumlah (Rp.)</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='modalEditUserTanggal' name="tanggal" class="form-control">
              <label for="modalEditUserTanggal">Tanggal</label>
            </div>
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="uangKeluar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Pengeluaran Pondok</h3>
          <p class="pt-1">Detail pengeluaran pondok </p>
        </div>
        <form id="formSakuKeluar" class="row g-4" onsubmit="return false">
          <input type="hidden" name="jenis" value="saku_keluar">
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id='modalEditUsernote' name="catatan" class="form-control">
              <label for="modalEditUsernote">Catatan</label>
            </div>
          </div>
          <div id="list-detail">
            <div class='detail' style="margin:10px 0;">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="date" id='modalEditUserTanggal' name="tanggal" class="form-control">
                    <label for="modalEditUserTanggal">Tanggal</label>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="number" id='modalEditUserjumlah' name="jumlah" class="form-control">
                    <label for="modalEditUserjumlah">Jumlah (Rp.)</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {{-- <div class="col-12">
            <div class="form-floating form-floating-outline">
              <a href="#" class="btn btn-primary me-sm-3 me-1" id='tambah'>+ Tambah Daftar</a>
              <a href="#" class="btn btn-danger me-sm-3 me-1" id='remove'>- Kurangi Daftar</a>
            </div>
          </div> --}}
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection
<script>
document.addEventListener("DOMContentLoaded", function(event) {
  const title = "Pemasukan dan Pengeluaran Pondok Bulan {{$var['list_bulan'][$var['bulan']]}} {{$var['tahun']}}";
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
  $('#formSakuMasuk').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    //showBlock();
    $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('admin/uang_masuk/store'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        //showUnblock();
        //hilangkan modal
        $('#uangMasuk').modal('hide');
        //reset form
        reload_table();
        //refresh table
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('uang Saku ', ' ').concat(' berhasil ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function error(err) {
        //showUnblock();
        Swal.fire({
          title: 'Duplicate Entry!',
          text: title + ' Not Saved !',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
  $('#formSakuKeluar').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    //showBlock();
    $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('admin/uang_keluar/store'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        //showUnblock();
        //hilangkan modal
        $('#uangKeluar').modal('hide');
        //reset form
        reload_table();
        //refresh table
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Pengeluaran ', ' ').concat(' Berhasil Ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function error(err) {
        //showUnblock();
        Swal.fire({
          title: 'Duplicate Entry!',
          text: title + ' Not Saved !',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
  function reload_table(bulan, tahun){
    $.ajax({
        data: {'bulan' : bulan, "tahun" : tahun},
        url: ''.concat(baseUrl).concat('admin/akuntansi/get_all'),
        type: 'POST',
        success: function success(data) {
          $("#table_akuntansi").html(data);
        },
      });
  }
});



</script>
