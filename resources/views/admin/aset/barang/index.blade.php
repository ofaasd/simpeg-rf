@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

@endsection
@section('page-script')

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
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="aset-barang-tab" data-bs-toggle="tab" data-bs-target="#aset-barang" type="button" role="tab" aria-controls="aset-barang" aria-selected="true">NON ELEKTRONIK</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="elektronik-tab" data-bs-toggle="tab" data-bs-target="#elektronik" type="button" role="tab" aria-controls="elektronik" aria-selected="false">Elektronik</button>
      </li>
      {{-- <li class="nav-item" role="presentation">
        <button class="nav-link" id="non-elektronik-tab" data-bs-toggle="tab" data-bs-target="#non-elektronik" type="button" role="tab" aria-controls="non-elektronik" aria-selected="false">Non Elektronik</button>
      </li> --}}
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="aset-barang" role="tabpanel" aria-labelledby="aset-barang-tab">
        <div class="card mb-4" id="card-block">
          <div class="card-header">
            <h4>Aset Non Elektronik</h4>
          </div>
          <div class="card-body" style="overflow-x:scroll">
            <div class="row">
              <div class="col-md-12 text-right">
                <button type="button" id="btnTambahBarang" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_barang" style="float:right">+ Tambah</button>
              </div>
            </div>
            <div id="table_barang">
              <table class="dataTable table">
                <thead>
                  <tr>
                    <td>No.</td>
                    <td>Ruang</td>
                    <td>Jenis Barang</td>
                    <td>Nama Barang</td>
                    <td>Kondisi Penerimaan</td>
                    <td>Tanggal Perolehan</td>
                    <td>Status</td>
                    <td>Catatan</td>
                    <td>Aksi</td>
                  </tr>
                </thead>
                <tbody id="table_barang">
                  @foreach($barang as $row)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{ $row->ruang }}</td>
                      <td>{{ $row->jenisBarang }}</td>
                      <td>{{ $row->nama }}</td>
                      <td>{{ ucwords(str_replace('-', ' ', $row->kondisi_penerimaan)) }}</td>
                      <td>{{ \Carbon\Carbon::parse($row->tanggal_perolehan)->locale('id')->translatedFormat('d F Y') }}</td>
                      <td>{{ ucwords($row->status) }}</td>
                      <td>{{ $row->catatan }}</td>
                      <td>
                        <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                          <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-barang waves-effect" data-status="lantai"><i class="mdi mdi-pencil me-1"></i></button>
                          <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-barang" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="elektronik" role="tabpanel" aria-labelledby="elektronik-tab">
        <div class="card mb-4" id="card-block">
          <div class="card-header">
            <h4>Aset Elektronik</h4>
          </div>
          <div class="card-body" style="overflow-x:scroll">
            <div class="row">
              <div class="col-md-12 text-right">
                <button type="button" id="btnTambahElektronik" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_elektronik" style="float:right">+ Tambah</button>
              </div>
            </div>
            <div id="table_elektronik">
              <table class="dataTable table">
                <thead>
                  <tr>
                    <td>No.</td>
                    <td>Ruang</td>
                    <td>Nama Barang</td>
                    <td>Status Barang</td>
                    <td>Aksi</td>
                  </tr>
                </thead>
                <tbody id="table_elektronik">
                  @foreach($asetElektronik as $row)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{ $row->ruang }}</td>
                      <td>{{ $row->nama }}</td>
                      <td>{{ ucwords($row->status) }}</td>
                      <td>
                        <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                          <button type="button" id="btnView" data-id="{{$row->id}}" class="btn btn-success view-elektronik waves-effect" data-status="view_elektronik"><i class="mdi mdi-eye me-1"></i></button>
                          <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit-elektronik waves-effect" data-status="elektronik"><i class="mdi mdi-pencil me-1"></i></button>
                          <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-elektronik" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- <div class="tab-pane fade" id="non-elektronik" role="tabpanel" aria-labelledby="non-elektronik-tab">
        Non Elektronik
      </div> --}}
    </div>
  </div>
</div>

{{-- Modal Barang --}}
<form id="formBarang">
  <div class="modal fade" id="modal_barang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Barang</h3>
            <p class="pt-1">Tambah barang baru</p>
          </div>
          <div class="row g-4">
            <input type="hidden" name="id" id="id_barang">

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select name="ruang" class="form-control" id="id_ruang">
                  @foreach($ruang as $row)
                    <option value="{{$row->kode}}">{{$row->nama}}</option>
                  @endforeach
                </select>
                <label for="id_ruang">Lokasi Barang</label> <!-- Mengubah for agar sesuai dengan id input -->
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select name="jenisBarang" class="form-control" id="id_jenis_barang">
                  @foreach($refJenisBarang as $row)
                    <option value="{{$row->kode}}">{{$row->nama}}</option>
                  @endforeach
                </select>
                <label for="id_jenis_barang">Jenis Barang</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="nama" name="nama" class="form-control" placeholder="cth: Setrika">
                <label for="nama">Nama Barang</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select name="kondisiPenerimaan" class="form-control" id="kondisi_penerimaan">
                  <option value="sangat-bagus">Sangat Bagus</option>
                  <option value="bagus">Bagus</option>
                  <option value="kurang-bagus">Kurang Bagus</option>
                  <option value="buruk">Buruk</option>
                </select>
                <label for="kondisi_penerimaan">Kondisi Penerimaan</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="date" id="tanggal_perolehan" name="tglPerolehan" class="form-control">
                <label for="tanggal_perolehan">Tanggal Perolehan</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="catatan" name="catatan" class="form-control">
                <label for="catatan">Catatan</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select name="status" class="form-control" id="status">
                  <option value="normal">Normal</option>
                  <option value="rusak">Rusak</option>
                  <option value="hilang">Hilang</option>
                </select>
                <label for="status">Status Barang</label>
              </div>
            </div>

            <div class="col-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1" id="btn-submit">Submit</button>
              <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</form>
{{-- /Modal Barang --}}

{{-- Modal Elektronik --}}
<form id="formElektronik">
  <div class="modal fade" id="modal_elektronik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body py-3 py-md-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Elektronik</h3>
            <p class="pt-1">Tambah elektronik baru</p>
          </div>
          <div class="row g-4">
            <input type="hidden" name="id" id="id_elektronik">

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select name="ruang" class="form-control" id="id_ruang_elektronik">
                  @foreach($ruang as $row)
                    <option value="{{$row->id}}">{{$row->nama}}</option>
                  @endforeach
                </select>
                <label for="id_ruang_elektronik">Lokasi Barang</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="nama_elektronik" name="nama" class="form-control" placeholder="cth: Setrika">
                <label for="nama_elektronik">Nama Barang</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select name="kondisiPenerimaan" class="form-control" id="kondisi_penerimaan_elektronik">
                  <option value="sangat-bagus">Sangat Bagus</option>
                  <option value="bagus">Bagus</option>
                  <option value="kurang-bagus">Kurang Bagus</option>
                  <option value="buruk">Buruk</option>
                </select>
                <label for="kondisi_penerimaan_elektronik">Kondisi Penerimaan</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="date" id="tanggal_perolehan_elektronik" name="tglPerolehan" class="form-control">
                <label for="tanggal_perolehan_elektronik">Tanggal Perolehan</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="mb-3">
                <label for="garansi_elektronik" class="form-label">Garansi</label>
                <div class="input-group">
                  <input type="text" id="garansi_elektronik" name="garansi" class="form-control" placeholder="cth: 3 / 3.2">
                  <span class="input-group-text">Tahun</span>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="spesifikasi_elektronik" name="spesifikasi" class="form-control" placeholder="cth: 20watt, 2300mAh">
                <label for="spesifikasi_elektronik">Spesifikasi</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="serial_number_elektronik" name="serialNumber" class="form-control">
                <label for="serial_number_elektronik">Serial Number</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="date" id="last_checking_elektronik" name="lastChecking" class="form-control">
                <label for="last_checking_elektronik">Terakhir Pemeriksaan</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="catatan_elektronik" name="catatan" class="form-control">
                <label for="catatan">Catatan</label>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select name="status" class="form-control" id="status_elektronik">
                  <option value="normal">Normal</option>
                  <option value="rusak">Rusak</option>
                  <option value="hilang">Hilang</option>
                </select>
                <label for="status_elektronik">Status Barang</label>
              </div>
            </div>

            <div class="col-12 col-md-12 text-center">
              <div class="form-floating form-floating-outline">
                <button type="submit" class="btn btn-primary me-sm-3 me-1" id="btn-submit-elektronik">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="modal fade" id="modal_view_elektronik" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Detail Elektronik</h3>
        </div>
        <div class="row g-4">

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
              <p>Ruang : <span id='view-elektronik-ruang'></span></p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
              <p>Nama : <span id='view-elektronik-nama'></span></p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
            <p>Kondisi Penerimaan : <span id='view-elektronik-kondisi_penerimaan' class="text-capitalize"></span></p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
            <p>Tanggal Perolehan : <span id='view-elektronik-tanggal_perolehan'></span></p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
            <p> Garansi : <span id='view-elektronik-garansi'></span> Tahun</p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
            <p>Spesifikasi : <span id='view-elektronik-spesifikasi'></span></p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
              <p>Serial Number : <span id='view-elektronik-serial_number'></span></p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
            <p>Pengecekan Terakhir : <span id='view-elektronik-last_checking'></span></p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
            <p>Catatan : <span id='view-elektronik-catatan'></span></p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="form-floating form-floating-outline">
            <p>Status : <span id='view-elektronik-status' class="text-uppercase"></span></p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
</div>
{{-- /Modal Elektronik --}}

@endsection
<script>
function zeroPadded(val) {
  if (val >= 10)
    return val;
  else
    return '0' + val;
}

function toElektronik()
{
  $('.tab-slider').attr('style', 'left: 177.867px; width: 139.667px; bottom: 0px;');
  $('#aset-barang').removeClass('show active');
  $('#aset-barang-tab').removeClass('active').attr('aria-selected', 'false');

  $('#elektronik').addClass('show active');  // 'show' + 'active' untuk konten tab
  $('#elektronik-tab')
      .addClass('active')
      .attr('aria-selected', 'true')
      .attr('style', 'border-color: var(--bs-nav-tabs-link-active-border-color);');
  let tab = new bootstrap.Tab(document.querySelector('#elektronik-tab'));
  tab.show();
  console.log('tab hidup')
}

document.addEventListener("DOMContentLoaded", function(event) {
  $('.dataTable').dataTable();
  const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab === 'elektronik') {
        toElektronik();
    }

  $('#formBarang').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit').prop('disabled', true);

      insert_update(formData).done(function() {
          $('#btn-submit').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit').prop('disabled', false);
      });
  });

  $('#formElektronik').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      
      $('#btn-submit-elektronik').prop('disabled', true);

      insert_update_elektronik(formData).done(function() {
          $('#btn-submit-elektronik').prop('disabled', false);
      }).fail(function() {
          $('#btn-submit-elektronik').prop('disabled', false);
      });
  });

  $('#btnTambahBarang').on('click', function(){
    $('#id_barang').val('');
    $('#nama').val('');
    $('#tanggal_perolehan').val('');
    $('#catatan').val('');
    $('#status').val('');
  })

  $('#btnTambahElektronik').on('click', function(){
    $('#id_elektronik').val('');
    $('#nama_elektronik').val('');
    $('#tanggal_perolehan_elektronik').val('');
    $('#garansi_elektronik').val('');
    $('#spesifikasi_elektronik').val('');
    $('#serial_number_elektronik').val('');
    $('#last_checking_elektronik').val('');
    $('#catatan_elektronik').val('');
    $('#status_elektronik').val('');
  })

  $(document).on('click', '.edit-barang', function () {
    const id = $(this).data('id');
    $('#nama').val('');
    $('#tanggal_perolehan').val('');
    $('#catatan').val('');
    $('#status').val('');
    // get data
    $('.loader-container').show();
    $.get(''.concat(baseUrl).concat('aset/barang/').concat(id, '/edit'), function (data) {
    Object.keys(data).forEach(key => {
        if(data[key] == null){
          data[key] = ''
        }
        if(key == 'tanggal_perolehan'){
          var dateOnly = data[key].split(' ')[0];
          $('#' + key)
            .val(dateOnly)
            .trigger('change');
        }else if(key == 'id'){
          $('#' + key + '_barang')
            .val(data[key])
            .trigger('change');
        }else{
          $('#' + key)
            .val(data[key])
            .trigger('change');
        }
    });
    $('#modal_barang').modal('show');
    $('.loader-container').hide();
    });
  });

  $(document).on('click', '.view-elektronik', function () {
      const id = $(this).data('id');
      // Get data
      $('.loader-container').show();
      $.get(''.concat(baseUrl).concat('aset/elektronik/').concat(id))
          .done(function (data) {
              Object.keys(data).forEach(key => {
                if(data[key] == null){
                  data[key] = ''
                }
                $('#view-elektronik-' + key).text(data[key]);
              });
              $('#modal_view_elektronik').modal('show');
              $('.loader-container').hide();
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
              $('.loader-container').hide();
              console.error('Error fetching data:', textStatus, errorThrown);
          });
  });

  $(document).on('click', '.edit-elektronik', function () {
    const id = $(this).data('id');
    $('#nama_elektronik').val('');
    $('#tanggal_perolehan_elektronik').val('');
    $('#garansi_elektronik').val('');
    $('#spesifikasi_elektronik').val('');
    $('#serial_number_elektronik').val('');
    $('#last_checking_elektronik').val('');
    $('#catatan_elektronik').val('');
    $('#status_elektronik').val('');
    // get data
    $('.loader-container').show();
    $.get(''.concat(baseUrl).concat('aset/elektronik/').concat(id, '/edit'), function (data) {
    Object.keys(data).forEach(key => {
      if(data[key] == null){
        data[key] = ''
      }
        if(key == 'tanggal_perolehan'){
          var dateOnly = data[key].split(' ')[0];
          $('#' + key + '_elektronik')
            .val(dateOnly)
            .trigger('change');
        }else if(key == 'last_checking'){
          var dateOnly = data[key].split(' ')[0];
          $('#' + key + '_elektronik')
            .val(dateOnly)
            .trigger('change');
        }else{
          $('#' + key + '_elektronik')
            .val(data[key])
            .trigger('change');
        }
    });
    $('#modal_elektronik').modal('show');
    $('.loader-container').hide();
    });
  });

  $(document).on('click', '.delete-barang', function () {
      const id = $(this).data('id');
      // SweetAlert for confirmation of delete
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
          if (result.isConfirmed) {
              // Delete the data
              $('.loader-container').show();
              $.ajax({
                  type: 'DELETE',
                  url: ''.concat(baseUrl, 'aset/barang/', id),
                  success: function () {
                      // Success SweetAlert after successful deletion
                      $('.loader-container').hide();
                      Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'The Record has been deleted!',
                          customClass: {
                              confirmButton: 'btn btn-success'
                          }
                      });
                      location.href = "barang";
                  },
                  error: function (_error) {
                      console.log(_error);
                      $('.loader-container').hide();
                      // Error SweetAlert in case of failure
                      Swal.fire({
                          title: 'Error!',
                          text: 'There was an error deleting the record.',
                          icon: 'error',
                          customClass: {
                              confirmButton: 'btn btn-danger'
                          }
                      });
                      location.href = "barang";
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

  $(document).on('click', '.delete-elektronik', function () {
      const id = $(this).data('id');
      // SweetAlert for confirmation of delete
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
          if (result.isConfirmed) {
            $('.loader-container').show();
              // Delete the data
              $.ajax({
                  type: 'DELETE',
                  url: ''.concat(baseUrl, 'aset/elektronik/', id),
                  success: function () {
                    $('.loader-container').hide();
                      // Success SweetAlert after successful deletion
                      Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'The Record has been deleted!',
                          customClass: {
                              confirmButton: 'btn btn-success'
                          }
                      });
                      location.href = "?tab=elektronik";
                  },
                  error: function (_error) {
                      console.log(_error);
                      $('.loader-container').hide();
                      // Error SweetAlert in case of failure
                      Swal.fire({
                          title: 'Error!',
                          text: 'There was an error deleting the record.',
                          icon: 'error',
                          customClass: {
                              confirmButton: 'btn btn-danger'
                          }
                      });
                      location.href = "?tab=elektronik";
                  }
              });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            $('.loader-container').hide();
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
});

function insert_update(formData){
  $('.loader-container').show();
  $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('aset/barang'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        $('.loader-container').hide();
        //hilangkan modal
        $('#modal_barang').modal('hide');
        //reset form
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Data ', ' ').concat(' Berhasil Ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });

        location.href = "barang";
      },
      error: function error(err) {
        //showUnblock();
        $('#modal_barang').modal('hide');
        $('.loader-container').hide();
        console.log(err.responseText);
        Swal.fire({
          title: 'Cant Save Data !',
          text:  'Data Not Saved !',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
        location.href = "barang";
      }
    });
}

function insert_update_elektronik(formData)
{
  $('.loader-container').show();
  $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('aset/elektronik'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        $('.loader-container').hide();
        //hilangkan modal
        $('#modal_elektronik').modal('hide');
        //reset form
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Data ', ' ').concat(' Berhasil Ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });

        location.href = "?tab=elektronik";
      },
      error: function error(err) {
        //showUnblock();
        $('#modal_elektronik').modal('hide');
        $('.loader-container').hide();
        console.log(err.responseText);
        Swal.fire({
          title: 'Cant Save Data !',
          text:  'Data Not Saved !',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
        location.href = "?tab=elektronik";
      }
    });
}
</script>
