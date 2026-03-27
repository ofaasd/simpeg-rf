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
            <button type="button" id="btn-tambah-perilaku" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah-perilaku"> Tambah Perilaku</button>
          </div>
        </div>
        <div class="table-responsive"  id="table-perilaku">
          <table class="dataTable table">
            <thead>
              <tr>
                <td></td>
                <td>NIS</td>
                <td>Nama</td>
                <td>Tanggal</td>
                <td>Ketertiban</td>
                <td>Kebersihan</td>
                <td>Kedisiplinan</td>
                <td>Kerapian</td>
                <td>Kesopanan</td>
                <td>Kepekaan Lingkungan</td>
                <td>Ketaatan Peraturan</td>
                <td></td>
              </tr>
            </thead>
            <tbody id="table-perilaku">
              @php
                $i = 1;
              @endphp
              @foreach($var['listSantri'] as $row)
                <tr>
                  <td>{{$i}}</td>
                  <td>{{$row->no_induk}}</td>
                  <td>{{$row->nama}}</td>
                  <td>
                    {{ isset($var['perilaku'][$row->no_induk]->tanggal) 
                        ? \Carbon\Carbon::parse($var['perilaku'][$row->no_induk]->tanggal)->format('Y-m-d') 
                        : '-' 
                    }}
                  </td>

                  <td>{{ $var['perilaku'][$row->no_induk]->ketertiban ?? '-' }}</td>
                  <td>{{ $var['perilaku'][$row->no_induk]->kebersihan ?? '-' }}</td>
                  <td>{{ $var['perilaku'][$row->no_induk]->kedisiplinan ?? '-' }}</td>
                  <td>{{ $var['perilaku'][$row->no_induk]->kerapian ?? '-' }}</td>
                  <td>{{ $var['perilaku'][$row->no_induk]->kesopanan ?? '-' }}</td>
                  <td>{{ $var['perilaku'][$row->no_induk]->kepekaan_lingkungan ?? '-' }}</td>
                  <td>{{ $var['perilaku'][$row->no_induk]->ketaatan_peraturan ?? '-' }}</td>
                  <td class='text-center'><a href="{{url('murroby/perilaku/detail/' . $row->no_induk)}}"><span class="mdi mdi-information"></span></a></td>
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
<div class="modal fade" id="tambah-perilaku" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Tambah Perilaku Santri</h3>
        </div>
        <form id="form-perilaku" class="row g-4" onsubmit="return false">
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
              <select id="ketertiban" name="ketertiban" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="ketertiban">Ketertiban</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kebersihan" name="kebersihan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kebersihan">Kebersihan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kedisiplinan" name="kedisiplinan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kedisiplinan">Kedisiplinan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kerapian" name="kerapian" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kerapian">Kerapian</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kesopanan" name="kesopanan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kesopanan">Kesopanan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kepekaan-lingkungan" name="kepekaanLingkungan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kepekaan-lingkungan">Kepekaan Lingkungan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="ketaatan-peraturan" name="ketaatanPeraturan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="ketaatan-peraturan">Ketaatan Peraturan</label>
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
  const title = 'Perilaku Santri : {{$var['EmployeeNew']->nama}}';
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
 
  $('#form-perilaku').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    // showBlock();
    $('#btn-submit').prop('disabled', true);
    $.ajax({
      data: formData,
      url: ''.concat(baseUrl).concat('murroby/perilaku/store'),
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      success: function success(status) {
        // sweetalert unblock data
        // showUnblock();
        //hilangkan modal
        $('#tambah-perilaku').modal('hide');
        //reset form
        reload_table();
        //refresh table
        Swal.fire({
          icon: 'success',
          title: 'Successfully '.concat(' Updated !'),
          text: ''.concat('Perilaku ', ' ').concat(' berhasil ditambahkan'),
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
        $('#btn-submit').prop('disabled', false);
      },
      error: function error(err) {
        // showUnblock();
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

  $(document).on('click', '#btn-tambah-perilaku', function () {
    $('#form-perilaku')[0].reset();
  });
});

</script>