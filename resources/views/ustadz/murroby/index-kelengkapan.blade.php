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
        <div class="row mb-3">
          <div class="col-md-12">
            <button type="button" id="btn-tambah-kelengkapan" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah-kelengkapan"> Tambah Kelengkapan</button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="dataTable table">
            <thead>
              <tr>
                <td></td>
                <td>NIS</td>
                <td>Nama</td>
                <td>Tanggal</td>
                <td>Perlengkapan Mandi</td>
                <td>Peralatan Sekolah</td>
                <td>Perlengkapan Diri</td>
                <td></td>
              </tr>
            </thead>
            <tbody id="table-kelengkapan">
              @php
                $i = 1;
              @endphp
              @foreach($var['listSantri'] as $row)
                <tr>
                  <td>{{$i}}</td>
                  <td>{{$row->no_induk}}</td>
                  <td>{{$row->nama}}</td>
                  <td>
                    {{ isset($var['kelengkapan'][$row->no_induk]->tanggal) 
                        ? \Carbon\Carbon::parse($var['kelengkapan'][$row->no_induk]->tanggal)->format('Y-m-d') 
                        : '-' 
                    }}
                  </td>

                  <td>{{ $var['kelengkapan'][$row->no_induk]->perlengkapan_mandi ?? '-' }}</td>
                  <td>{{ $var['kelengkapan'][$row->no_induk]->peralatan_sekolah ?? '-' }}</td>
                  <td>{{ $var['kelengkapan'][$row->no_induk]->perlengkapan_diri ?? '-' }}</td>
                  <td class='text-center'><a href="{{url('murroby/kelengkapan/detail/' . $row->no_induk)}}"><span class="mdi mdi-information"></span></a></td>
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
</div>
<!-- uang Saku Masuk -->
<div class="modal fade" id="tambah-kelengkapan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Tambah Kelengkapan Santri</h3>
        </div>
        <form id="form-kelengkapan" class="row g-4" onsubmit="return false">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id='tanggal' name="tanggal" class="form-control" value="{{date('Y-m-d')}}">
              <label for="tanggal">Tanggal</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="nama" name="noInduk" class="form-control select2">
                @foreach($var['listSantri'] as $row)
                <option value='{{$row->no_induk}}'>{{ $row->no_induk }} - {{$row->nama}}</option>
                @endforeach
              </select>
              <label for="nama">Nama Santri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="perlengkapan_mandi" name="perlengkapanMandi" class="form-control select2">
                <option value='0'>Tidak Lengkap</option>
                <option value='1'>Lengkap & Kurang baik</option>
                <option value='2'>Lengkap & Baik</option>
              </select>
              <label for="perlengkapan_mandi">Perlengkapan Mandi</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='catatan_mandi' name="catatanMandi" class="form-control" placeholder="Catatan perlengkapan mandi">
              <label for="catatan_mandi">Catatan Perlengkapan Mandi</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="peralatan_sekolah" name="peralatanSekolah" class="form-control select2">
                <option value='0'>Tidak Lengkap</option>
                <option value='1'>Lengkap & Kurang baik</option>
                <option value='2'>Lengkap & Baik</option>
              </select>
              <label for="peralatan_sekolah">Peralatan Sekolah</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='catatan_sekolah' name="catatanSekolah" class="form-control" placeholder="Catatan peralatan sekolah">
              <label for="catatan_sekolah">Catatan Peralatan Sekolah</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="perlengkapan_diri" name="perlengkapanDiri" class="form-control select2">
                <option value='0'>Tidak Lengkap</option>
                <option value='1'>Lengkap & Kurang baik</option>
                <option value='2'>Lengkap & Baik</option>
              </select>
              <label for="perlengkapan_diri">Perlengkapan Diri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='catatan_diri' name="catatanDiri" class="form-control" placeholder="Catatan perlengkapan diri">
              <label for="catatan_diri">Catatan Peralatan Sekolah</label>
            </div>
          </div>
          <div class="col-12 text-center">
            <button type="submit" id="btn-submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
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
  const title = 'Kelengkapan Santri : {{$var['EmployeeNew']->nama}}';
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
                  doc.content[1].table.widths = [35,170,30,"*","*","*"];

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
 
  $('#form-kelengkapan').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    // showBlock();
    $('#btn-submit').prop('disabled', true);
    $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('murroby/kelengkapan/store'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        //hilangkan modal
        $('#tambah-kelengkapan').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Kelengkapan ', ' ').concat(' berhasil ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
        $('#btn-submit').prop('disabled', false);
      },
      error: function error(err) {
        Swal.fire({
          title: 'Duplicate Entry!',
          text: title + ' Not Saved !',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
        $('#btn-submit').prop('disabled', false);
      }
    });
  });

  $(document).on('click', '#btn-tambah-kelengkapan', function () {
    $('#form-kelengkapan')[0].reset();
  });

});



</script>