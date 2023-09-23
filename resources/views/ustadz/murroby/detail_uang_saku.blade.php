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
        <h4>Detail Uang Saku Santri {{$var['santri']->nama}} Bulan {{$var['list_bulan'][$var['bulan']]}} {{$var['tahun']}}</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uangMasuk"> Tambah Uang Masuk</button>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uangKeluar"> Tambah Uang Keluar</button> -->
          </div>
        </div>

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
              <input type="text" class="form-control" name="no_induk" value="{{$var['no_induk']}}" readonly>
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
          <h3 class="mb-2">Pengeluaran Uang Saku</h3>
          <p class="pt-1">Pengeluaran Uang Saku dapat diisi oleh Murroby Santri</p>
        </div>
        <form id="formSakuKeluar" class="row g-4" onsubmit="return false">
          <input type="hidden" name="jenis" value="saku_keluar">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" class="form-control" name="no_induk" value="{{$var['no_induk']}}" readonly>
              <label for="NamaSantriMasuk">Nama Santri</label>
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
                    <input type="number" id='modalEditUserjumlah' name="jumlah[]" class="form-control">
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
  $('.dataTable').dataTable();
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
                <td class='text-center'><a href="{{url('ustadz/uang-saku/')}}/${item.no_induk}"><span class="mdi mdi-information"></span></a></td>
              </tr>`);
              i++;
          });
        },
      });
  }
});



</script>
