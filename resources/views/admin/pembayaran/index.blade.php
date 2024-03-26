
@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
  <script>
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
@endsection


@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Pembayaran</h5>
  </div>
  <div class="card" style="overflow-x:scroll">
    <div class="col-md-12" style="padding:20px;">
      <a href="#" id="filter_btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filter">Filter</a>
      <a href="#" id="export_btn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#filter">Export</a>
      <a href="#" id="import_btn" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#import">Import</a>
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
                  <input type="number" name='tahun' id='tahun' class='form-control col-md-2' value='{{ date('Y') }}'>
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
            <form method="GET" action='{{URL::to('/pembayaran')}}'>
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Filter</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <input type="file" id="input_file" name="upload" class="form-control">
                  <label for="upload">File Upload</label>
                </div>
                <div class="form-floating form-floating-outline mb-4 col-md-12">
                  <textarea name="hasil" id="xlx_json" class="form-control"></textarea>
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

          <h2 style='text-align:center; margin-top:40px;'>Syahriyah {{ $data['bulan'][$data['periode']] }} {{ $data['tahun'] }}</h2>
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
                      </tr>
                  </thead>
                  <tbody>
                      @php
                      $i = 1;
                      @endphp
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
                                    <button class='btn btn-secondary btn-xs'>Belum Valid</button>
                                  @elseif($s->validasi == 1)
                                    <button class='btn btn-primary btn-xs'>Valid</button>
                                  @else
                                    <button class='btn btn-danger btn-xs'>Tidak Valid</button>
                                  @endif
                              </td>
                              <td>Rp.  {{number_format($s->jumlah, 0, ',', '.')}}</td>
                          </tr>
                        @php $i++; @endphp
                      @endforeach
                  </tbody>
              </table>
      @endif
    </div>
  </div>
</div>
@endsection
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    $("#filter_btn").click(function(){
      $("#footer-filter").html(`<button type="submit" class="btn btn-primary waves-effect waves-light">Filter</button>`);
    });
    $("#export_btn").click(function(){
      $("#footer-filter").html(`<a href="javascript:void(0)" class="btn btn-success waves-effect waves-light" id="export" onclick="eskpor()">Export</a>`);
    });
    const title = 'Syahriyah';
    $("#table-laporan").DataTable();
    document.getElementById('input_file').addEventListener('change', handleFileSelect, false);
  });
  const eskpor = () => {
    const periode = $("#periode").val();
    const tahun = $("#tahun").val();
    const kelas = $("#kelas").val();
    return location.href = `{{URL::to('/pembayaran/export')}}?periode=${periode}&tahun=${tahun}&kelas=${kelas}`;
  };
</script>
