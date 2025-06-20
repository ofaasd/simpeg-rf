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
        <h4>Detail Perilaku {{ $var['dataSantri']->nama }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
            <table class="dataTable table">
            <thead>
                <tr>
                    <td></td>
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
            <tbody>
                @php
                $i = 1;
                @endphp
                @foreach($var['perilaku'] as $row)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{ $row->tanggal ?? '-' }}</td>
                    <td>{{ $row->ketertiban ?? '-' }}</td>
                    <td>{{ $row->kebersihan ?? '-' }}</td>
                    <td>{{ $row->kedisiplinan ?? '-' }}</td>
                    <td>{{ $row->kerapian ?? '-' }}</td>
                    <td>{{ $row->kesopanan ?? '-' }}</td>
                    <td>{{ $row->kepekaan_lingkungan ?? '-' }}</td>
                    <td>{{ $row->ketaatan_peraturan ?? '-' }}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                        <button type="button" id="btn-edit" data-id="{{$row->id}}" class="btn btn-primary edit-perilaku waves-effect"><i class="mdi mdi-pencil me-1"></i></button>
                        <button type="button" id="btn-delete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-perilaku" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
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
<!-- Perilaku -->
<div class="modal fade" id="modal-edit-perilaku" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body py-3 py-md-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Edit Perilaku</h3>
        </div>
        <form id="form-edit-perilaku" class="row g-4" onsubmit="return false">
          <input type="hidden" name="id" id="id-perilaku">
          <input type="hidden" name="noInduk" id="no_induk-perilaku">
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" id="tanggal-perilaku" name="tanggal" class="form-control">
              <label for="tanggal-perilaku">Tanggal</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="ketertiban-perilaku" name="ketertiban" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="ketertiban-perilaku">Ketertiban</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kebersihan-perilaku" name="kebersihan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kebersihan-perilaku">Kebersihan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kedisiplinan-perilaku" name="kedisiplinan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kedisiplinan-perilaku">Kedisiplinan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kerapian-perilaku" name="kerapian" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kerapian-perilaku">Kerapian</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kesopanan-perilaku" name="kesopanan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kesopanan-perilaku">Kesopanan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="kepekaan_lingkungan-perilaku" name="kepekaanLingkungan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="kepekaan_lingkungan-perilaku">Kepekaan Lingkungan</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-floating form-floating-outline">
              <select id="ketaatan_peraturan-perilaku" name="ketaatanPeraturan" class="form-control select2">
                <option value='0'>Kurang Baik</option>
                <option value='1'>Cukup</option>
                <option value='2'>Baik</option>
              </select>
              <label for="ketaatan_peraturan-perilaku">Ketaatan Peraturan</label>
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
  const title = 'Detail Perilaku {{$var['dataSantri']->nama}} : {{$var['EmployeeNew']->nama}}';
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
  
  $('#form-edit-perilaku').submit(function(e) {
        e.preventDefault();

        const idPerilaku = $('#id-perilaku').val(); 

        var formData = new FormData(this);
        formData.append('_method', 'PUT');

         $.ajax({
            url: ''.concat(baseUrl).concat('murroby/perilaku/update/').concat(idPerilaku),
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function(status) {
                $('#modal-edit-perilaku').modal('hide');
                showUnblock();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Perilaku berhasil diperbarui.',
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

  $(document).on('click', '.edit-perilaku', function () {
      const id = $(this).data('id');

      $('.loader-container').show();

      $.get(''.concat(baseUrl).concat('murroby/perilaku/edit/').concat(id), function (data) {
          Object.keys(data).forEach(key => {
              if (data[key] == null) {
                data[key] = '';
              }

              if(key == 'tanggal')
              {
                $('#' + key + '-perilaku')
                  .val(data[key].split(' ')[0])
                  .trigger('change');
              }else{
                $('#' + key + '-perilaku')
                  .val(data[key])
                  .trigger('change.select2');
              }
          });
          $('.loader-container').hide();
          $('#modal-edit-perilaku').modal('show');
      });
  });

  $(document).on('click', '.delete-perilaku', function () {
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
                url: ''.concat(baseUrl, 'murroby/perilaku/delete/', id),
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