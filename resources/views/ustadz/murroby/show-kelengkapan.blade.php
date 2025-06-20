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
        <h4>Detail Kelengkapan {{ $var['dataSantri']->nama }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
            <table class="dataTable table">
            <thead>
                <tr>
                    <td></td>
                    <td>Tanggal</td>
                    <td>Perlengkapan Mandi</td>
                    <td>Catatan Perlengkapan Mandi</td>
                    <td>Peralatan Sekolah</td>
                    <td>Catatan Peralatan Sekolah</td>
                    <td>Perlengkapan Diri</td>
                    <td>Catatan Perlengkapan Diri</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @php
                $i = 1;
                @endphp
                @foreach($var['kelengkapan'] as $row)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{ $row->tanggal ?? '-' }}</td>
                    <td>{{ $row->perlengkapan_mandi ?? '-' }}</td>
                    <td>{{ $row->catatan_mandi ?? '-' }}</td>
                    <td>{{ $row->peralatan_sekolah ?? '-' }}</td>
                    <td>{{ $row->catatan_sekolah ?? '-' }}</td>
                    <td>{{ $row->perlengkapan_diri ?? '-' }}</td>
                    <td>{{ $row->catatan_diri ?? '-' }}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                        <button type="button" id="btn-edit" data-id="{{$row->id}}" class="btn btn-primary edit-kelengkapan waves-effect"><i class="mdi mdi-pencil me-1"></i></button>
                        <button type="button" id="btn-delete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-kelengkapan" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
                        </div>
                    </td>
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
<!-- Kelengkapan -->
<div class="modal fade" id="modal-edit-kelengkapan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Edit Kelengkapan</h3>
        </div>
        <form id="form-edit-kelengkapan" class="row g-4" onsubmit="return false">
          <input type="hidden" name="id" id="id-kelengkapan">
          <input type="hidden" name="noInduk" id="no_induk-kelengkapan">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id="tanggal-kelengkapan" name="tanggal" class="form-control">
              <label for="tanggal-kelengkapan">Tanggal</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="perlengkapan_mandi-kelengkapan" name="perlengkapanMandi" class="form-control select2">
                <option value='0'>Tidak Lengkap</option>
                <option value='1'>Lengkap & Kurang baik</option>
                <option value='2'>Lengkap & Baik</option>
              </select>
              <label for="perlengkapan_mandi-kelengkapan">Perlengkapan Mandi</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='catatan_mandi-kelengkapan' name="catatanMandi" class="form-control" placeholder="Catatan perlengkapan mandi">
              <label for="catatan_mandi-kelengkapan">Catatan Perlengkapan Mandi</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="peralatan_sekolah-kelengkapan" name="peralatanSekolah" class="form-control select2">
                <option value='0'>Tidak Lengkap</option>
                <option value='1'>Lengkap & Kurang baik</option>
                <option value='2'>Lengkap & Baik</option>
              </select>
              <label for="peralatan_sekolah-kelengkapan">Peralatan Sekolah</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='catatan_sekolah-kelengkapan' name="catatanSekolah" class="form-control" placeholder="Catatan peralatan sekolah">
              <label for="catatan_sekolah-kelengkapan">Catatan Peralatan Sekolah</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="perlengkapan_diri-kelengkapan" name="perlengkapanDiri" class="form-control select2">
                <option value='0'>Tidak Lengkap</option>
                <option value='1'>Lengkap & Kurang baik</option>
                <option value='2'>Lengkap & Baik</option>
              </select>
              <label for="perlengkapan_diri-kelengkapan">Perlengkapan Diri</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" id='catatan_diri-kelengkapan' name="catatanDiri" class="form-control" placeholder="Catatan perlengkapan diri">
              <label for="catatan_diri-kelengkapan">Catatan Peralatan Sekolah</label>
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
  const title = 'Detail Kelengkapan {{$var['dataSantri']->nama}} : {{$var['EmployeeNew']->nama}}';
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
  
  $('#form-edit-kelengkapan').submit(function(e) {
        e.preventDefault();

        const idKelengkapan = $('#id-kelengkapan').val(); 

        var formData = new FormData(this);
        formData.append('_method', 'PUT');

         $.ajax({
            url: ''.concat(baseUrl).concat('murroby/kelengkapan/update/').concat(idKelengkapan),
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function(status) {
                $('#modal-edit-kelengkapan').modal('hide');
                showUnblock();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Kelengkapan berhasil diperbarui.',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            },
            error: function(err) {
                showUnblock();
                // console.log(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            }
        });
    });

  $(document).on('click', '.edit-kelengkapan', function () {
      const id = $(this).data('id');

      $('.loader-container').show();

      $.get(''.concat(baseUrl).concat('murroby/kelengkapan/edit/').concat(id), function (data) {
          Object.keys(data).forEach(key => {
              if (data[key] == null) {
                data[key] = '';
              }

              if(key == 'tanggal')
              {
                $('#' + key + '-kelengkapan')
                  .val(data[key].split(' ')[0])
                  .trigger('change');
              }else{
                $('#' + key + '-kelengkapan')
                  .val(data[key])
                  .trigger('change.select2');
              }
          });
          $('.loader-container').hide();
          $('#modal-edit-kelengkapan').modal('show');
      });
  });

  $(document).on('click', '.delete-kelengkapan', function () {
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
        $('.loader-container').show();
        if (result.isConfirmed) {
            // Delete the data
            $.ajax({
                type: 'DELETE',
                url: ''.concat(baseUrl, 'murroby/kelengkapan/delete/', id),
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
                },
                error: function (_error) {
                    // console.log(_error);
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



</script>