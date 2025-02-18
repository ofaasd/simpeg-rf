@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-md-6">
        <h5 class="card-title mb-0"><a href="{{URL::previous()}}" class="btn btn-primary"><i class="mdi mdi-arrow-left"></i></a> Tambah Pembayaran</h5>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="col-md-12" style="padding:20px;">
        @if(!empty(session('message')))
        <p class="alert alert-primary"></p>
        @endif
        @if(!empty(session('error')))
            <p class="alert alert-danger"></p>
        @endif
        <br />
        <form id="formPembayaran" action="javascript:void(0)">
            <input type="hidden" name="id" value="{{(!empty($pembayaran))?$pembayaran->id:''}}">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-4">
                  <label class="form-label">Jenis Pembayaran</label><br />

                  <div class="form-check form-check-inline">
                      <input class="form-check-input" required type="radio" checked name="tipe" id="bank_transfer" value="Bank" {{(!empty($pembayaran) && $pembayaran->tipe == "Bank")?"checked":""}}>
                      <label class="form-check-label" for="bank_transfer">Bank Transfer</label>
                  </div>
                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="tipe" id="cash" value="Cash" {{(!empty($pembayaran) && $pembayaran->tipe == "Cash")?"checked":""}}>
                      <label class="form-check-label" for="cash">Cash</label>
                  </div>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <select id="nama_santri" name="nama_santri" class="form-control col-md-6" onchange="update_riwayat()" required>
                      @if(!empty($curr_santri))
                        <option value='{{$curr_santri->no_induk}}'>{{$curr_santri->nama}} - {{$curr_santri->no_induk}}</option>
                      @else
                        <option value=''>Masukan Nama Santri</option>
                      @endif
                      @foreach($santri as $row)
                          <option value="{{$row->no_induk}}">{{$row->nama}} - {{$row->no_induk}}</option>
                      @endforeach
                  </select>
                  <label for="nama">Nama Santri</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">

                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Lihat Riwayat Pembayaran
                  </button>
                  <!-- Modal -->
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Riwayat Pembayaran</h5>
                      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      </div>
                      <div class="modal-body" id="riwayat">
                      <div class="alert alert-primary">
                        Belum ada riwayat pembayaran
                      </div>
                      </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                    </div>
                  </div>

                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <input class="form-control col-md-6" type="text" onkeyup="splitInDots(this)" name="jumlah" value="{{(!empty($pembayaran))?number_format($pembayaran->jumlah,0,',','.'):''}}">
                  <label for="jumlah">Pembayaran Sebesar (Rp)</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <input class="form-control col-md-6" type="date" name="tanggal_bayar" value="{{(!empty($pembayaran))?$pembayaran->tanggal_bayar:''}}">
                  <label class="form-label">Tanggal Bayar</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">

                  <select name="periode" class="form-control col-md-6" id="periode" onchange="update_riwayat()">
                    @foreach($list_bulan as $key=>$row)
                      <option value="{{$key}}" {{($key == date('m'))?"selected":""}}>{{$row }}</option>
                    @endforeach
                  </select>
                  <label class="form-label">Periode Bayar</label>
                </div>
                <input type="hidden" name="tahun" value="{{(!empty($pembayaran))?$pembayaran->tahun:date('Y')}}">
                <div class="form-floating form-floating-outline mb-4 col-md-12">

                    <select name="bank_pengirim"  class="form-control col-md-6">
                        @foreach($ref_bank as $bank)
                          <option value="{{$bank->id}}" {{(!empty($pembayaran) && $pembayaran->bank_pengirim == $bank->id)?"selected":""}}>{{$bank->nama}}</option>
                        @endforeach
                    </select>
                    <label class="form-label">Bank Pengirim</label>
                </div>

                <div class="form-floating form-floating-outline mb-4 col-md-12">
                    <input class="form-control col-md-6" type="text" name="atas_nama" value="{{(!empty($pembayaran))?$pembayaran->atas_nama:''}}"">
                    <label class="form-label">Pengirim Atas Nama</label>
                </div>

                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <textarea class="form-control col-md-12" name="catatan" >{{(!empty($pembayaran))?$pembayaran->catatan:''}}</textarea>
                  <label class="form-label">Catatan</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12" id="bukti_bayar">
                  <input type="file" class="form-control col-md-6" name="bukti">
                  <label class="form-label">Upload Bukti Bayar</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <input class="form-control col-md-6" type="text" name="no_wa" value="{{(!empty($pembayaran))?$pembayaran->no_wa:''}}">
                  <label class="form-label">No. Wa / Telp Konfirmasi</label>
                </div>
                <div class="form-group mb-4">
                  <label class="form-label">Validasi</label><br />
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" required type="radio" {{(!empty($pembayaran) && $pembayaran->validasi == "1")?"checked":""}} name="validasi" id="inlineRadioJenis1" value="1">
                    <label class="form-check-label" for="inlineRadioJenis1">Valid</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="validasi" id="inlineRadioJenis2" {{(!empty($pembayaran) && $pembayaran->validasi == "0")?"checked":""}}  value="0">
                    <label class="form-check-label" for="inlineRadioJenis2">Tidak Valid</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="validasi" id="inlineRadioJenis2" value="2" {{(!empty($pembayaran) && $pembayaran->validasi == "2")?"checked":""}} >
                    <label class="form-check-label" for="inlineRadioJenis2">Ditolak</label>
                  </div>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <textarea name="note_validasi" class="form-control col-md-6">{{(!empty($pembayaran))?$pembayaran->note_validasi:''}}</textarea>
                  <label class="form-label">Catatan Validasi</label>
                </div>
                <div class="form-check form-check-primary mb-4">
                  <input class="form-check-input" name="wa_pesan" type="checkbox" value="" id="wa_pesan" checked="">
                  <label class="form-check-label" for="wa_pesan">Kirim Pesan WA</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">

                  <table class="table col-md-6">
                    <tr>
                      <td colspan="5"><label class="form-label" style="font-size:13pt;">Detail Pembayaran</label></td>
                    </tr>
                    <tr>
                      <td>Tunggakan</td>
                      <td><input type="text" id="tunggakan_value" onkeyup="splitInDots(this)" placeholder="0" name="tunggakan" class="form-control" value="0" ></td>
                      <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tunggakanModal">
                          Lihat
                        </button>
                      </td>
                    </tr>
                    @foreach($jenis_pembayaran as $jenis_pembayaran)
                        <tr>
                            <td>{{$jenis_pembayaran->jenis}}<input type="hidden" name="id_jenis_pembayaran[]" value='{{ $jenis_pembayaran->id }}'></td>
                            <td><input type="text" onkeyup="splitInDots(this)" placeholder="0" name="jenis_pembayaran[]" class="form-control" value="{{(!empty($pembayaran))?$list_detail[$jenis_pembayaran->id ]:""}}"></td>
                        </tr>
                    @endforeach
                  </table>
                </div>
              </div>
            </div>
            <div class="form-group mb-4 col-md-12">
              <button type="submit" class="btn btn-primary waves-effect waves-light" id="btnSave">Simpan</button>
            </div>
        </form>
    </div>
  </div>
</div>
<div class="modal fade" id="tunggakanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Lihat Detail Tunggakan</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="target_tunggakan">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
      $('#nama_santri').select2({
          minimumInputLength: 3,
      });
      $("#formPembayaran").submit(function(e){
        e.preventDefault();
        $('#btnSave').block({
          message:
            '<div class="d-flex justify-content-center"><p class="mb-0">Tunggu Sebentar</p> <div class="sk-wave m-0"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div> </div>',
          css: {
            backgroundColor: 'transparent',
            color: '#fff',
            border: '0'
          },
          overlayCSS: {
            opacity: 0.5
          }
        });
        let data = new FormData(this);
        const url_save = "{{URL::to('pembayaran')}}";
        $.ajax({
          method:"POST",
          url: url_save,
          processData: false,
          contentType: false,
          data : data,
          success : function(data){
            if(data.status == 1){
              Swal.fire({
                  title: 'success!',
                  text: 'Data Berhasil Disimpan',
                  icon: 'success',
                  timer : 2000,
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
              }).then(function() {
                window.location = "{{URL::to('pembayaran')}}?periode="+data.periode+"&tahun="+data.tahun+"&kelas="+data.kelas;
              });

            }else{
              Swal.fire({
                  title: 'Gagal!',
                  text: 'Data Gagal Disimpan',
                  icon: 'error',
                  timer : 2000,
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
              })
            }
            $('#btnSave').unBlock();
          }
        });

      })

      $("#cash").on("change",function(){
          $("#bukti_bayar").hide();
          $("#bank_pengirim").hide();
          $("#atas_nama").hide();
      });
      $("#bank_transfer").on("change",function(){
          $("#bukti_bayar").show();
          $("#bank_pengirim").show();
          $("#atas_nama").show();
      });
  });
function update_riwayat(){
  //alert("asdasdasd");
  $.ajax({
    url:'{{ URL::to('pembayaran/get_riwayat') }}',
    data:'nama_santri='+$('#nama_santri').val()+'&periode='+$("#periode").val(),
    method:'POST',
    success: function(data){
      $("#riwayat").html(data);
    }
  });

  $.ajax({
    url:'{{ URL::to('pembayaran/get_jumlah_tunggakan') }}',
    data:'nama_santri='+$('#nama_santri').val(),
    method:'POST',
    success: function(data){
      $("#tunggakan_value").val(data);
    }
  });
  $.ajax({
    url:'{{ URL::to('pembayaran/get_tunggakan') }}',
    data:'nama_santri='+$('#nama_santri').val(),
    method:'POST',
    success: function(data){
      $("#target_tunggakan").html(data);
    }
  });

}
  function reverseNumber(input) {
      return [].map.call(input, function(x) {
          return x;
      }).reverse().join('');
  }

  function plainNumber(number) {
      return number.split('.').join('');
  }

  function splitInDots(input) {

      var value = input.value,
          plain = plainNumber(value),
          reversed = reverseNumber(plain),
          reversedWithDots = reversed.match(/.{1,3}/g).join('.'),
          normal = reverseNumber(reversedWithDots);

      console.log(plain,reversed, reversedWithDots, normal);
      input.value = normal;
  }

  function oneDot(input) {
      var value = input.value,
          value = plainNumber(value);

      if (value.length > 3) {
          value = value.substring(0, value.length - 3) + '.' + value.substring(value.length - 3, value.length);
      }
      console.log(value);
      input.value = value;
  }

</script>
