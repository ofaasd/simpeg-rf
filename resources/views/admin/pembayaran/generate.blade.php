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
        <h5 class="card-title mb-0"> Generate Pembayaran Bulan {{$list_bulan[(int)date('m')]}}</h5>
      </div>
      <div class="col-md-6">
        <div class="row g-3 justify-content-end">
          <div class="col-auto">
            <select id="bulan_now" class="form-control">
              <option value="0">Ubah Bulan</option>
              @foreach($list_bulan as $key=>$value)
              <option value="{{$key}}" {{$key == date('m') ? "selected" : ""}}>{{$value}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-auto">
            <select name="tahun" id="tahun_now" class="form-control">
              @for($i=date('Y'); $i >= (date('Y')-5); $i--)
                <option value="{{$i}}">{{$i}}</option>
              @endfor
            </select>
          </div>
        </div>
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
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal">+ Generate Pembayaran</a>
        <a href="#" class="btn btn-success" id="publish_pembayaran">Publish Pembayaran</a>

        <table class="datatable table table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nama</th>
              <th>No. Induk</th>
              <th>Kelas</th>
              <th>Total Pembayaran</th>
              <th>Publish</th>
              <th>Status Pembayaran</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
            @foreach($santri as $row)
              <tr>
                <td>{{$no}}</td>
                <td>{{$row->nama}}</td>
                <td>{{$row->no_induk}}</td>
                <td>{{strtoupper($row->kelas)}}</td>
                <td>{{(!empty($generate[$row->no_induk]->total_bayar)) ? number_format($generate[$row->no_induk]->total_bayar,0,",",".") : 0}}</td>
                <td>{!!(!empty($generate[$row->no_induk]->publish) && $generate[$row->no_induk]->publish == 1)?"<span class='btn btn-success btn-sm'>Sudah</span>":"<span class='btn btn-danger btn-sm'>Belum</span>"!!}</td>
                <td>{!!(!empty($generate[$row->no_induk]->publish) && $generate[$row->no_induk]->status == 1)?"<span class='btn btn-success btn-sm'>Sudah</span>":"<span class='btn btn-danger btn-sm'>Belum</span>"!!}</td>
                <td><a href="#" class="btn btn-primary editGenerate" data-id="{{$row->no_induk}}" data-bs-toggle="modal" data-bs-target="#editGenerateModal"><i class="fa fa-pencil"></i></a></td>
              </tr>
              @php $no++; @endphp
            @endforeach
          </tbody>
        </table>
    </div>
  </div>
</div>
<div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Generate Pembayaran</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="target_tunggakan">
        <div class="pesan"></div>
        <form method="POST" action="{{url('pembayaran/generate_tunggakan')}}">
          <input type="hidden" name="id" id="id_pembayaran">
          <table class="table">
            <tr>
              <td>Kelas</td>
              <td>
                <select name="kelas[]" class="form-control kelas_select2" multiple>
                  @foreach($kelas as $row)
                  <option value="{{$row->code}}" {{(in_array($row->code, $kelas_already))?"class='bg-danger'":"class='bg-primary'"}}>{{$row->name}}</option>
                  @endforeach
                </select>
              </td>
            </tr>
            <tr>
              <td>Bulan</td>
              <td>
                <select name="bulan" id="bulan_input" class="form-control">
                  @foreach($list_bulan as $key=>$value)
                  <option value="{{$key}}" {{($key == date('m')) ? "selected":""}}>{{$value}}</option>
                  @endforeach
                </select>
              </td>
            </tr>
            {{-- <tr>
              <td>Total Bayar (per santri)</td>
              <td>
                <input type="text" name="total_bayar" class="form-control" placeholder="0" onkeyup="splitInDots(this)">
              </td>
            </tr> --}}
            @php $total = 0; @endphp
            @foreach($jenis_pembayaran as $jen)
            @php $total += $jen->harga @endphp
              <tr>
                  <td>{{$jen->jenis}}<input type="hidden" name="id_jenis_pembayaran[]" value='{{ $jen->id }}'></td>
                  <td><input type="text" onkeyup="splitInDots2(this)" id="jenis_{{$jen->id}}" placeholder="0" name="jenis_pembayaran[]" class="form-control" value="{{(!empty($jen->harga)) ? number_format($jen->harga,0,",",".") : '0'}}"></td>
              </tr>
            @endforeach
            <tr>
              <td>Total Pembayaran</td>
              <td>
                <input type="text" class="form-control" name="total_bayar" id="total" value="{{number_format($total,0,"",".")}}" readonly>
              </td>
            </tr>
            <tr>
              <td colspan=2><button type="submit" class="btn btn-primary" value="Generate">Generate</button</td>
            </tr>
          </table>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editGenerateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Pembayaran</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="target_edit">
        <div class="pemberitahuan"></div>
        <form method="POST" action="{{url('pembayaran/generate_pembayaran_single')}}">
          <table class="table">
            <tr>
              <td>No. Induk</td>
              <td>
                <input type="text" name="no_induk" id="no_induk_edit" class="form-control" value="" readonly>
              </td>
            </tr>
            <tr>
              <td>Bulan</td>
              <td>
                <select name="bulan_input2" class="form-control" id="bulan_edit" readonly>
                  @foreach($list_bulan as $key=>$value)
                  <option value="{{$key}}" {{($key == ($pembayaran->bulan ?? date('m'))) ? "selected":""}}>{{$value}}</option>
                  @endforeach
                </select>
              </td>
            </tr>
            <tr>
              <td>Tahun</td>
              <td>
                  <input type="text" name="tahun" id="tahun_edit" class="form-control" value="{{$pembayaran->tahun ?? date('Y')}}" readonly>
              </td>
            </tr>
            {{-- <tr>
              <td>Total Bayar (per santri)</td>
              <td>
                <input type="text" name="total_bayar" class="form-control" placeholder="0" onkeyup="splitInDots(this)">
              </td>
            </tr> --}}
            @php $total = 0; @endphp
            @foreach($jenis_pembayaran as $jen)
              <tr>
                  <td>{{$jen->jenis}}<input type="hidden" name="id_jenis_pembayaran[]" value='{{ $jen->id }}'></td>
                  <td><input type="text" onkeyup="splitInDots3(this)" id="jenis2_{{$jen->id}}" placeholder="0" value="0" name="jenis_pembayaran[]" class="form-control" ></td>
              </tr>
            @endforeach
            <tr>
              <td>Total Pembayaran</td>
              <td>
                <input type="text" class="form-control" name="total_bayar" id="total_edit" readonly>
              </td>
            </tr>
            <tr>
              <td colspan=2><button type="submit" class="btn btn-primary" value="Generate">Simpan</button</td>
            </tr>
          </table>

        </form>
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
      $("#generateModal").on("hidden.bs.modal", function () {
          $(".pesan").html("");
      });
      const url_publish = "{{URL::to('pembayaran/publish')}}";
      $("#publish_pembayaran").click(() => {
        const r = confirm("Data Pembayaran akan terpublish. apakah anda yakin ? ")
        const bulan = $("#bulan_now").val()
        const tahun = $("#tahun_now").val()
        const data3 = {
          bulan : bulan,
          tahun : tahun,
        }
        if(r){
          $('.loader-container').show();
          $.ajax({
            method:"POST",
            url: url_publish,
            data : data3,
            success : function(data){
              Swal.fire({
                  title: 'Success!',
                  text: 'Data Berhasil Disimpan',
                  icon: 'success',
                  timer : 3000,
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
              })
              //$("#target_edit").html(data)
              $('.loader-container').hide();
              location.reload()
            },
            error: function (request, status, error) {
              Swal.fire({
                  title: 'Gagal!',
                  text: 'Data Gagal Disimpan',
                  icon: 'error',
                  timer : 2000,
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
              })
              $('.loader-container').hide();
            }
          });

        }else{
          return false;
        }
      })
      $(".datatable").dataTable()
      $('#nama_santri').select2({
          minimumInputLength: 3,
      });
      $('.kelas_select2').select2({
          dropdownParent: $('.kelas_select2').parent()
      });
      const url_get = "{{URL::to('pembayaran/get_generate')}}";
      $(".editGenerate").click(function(){
        const no_induk = $(this).data('id')
        const bulan = $("#bulan_now").val()
        const tahun = $("#tahun_now").val()
        $("#no_induk_edit").val(no_induk)
        const data2 = {
          no_induk : no_induk,
          bulan : bulan,
          tahun : tahun,
        }
        $.ajax({
          method:"POST",
          url: url_get,
          data : data2,
          type:"json",
          success : function(data){
            if(data[0].length != 0){
              $("#bulan_edit").val(data[0].bulan)
              $("#tahun_edit").val(data[0].tahun)
              $("#no_induk_edit").val(data[0].no_induk)
              $("#total_edit").val(data[0].total_bayar)
              data[1].forEach(element => {
                $("#jenis2_" + element.id_jenis).val(element.jumlah)
              });
            }else{
              $(".pemberitahuan").html("<div class='alert alert-warning'>Data tidak ditemukan silahkan</div>")
            }
            console.log(data)
            //$("#target_edit").html(data)
          }
        });
      })
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

  function plainNumber2(number) {
      return number.split('.').join('');
  }

  function splitInDots2(input) {

      var value = input.value,
          plain = plainNumber(value),
          reversed = reverseNumber(plain),
          reversedWithDots = reversed.match(/.{1,3}/g).join('.'),
          normal = reverseNumber(reversedWithDots);

      input.value = normal;

      let jumlah = 0;
					jumlah += parseInt(plainNumber2($("#jenis_17").val())) || 0;
					jumlah += parseInt(plainNumber2($("#jenis_18").val())) || 0;
					jumlah += parseInt(plainNumber2($("#jenis_1").val())) || 0;
					jumlah += parseInt(plainNumber2($("#jenis_16").val())) || 0;
					jumlah += parseInt(plainNumber2($("#jenis_3").val())) || 0;
					jumlah = jumlah + parseInt(plainNumber2($("#jenis_4").val())) || 0;
					jumlah = jumlah + parseInt(plainNumber2($("#jenis_5").val())) || 0;
					jumlah = jumlah + parseInt(plainNumber2($("#jenis_6").val())) || 0;
					jumlah = jumlah + parseInt(plainNumber2($("#jenis_15").val())) || 0;
					jumlah = jumlah + parseInt(plainNumber2($("#jenis_12").val())) || 0;
					jumlah = jumlah + parseInt(plainNumber2($("#jenis_12").val())) || 0;
          plainJumlah = plainNumber2(jumlah.toString());
          reversedJumlah = reverseNumber(plainJumlah),
          reversedWithDotsJumlah = reversedJumlah.match(/.{1,3}/g).join('.'),
          normalJumlah = reverseNumber(reversedWithDotsJumlah);

          $("#total").val(normalJumlah);


  }
  function splitInDots3(input) {

    var value = input.value,
        plain = plainNumber(value),
        reversed = reverseNumber(plain),
        reversedWithDots = reversed.match(/.{1,3}/g).join('.'),
        normal = reverseNumber(reversedWithDots);

    input.value = normal;

      let jumlah2 = 0;
        jumlah2 += parseInt(plainNumber2($("#jenis2_17").val())) || 0;
        jumlah2 += parseInt(plainNumber2($("#jenis2_18").val())) || 0;
        jumlah2 += parseInt(plainNumber2($("#jenis2_1").val())) || 0;
        jumlah2 += parseInt(plainNumber2($("#jenis2_16").val())) || 0;
        jumlah2 += parseInt(plainNumber2($("#jenis2_3").val())) || 0;
        jumlah2 = jumlah2 + parseInt(plainNumber2($("#jenis2_4").val())) || 0;
        jumlah2 = jumlah2 + parseInt(plainNumber2($("#jenis2_5").val())) || 0;
        jumlah2 = jumlah2 + parseInt(plainNumber2($("#jenis2_6").val())) || 0;
        jumlah2 = jumlah2 + parseInt(plainNumber2($("#jenis2_15").val())) || 0;
        jumlah2 = jumlah2 + parseInt(plainNumber2($("#jenis2_12").val())) || 0;
        jumlah2 = jumlah2 + parseInt(plainNumber2($("#jenis2_12").val())) || 0;
        plainJumlah2 = plainNumber2(jumlah2.toString());
        reversedJumlah2 = reverseNumber(plainJumlah2),
        reversedWithDotsJumlah2 = reversedJumlah2.match(/.{1,3}/g).join('.'),
        normalJumlah2 = reverseNumber(reversedWithDotsJumlah2);
        //console.log(normalJumlah2)
        $("#total_edit").val(normalJumlah2);
    }

  function oneDot(input) {
      var value = input.value,
          value = plainNumber(value);

      if (value.length > 3) {
          value = value.substring(0, value.length - 3) + '.' + value.substring(value.length - 3, value.length);
      }
      //console.log(value);
      input.value = value;
  }

</script>
