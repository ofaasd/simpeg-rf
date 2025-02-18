@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
@endsection
@section('page-script')
  <script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
@endsection
@section('content')
<style>
  table.dataTable td, table.dataTable th {
    font-size: 0.8em;
  }
</style>
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
                <select name="tahun" id="tahun" class="form-control">
                  @for($i=date('Y'); $i>(int)(date('Y'))-5; $i--)
                  <option value={{$i}} {{($i == $var['tahun'])?"selected":""}}>{{$i}}</option >
                  @endfor
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <button type="button" id="btnUangMasuk" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uangMasuk"> Tambah Uang Masuk</button>
            <button type="button" id="btnUangKeluar"  class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uangKeluar"> Tambah Uang Keluar</button>
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
          <input type="hidden" name="id_uang_masuk" id="id_uang_masuk">
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id='sumber_uang_masuk' name="sumber" class="form-control">
              <label for="modalEditUserSumber">Deskripsi Uang Masuk</label>
            </div>
          </div>
          <div class="col-6 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="nama_keg" id="nama_keg_in" class="form-control">
                <option value="RUTIN BULANAN">RUTIN BULANAN</option>
                <option value="RUTIN TAHUNAN">RUTIN TAHUNAN</option>
                <option value="HAFLAH">HAFLAH</option>
                <option value="0">Lainnya</option>
              </select>
              <input type="text" class="form-control mt-2 mb-2" name="nama_kegiatan" placeholder="Masukan Kegiatan Lainnya" id="nama_kegiatan_in"> 
              <label for="modalEditUserSumber">Nama Kegiatan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="number" id='jumlah_uang_masuk' name="jumlah" class="form-control">
              <label for="modalEditUserjumlah">Jumlah (Rp.)</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal_uang_masuk' name="tanggal" class="form-control">
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
          <input type="hidden" name="id_uang_keluar" id="id_uang_keluar">
          <div class="col-12 col-md-12">
            <div class="form-floating form-floating-outline">
              <input type="text" id='keterangan_uang_keluar' name="keterangan" class="form-control">
              <label for="modalEditUsernote">Deskripsi Uang Keluar</label>
            </div>
          </div>
          <div class="col-6 col-md-6">
            <div class="form-floating form-floating-outline">
              <select name="nama_keg" id="nama_keg_out" class="form-control">
                <option value="RUTIN BULANAN">RUTIN BULANAN</option>
                <option value="RUTIN TAHUNAN">RUTIN TAHUNAN</option>
                <option value="HAFLAH">HAFLAH</option>
                <option value="0">Lainnya</option>
              </select>
              <input type="text" class="form-control mt-2 mb-2" name="nama_kegiatan" placeholder="Masukan Kegiatan Lainnya" id="nama_kegiatan_out"> 
              <label for="modalEditUserSumber">Nama Kegiatan</label>
            </div>
          </div>
          <div id="list-detail">
            <div class='detail' style="margin:10px 0;">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="date" id='tanggal_uang_keluar' name="tanggal" class="form-control">
                    <label for="tanggal_uang_keluar">Tanggal</label>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-floating form-floating-outline">
                    <input type="number" id='jumlah_uang_keluar' name="jumlah" class="form-control">
                    <label for="jumlah_uang_keluar">Jumlah (Rp.)</label>
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
  let title = "Pemasukan dan Pengeluaran Pondok Bulan {{$var['list_bulan'][$var['bulan']]}} {{$var['tahun']}}";
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
    });
  $("#nama_kegiatan_in").hide();
  $("#nama_kegiatan_out").hide();
  $("#tambah").on("click",function(){
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
  $("#remove").on("click",function(){
    $("#list-detail > .detail:last").remove();
  });
  $('#formSakuMasuk').submit(function(e) {
    const bulan = $("#bulan").val();
    const tahun = $("#tahun").val();
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
        reload_table(bulan,tahun);
        resetFormUangMasuk();
        //refresh table
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Pemasukan ', ' ').concat(' berhasil ditambahkan'),
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
    const bulan = $("#bulan").val();
    const tahun = $("#tahun").val();
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
        reload_table(bulan,tahun);
        resetFormUangKeluar();
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
  $("#bulan").change(function(){
    const bulan = $(this).val();
    const tahun = $("#tahun").val();
    reload_table(bulan,tahun);
  });
  $("#tahun").change(function(){
    const bulan = $("#bulan").val();
    const tahun = $(this).val();
    reload_table(bulan,tahun);
  });
  $("#btnUangMasuk").on("click",function(){
    resetFormUangMasuk();
  });
  $("#btnUangKeluar").on("click",function(){
    resetFormUangKeluar();
  });

  $(".edit_uang_masuk").on("click",function(){
    showBlockModal('#uangMasuk');
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
        showUnblockModal('#uangMasuk');
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
    showBlockModal('#uangKeluar');
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
        showUnblockModal('#uangKeluar');
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
  
  $("#nama_keg_in").change(function(){
    const isi = $(this).val();
    if(isi == 0){
      $("#nama_kegiatan_in").show();
      $("#nama_kegiatan_in").val('');
    }else{
      $("#nama_kegiatan_in").hide();
      $("#nama_kegiatan_in").val(isi);
    }
  });
  $("#nama_keg_out").change(function(){
    const isi = $(this).val();
    if(isi == 0){
      $("#nama_kegiatan_out").show();
      $("#nama_kegiatan_out").val('');
    }else{
      $("#nama_kegiatan_out").hide();
      $("#nama_kegiatan_out").val(isi);
    }
  });

});

function reload_table(bulan, tahun){
  showBlock();
  $.ajax({
      data: {'bulan' : bulan, "tahun" : tahun},
      url: ''.concat(baseUrl).concat('admin/akuntansi/get_all'),
      type: 'POST',
      success: function success(data) {
        $("#table_akuntansi").html(data);
        showUnblock();
      },
    });
}
function resetFormUangMasuk(){
  $("#id_uang_masuk").val("");
  $("#sumber_uang_masuk").val("");
  $("#jumlah_uang_masuk").val("");
  $("#tanggal_uang_masuk").val("");
}
function resetFormUangKeluar(){
  $("#id_uang_keluar").val("");
  $("#keterangan_uang_keluar").val("");
  $("#jumlah_uang_keluar").val("");
  $("#tanggal_uang_keluar").val("");
}

</script>
