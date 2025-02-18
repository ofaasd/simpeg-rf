
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
        <h5 class="card-title mb-0">Pembayaran</h5>
      </div>
      <div class="col-md-6 text-right">
        <div class="dropdown">
          <a href="#" id="filter_btn" class="btn btn-primary dropdown-toggle" style="float:right" data-bs-toggle="dropdown" aria-expanded="false">Actions</a>
          <ul class="dropdown-menu">
            <li><a href="{{URL::to('pembayaran/create')}}" id="add" class="dropdown-item"><i class="mdi mdi-plus"></i>  Create</a></li>
            <li><a href="#" id="filter_btn" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#filter"><i class="mdi mdi-sort"></i> Filter</a></li>
            <li><a href="#" id="export_btn" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#filter"><i class="mdi mdi-export"></i> Export</a></li>
            <li><a href="#" id="import_btn" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#import"><i class="mdi mdi-export"></i> Import</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="card" style="overflow-x:scroll">
    <div class="col-md-12" style="padding:20px;">

      <!--<a href="#" id="import_btn" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#import">Import</a>-->
      <div class="modal fade" id="filter" tabindex="-1" aria-labelledby="filterLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="GET" action='{{URL::to('/pembayaran')}}'>
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Filter</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="form-floating form-floating-outline mb-4 col-md-6">
                  <select name="periode" id="periode" class='form-control'>
                    <option value="0">Semua</option>
                    @foreach($data['bulan'] as $key=>$value)
                    <option value={{$key}} {{($data['periode'] == $key)?'selected':''}}>{{$value}}</option>
                    @endforeach
                  </select>
                  <label for="periode">Periode</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-6">
                  <input type="number" name='tahun' id='tahun' class='form-control col-md-2' value='{{ $data['tahun'] }}'>
                  <label for="tahun">Tahun</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-6">
                  <select name="kelas" id="kelas" class='form-control'>
                    @foreach($kelas as $row)
                      <option value="{{$row->kelas}}" {{($data['kelas'] == $row->kelas)?'selected':''}}>{{$row->kelas}}</option>
                    @endforeach
                  </select>
                  <label for="kelas">Kelas</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-6">
                  <select name="status" id="status" class='form-control col-md-4'>
                    @foreach($data['status'] as $key=>$value)
                    <option value="{{$key}}" {{(!empty($data['status']) && $data['status']==$key)?"selected":""}}>{{$value}}</option>
                    @endforeach
                  </select>
                  <label for="periode">Status</label>
                </div>
              </div>
              <div class="modal-footer" >
                <div class="form-floating form-floating-outline col-md-12" id="footer-filter">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" id="import" tabindex="-1" aria-labelledby="importLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST" action='{{URL::to('/pembayaran/review')}}'>
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Filter</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="form-floating form-floating-outline mb-4 col-md-6">
                  <select name="kelas" id="kelas" class='form-control'>
                    @foreach($kelas as $row)
                      <option value="{{$row->kelas}}" {{($data['kelas'] == $row->kelas)?'selected':''}}>{{$row->kelas}}</option>
                    @endforeach
                  </select>
                  <label for="kelas">Kelas</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <input type="file" id="input_file" name="upload" class="form-control">
                  <label for="upload">File Upload</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <textarea name="hasil" id="xlx_json" class="form-control" readonly></textarea>
                  <label for="hasil">Hasil</label>
                </div>

              </div>
              <div class="modal-footer" >
                <div class="form-floating form-floating-outline col-md-12" id="footer-filter">
                  <button type="submit" class="btn btn-primary waves-effect waves-light">Import</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <br />
      @if(!empty($data))

          <h2 style='text-align:center; margin-top:40px;'>Laporan Pembayaran {{ $data['bulan'][$data['periode']] }} {{ $data['tahun'] }}</h2>
              <table class="table" id="table-laporan">
                  <thead>
                      <tr>
                          <td>No.</td>
                          <td>No. Induk</td>
                          <td>Nama Santri</td>
                          <td>Kode Kelas</td>
                          <td>Kode Murroby</td>
                          <td>Tanggal Bayar</td>
                          <td>Status</td>
                          <td>Total</td>
                          <td></td>
                      </tr>
                  </thead>
                  <tbody>
                      @php
                      $i = 1;

                      @endphp
                      @if($status >= 0 && $status < 4)
                        @foreach ($pembayaran as $s)
                          @php
                            $total = 0;
                          @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$s->no_induk}}</td>
                                <td>{{$s->nama}}</td>
                                <td>{{$s->kelas}}</td>
                                <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>

                                <td>{{$s->tanggal_bayar}}</td>
                                <td>@if($s->validasi == 0)
                                      <button class='btn btn-secondary btn-xs btn-status' data-bs-toggle="modal" data-bs-target="#status" data-id="{{$s->id}}" style="padding-top:10px;padding-bottom:10px;">Belum Valid</button>
                                    @elseif($s->validasi == 1)
                                      <button class='btn btn-primary btn-xs btn-status' data-bs-toggle="modal" data-bs-target="#status" data-id="{{$s->id}}" style="padding-top:10px;padding-bottom:10px;">Valid</button>
                                    @else
                                      <button class='btn btn-danger btn-xs btn-status' data-bs-toggle="modal" data-bs-target="#status" data-id="{{$s->id}}" style="padding-top:10px;padding-bottom:10px;">Tidak Valid</button>
                                    @endif
                                </td>
                                <td>Rp.  {{number_format($s->jumlah, 0, ',', '.')}}</td>
                                <td>
                                  <div class="btn-group" role="group" aria-label="First group">
                                    <a href="{{URL::to('pembayaran/' . $s->id . '/edit')}}" class="btn btn-outline-primary waves-effect"><i class="tf-icons mdi mdi-pencil-outline"></i></a>
                                    <a href="#" class="btn btn-outline-danger waves-effect delete-record" data-id="{{$s->id}}"><i class="tf-icons mdi mdi-delete-outline"></i></a>
                                  </div>
                                </td>
                            </tr>
                          @php $i++; @endphp
                        @endforeach
                      @endif
                      @if($status == 0 || $status == 4)
                        @foreach($data['sisa_santri'] as $s)
                          @php
                          $total = 0;
                          @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$s->no_induk}}</td>
                                <td>{{$s->nama}}</td>
                                <td>{{$s->kelas}}</td>
                                <td>{{$s->kamar_id}} ({{ ($s->kamar_id==0)?"":$data['nama_murroby'][$s->kamar_id]}})</td>

                                <td>-</td>
                                <td>
                                  <button class='btn btn-danger btn-xs btn-status' data-bs-toggle="modal" data-bs-target="#kirim_wa" data-id="{{$s->id}}" style="padding-top:10px;padding-bottom:10px;">Belum Lapor</button>
                                </td>
                                <td>Rp.  0</td>
                                <td>
                                  <div class="btn-group" role="group" aria-label="First group">
                                    <div class="btn-group" role="group" aria-label="First group">
                                      <a href="javascript:void(0)" class="btn btn-outline-primary waves-effect btn-wa" data-bs-toggle="modal" data-bs-target="#msg" data-id="{{$s->no_induk}}" title="Kirim Pesan WA"><i class="tf-icons mdi mdi-whatsapp"></i></a>
                                    </div>
                                  </div>
                                </td>
                            </tr>
                          @php $i++; @endphp
                        @endforeach
                      @endif
                  </tbody>
              </table>
      @endif
      <div class="modal fade" id="status" tabindex="-1" aria-labelledby="importLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="block_status_modal">
          <div class="modal-content" >
            <form method="POST" action='javascript:void(0)' id="formReview">
              <input type="hidden" name="id" id="id">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Pembayaran</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <table class="table" style="font-size:11pt;">
                      <tbody>
                        <tr>
                          <td>Nama</td>
                          <td id="detail_nama">: </td>
                        </tr>
                        <tr>
                          <td>No. Induk</td>
                          <td id="detail_no_induk">: </td>
                        </tr>
                        <tr>
                          <td>kelas</td>
                          <td id="detail_kelas">: kelas</td>
                        </tr>
                        <tr>
                          <td>Murroby</td>
                          <td id="detail_murroby">: kelas</td>
                        </tr>
                        <tr>
                          <td>Tanggal Bayar</td>
                          <td id="detail_tanggal_bayar">: kelas</td>
                        </tr>


                        <tr>
                          <td>Status</td>
                          <td >
                            <select name="status" id="detail_status" class='form-control'>
                              <option value="0">Belum Valid</option>
                              <option value="1">Valid</option>
                              <option value="2">Tidak Valid</option>
                            </select>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-6">
                    <p>Detail Bayar</p>
                    <div id="detail_bayar">
                      <table class="table" >
                        <tbody id="tbl_detail_bayar"></tbody>
                        <tfooter>
                          <tr>
                            <td style="font-weight:bold">Total</td>
                            <td id="detail_total" style="font-weight:bold">: Total</td>
                           </tr>
                          </tfooter>
                        </table>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">

                  </div>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-6">

                </div>
              </div>
              <div class="modal-footer" >
                <div class="form-floating form-floating-outline col-md-12" id="footer-filter">
                  <button type="submit" class="btn btn-primary waves-effect waves-light" id="btnSave">Update Status</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" id="msg" tabindex="-1" aria-labelledby="importLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="block_status_modal">
          <div class="modal-content" >
            <form method="POST" action='javascript:void(0)' id="formSendWarning">
              <input type="hidden" name="id_santri" id="id_santri_warning">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Kirim Pesan WA</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div id="alert-wa"></div>
                <div class="form-floating form-floating-outline col-md-12 mb-4">
                  <input type="text" name="no_wa" class="form-control" id="no_hp" placeholder="No. HP">
                  <label for="no_hp">No. HP</label>
                </div>
                <div class="form-floating form-floating-outline col-md-12 mb-4">
                  <textarea name="pesan" class="form-control" id="pesan" style="height:100px;"></textarea>
                  <label for="pesan">Pesan Teks</label>
                </div>
              </div>
              <div class="modal-footer" >
                <div class="form-floating form-floating-outline col-md-12" id="footer-filter">
                  <button type="submit" class="btn btn-primary waves-effect waves-light" id="btnSaveWarning">Kirim WA</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
<!-- Convert Excel to JSON -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    document.getElementById('input_file').addEventListener('change', handleFileSelect, false);
    $("#filter_btn").click(function(){
      $("#exampleModalLabel").html('Filter');
      $("#footer-filter").html(`<button type="submit" class="btn btn-primary waves-effect waves-light">Filter</button>`);
    });
    $("#export_btn").click(function(){
      $("#exampleModalLabel").html('Export');
      $("#footer-filter").html(`<a href="javascript:void(0)" class="btn btn-success waves-effect waves-light" id="export" onclick="eskpor()">Export</a>`);
    });
    const title = 'Syahriyah';
    const dt = $("#table-laporan").DataTable();
    $("#table-laporan").on("click", ".btn-status", function(){
      $('#block_status_modal').block({
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
      const id = $(this).data('id');
      $.ajax({
        method:"POST",
        data : {id : id},
        dataType: 'json',
        url:'{{URL::to('/pembayaran/detail_bayar')}}',
        success : function(data){
          const jumlah = new Intl.NumberFormat(["ban", "id"]).format(
            data[0].jumlah,
          );
          $("#detail_nama").html(": "+data[0].nama);
          $("#detail_no_induk").html(": "+data[0].no_induk);
          $("#detail_kelas").html(": "+data[0].kelas);
          $("#detail_murroby").html(": "+data[0].nama_murroby+' (' + data[0].kamar_id +')');
          $("#detail_tanggal_bayar").html(": "+data[0].tanggal_bayar);
          $("#detail_status").val(data[0].validasi);
          $("#detail_total").html("Rp. "+jumlah);
          $("#id").val(data[0].id);

          const keyDetail = [];
          $("#tbl_detail_bayar").html('');
          Object.keys(data[1]).forEach(item => {
            $("#tbl_detail_bayar").append(`
              <tr><td>${item}</td><td>${data[1][item]}</td></tr>
            `);
          })
          $('#block_status_modal').unblock();
        }
      });
    });
    $("#formReview").submit(function(e){
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
        const url_save = "{{URL::to('pembayaran/update_status')}}";
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
                location.reload();
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
            $('#btnSave').unblock();
          }
        });

      })
  $(document).on('click', '.delete-record', function () {
    var id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: ''.concat(baseUrl).concat('pembayaran/').concat(id),
          success: function success() {
            location.reload()
          },
          error: function error(_error) {
            console.log(_error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The Record has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The record is not deleted!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
    $(document).on('click', '.btn-wa', function () {
      const url_get_wa = "{{URL::to('pembayaran/get_pesan_warning')}}";
      $.ajax({
        method:"POST",
        url: url_get_wa,
        data : {
          'no_induk' : $(this).data('id'),
          'periode' : $("#periode :selected").text(),
          'tahun' : $("#tahun").val(),
          'bulan' : $("#periode").val(),
        },
        success : function(data){
          console.log(data[2]);
          if(data[2] > 0){
            $("#alert-wa").html('<div class="alert alert-warning">Santri sudah pernah dikirim pesan WA</div>');
          }else{
            $("#alert-wa").html('');
          }
          $("#no_hp").val(data[0].no_hp.replace(/\s/g, '') );
          $("#pesan").val(data[1]);
          $("#id_santri_warning").val(data[0].no_induk);
        }
      });
    });
    $("#formSendWarning").submit(function(e){
      e.preventDefault();
        $('#btnSaveWarning').block({
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
        const url_save = "{{URL::to('pembayaran/send_warning')}}";
        data.append('periode', $("#periode").val());
        data.append('tahun', $("#tahun").val());
        data.append('id_santri', $("#id_santri_warning").val());
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
            $("#msg").modal('hide');
            $('#btnSaveWarning').unblock();
          }
        });
    });
  });

  const eskpor = () => {
    const periode = $("#periode").val();
    const tahun = $("#tahun").val();
    const kelas = $("#kelas").val();
    return location.href = `{{URL::to('/pembayaran/export')}}?periode=${periode}&tahun=${tahun}&kelas=${kelas}`;
  };

  var ExcelToJSON = function() {

    this.parseExcel = function(file) {
      var reader = new FileReader();

      reader.onload = function(e) {
        var data = e.target.result;
        var workbook = XLSX.read(data, {
          type: 'binary'
        });
        workbook.SheetNames.forEach(function(sheetName) {
          // Here is your object
          var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
          var json_object = JSON.stringify(XL_row_object);
          console.log(JSON.parse(json_object));
          jQuery('#xlx_json').val(json_object);
        })
      };

      reader.onerror = function(ex) {
        console.log(ex);
      };

      reader.readAsBinaryString(file);
    };
    };

    function handleFileSelect(evt) {

    var files = evt.target.files; // FileList object
    var xl2json = new ExcelToJSON();
    xl2json.parseExcel(files[0]);
    }
</script>
