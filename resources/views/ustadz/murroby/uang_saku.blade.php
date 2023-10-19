@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css')}}" />
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/autosize/autosize.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-profile.js')}}"></script>
<script src="{{asset('assets/js/forms-selects.js')}}"></script>
<script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
@endsection

@section('content')

@include('ustadz/murroby/header')
<!-- Navbar pills -->
@include('ustadz/murroby/nav')
<!--/ Navbar pills -->
<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>List Santri</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uangMasuk"> Tambah Uang Masuk</button>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uangKeluar"> Tambah Uang Keluar</button>
          </div>
        </div>
        <table class="dataTable table">
          <thead>
            <tr>
              <td></td>
              <td>NIS</td>
              <td>Nama</td>
              <td>Kelas</td>
              <td>Uang Masuk</td>
              <td>Tanggal TF</td>
              <td>Total Saku (Rp.)</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody id="table_uang_saku">
            @php
            $i = 1;
            @endphp
            @foreach($var['list_santri'] as $santri)
              <tr>
                <td>{{$i}}</td>
                <td>{{$santri->no_induk}}</td>
                <td>{{$santri->nama}}</td>
                <td>{{$santri->kelas}}</td>
                <td>{{number_format($var['uang_masuk'][$santri->no_induk],0,",",".")}}</td>
                <td>{{(!empty($var['tanggal_masuk'][$santri->no_induk]))?date('d-m-Y', strtotime($var['tanggal_masuk'][$santri->no_induk])):''}}</td>
                <td>{{number_format($var['uang_saku'][$santri->no_induk],0,",",".")}}</td>
                @if(empty($id))
                <td class='text-center'><a href="{{url('ustadz/uang-saku/' . $santri->no_induk)}}"><span class="mdi mdi-information"></span></a></td>
                @else
                <td class='text-center'><a href="{{url('murroby/uang-saku-detail/' . $id . '/' . $santri->no_induk)}}"><span class="mdi mdi-information"></span></a></td>
                @endif
              </tr>
            @php
            $i++;
            @endphp
            @endforeach
          </tbody>
        </table>
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
          <h3 class="mb-2">Tambah Uang Saku</h3>
          <p class="pt-1">Penambahan uang saku santri jika tidak melalui halaman payment.</p>
        </div>
        <form id="formSakuMasuk" class="row g-4" onsubmit="return false">
          <input type="hidden" name="jenis" value="saku_masuk">
          <input type="hidden" name='pegawai_id' id="pegawai_id" value="{{$var['EmployeeNew']->id}}">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="NamaSantriMasuk" name="nama_santri" class="form-control select2">
                @foreach($var['list_santri'] as $santri)
                <option value='{{$santri->no_induk}}'>{{$santri->nama}}</option>
                @endforeach
              </select>
              <label for="NamaSantriMasuk">Nama Santri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="modalEditUserDari" name="dari" class="form-control select2">
                <option value='2'>Kunjungan Walsan</option>
                <option value='3'>Sisa Bulan Kemarin</option>
              </select>
              <label for="modalEditUserDari">Asal Saku Masuk</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" onkeyup="splitInDots(this)" id='modalEditUserjumlah' name="jumlah" class="form-control">
              <label for="modalEditUserjumlah">Jumlah (Rp.)</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='modalEditUserTanggal' name="tanggal" class="form-control" value="{{date('Y-m-d')}}">
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
          <h3 class="mb-2">Pengeluaran Uang Saku</h3>
          <p class="pt-1">Pengeluaran Uang Saku dapat diisi oleh Murroby Santri</p>
        </div>
        <form id="formSakuKeluar" class="row g-4" onsubmit="return false">
          <input type="hidden" name="jenis" value="saku_keluar">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="NamaSantrikeluar" name="nama_santri" class="form-control select2">
                @foreach($var['list_santri'] as $santri)
                <option value='{{$santri->no_induk}}'>{{$santri->nama}}</option>
                @endforeach
              </select>
              <label for="NamaSantrikeluar">Nama Santri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='modalEditUserTanggal' name="tanggal" class="form-control">
              <label for="modalEditUserTanggal">Tanggal</label>
            </div>
          </div>
          <div id="list-detail">
            <div class='detail' style="margin:10px 0;">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" id='modalEditUsernote' name="note[]" class="form-control">
                    <label for="modalEditUsernote">Note</label>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="text" onkeyup="splitInDots(this)"  name="jumlah[]" class="form-control">
                    <label for="modalEditUserjumlah">Jumlah (Rp.)</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating form-floating-outline">
              <a href="#" class="btn btn-primary me-sm-3 me-1" id='tambah'>+ Tambah Daftar</a>
              <a href="#" class="btn btn-danger me-sm-3 me-1" id='remove'>- Kurangi Daftar</a>
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


@endsection
<script>
document.addEventListener("DOMContentLoaded", function(event) {
  const title = 'Uang Saku Murroby : {{$var['EmployeeNew']->nama}} ({{$var['list_bulan'][(int)date("m")] . " " . date("Y")}} )';
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
                columns: [1, 2, 3, 4, 5, 6],
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
                columns: [1, 2, 3, 4, 5, 6],
              },
            },
            {
              extend: 'excel',
              title: title,
              text: '<i class="mdi mdi-file-excel-outline me-1" ></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
              },
            },
            {
              extend: 'pdf',
              title: title,
              text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
              },
              customize : function(doc){
                  doc.content[1].table.widths = [20,170,30,"*","*","*"];

              }
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
                    <input type="text" onkeyup="splitInDots(this)"  name="jumlah[]" class="form-control">
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
    showBlock();
    $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('ustadz/uang-saku'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        showUnblock();
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
        showUnblock();
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
    showBlock();
    $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('ustadz/uang-saku'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        showUnblock();
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
        showUnblock();
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
  function reload_table(){
    $.ajax({
        data: {'pegawai_id' : $("#pegawai_id").val()},
        url: ''.concat(baseUrl).concat('ustadz/uang-saku/get_all'),
        type: 'POST',
        success: function success(data) {
          $("#table_uang_saku").html("");
          let i = 1;
          data.list_santri.forEach((item,index) => {
            $("#table_uang_saku").append(`<tr>
                <td>${i}</td>
                <td>${item.no_induk}</td>
                <td>${item.nama}</td>
                <td>${item.kelas}</td>
                <td>${data.uang_saku[item.no_induk]}</td>
                @if(empty($id))
                <td class='text-center'><a href="{{url('ustadz/uang-saku/')}}/${item.no_induk}"><span class="mdi mdi-information"></span></a></td>
                @else
                <td class='text-center'><a href="{{url('murroby/uang-saku-detail/' . $id)}}/${item.no_induk}"><span class="mdi mdi-information"></span></a></td>
                @endif
              </tr>`);
              i++;
          });
        },
      });
  }
});



</script>
