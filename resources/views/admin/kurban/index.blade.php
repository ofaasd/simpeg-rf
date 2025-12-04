@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

{{-- @section('page-script')
<script src="{{asset('js/laravel-kurban.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('js/laravel-academic-management.js')}}"></script>
@endsection --}}

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
    <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
    <input type="hidden" name="page" id='page' value='kurban'>
    <input type="hidden" name="title" id='title' value='Kurban'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>Jumlah</th>
          <th>Jenis Kurban</th>
          <th>Atas Nama</th>
          <th>Tanggal</th>
          <th>Tahun Hijriah</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>

  <!-- Offcanvas to add new {{strtolower($title)}} -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAdd{{$title}}" aria-labelledby="offcanvasAdd{{$title}}Label">
    <div class="offcanvas-header">
      <h5 id="offcanvasAdd{{$title}}Label" class="offcanvas-title">Add {{$title}}</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Form">
        <input type="hidden" name="id" id="{{strtolower($title)}}_id">

       <div class="form-floating form-floating-outline mb-4">
        <select class="form-select select2" id="add-{{ strtolower($title) }}-id_santri" name="id_santri" required>
          <option selected disabled>Pilih Santri</option>
          @foreach($santri as $s)
            <option value="{{ $s->id }}">{{ $s->nama }}</option>
          @endforeach
        </select>
        <label for="add-{{ strtolower($title) }}-id_santri">Santri</label>
      </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-select" id="add-{{ strtolower($title) }}-jenis" name="jenis" required>
            <option selected disabled>Pilih Jenis</option>
            <option value="1">Sapi</option>
            <option value="2">Kambing</option>
            <option value="3">Domba</option>
            <option value="4">Lainnya</option>
          </select>
          <label for="add-{{ strtolower($title) }}-jenis">Jenis Kurban</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="number" class="form-control" id="add-{{strtolower($title)}}-jumlah" placeholder="Jumlah" name="jumlah" required />
          <label for="add-{{strtolower($title)}}-jumlah">Jumlah</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="date" class="form-control" id="add-{{strtolower($title)}}-tanggal" placeholder="Tanggal Kurban" name="tanggal" required />
          <label for="add-{{strtolower($title)}}-tanggal">Tanggal</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="number" 
                class="form-control" 
                id="add-{{ strtolower($title) }}-tahun_hijriah" 
                placeholder="Tahun Hijriah" 
                name="tahun_hijriah" 
                required />
          <label for="add-{{ strtolower($title) }}-tahun_hijriah">Tahun Hijriah</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{ strtolower($title) }}-atas_nama" name="atas_nama" placeholder="Atas Nama">
          <label for="add-{{ strtolower($title) }}-atas_nama">Atas Nama</label>
        </div>
         <!-- Input Upload Gambar -->
        <div class="mb-4">
          <label for="add-{{ strtolower($title) }}-foto" class="form-label">Upload Foto</label>
          <input class="form-control" type="file" id="add-{{ strtolower($title) }}-foto" name="foto" accept="image/*" onchange="previewImage(event)">
           <input type="hidden" name="old_foto" id="old_foto">
          <img id="preview-{{ strtolower($title) }}-foto" src="#" alt="Preview Foto" style="display:none; margin-top:10px; max-height:150px; object-fit:contain;" />
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
<script>
window.addEventListener('load', function(){
$(function () {
      //initial variabl
      var page = $('#page').val();
      var title = $('#title').val();
      // console.log(title);
      var my_column = $('#my_column').val();
      var pecah = my_column.split('\n');
      var my_data = [];
      // console.log(my_data);
      pecah.forEach(function (item, index) {
        var temp = item.replace(/ /g, '');
        var data_obj = {
          data: temp
        };
        //alert(data_obj.data);
        my_data.push(data_obj);
      });
      //alert(data_obj);
      // console.log(my_data);
      //alert(JSON.stringify(my_column.split('\n')));
      // Variable declaration for table
      var dt_table = $('.datatables-' + page),
        select2 = $('.select2'),
        view = baseUrl + 'app/' + page + '/view/',
        offCanvasForm = $('#offcanvasAdd' + title);
      if (select2.length) {
        var $this = select2;
        $this.wrap('<div class="position-relative"></div>').select2({
          placeholder: 'Pilih Santri',
          dropdownParent: $this.parent()
        });
      }

      // ajax setup
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // datatable
      if (dt_table.length) {
        var dt = dt_table.DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: baseUrl + page
          },
          columns: my_data,
          columnDefs: [
            {
              // For Responsive
              className: 'control',
              searchable: false,
              orderable: false,
              responsivePriority: 2,
              targets: 0,
              render: function render(data, type, full, meta) {
                return '';
              }
            },
            {
              searchable: false,
              orderable: false,
              targets: 1,
              render: function render(data, type, full, meta) {
                return '<span>'.concat(full.fake_id, '</span>');
              }
            },
            {
              // Actions
              targets: -1,
              title: 'Actions',
              searchable: false,
              orderable: false,
              render: function render(data, type, full, meta) {
                return (
                  '<div class="d-inline-block text-nowrap">' +
                  '<button class="btn btn-sm btn-icon edit-record" data-id="'
                    .concat(full['id'], '" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAdd')
                    .concat(title, '"><i class="mdi mdi-pencil-outline mdi-20px"></i></button>') +
                  '<button class="btn btn-sm btn-icon delete-record" data-id="'.concat(
                    full['id'],
                    '"><i class="mdi mdi-delete-outline mdi-20px"></i></button>'
                  )
                );
              }
            }
          ],
          order: [[2, 'desc']],
          dom:
            '<"row mx-2"' +
            '<"col-md-2"<"me-3"l>>' +
            '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
          language: {
            sLengthMenu: '_MENU_',
            search: '',
            searchPlaceholder: 'Search..'
          },
          // Buttons with Dropdown
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
                    columns: [2, 3],
                    // prevent avatar to be print
                    format: {
                      body: function body(inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                          result = result + item.innerText;
                        });
                        return result;
                      }
                    }
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
                    columns: [2, 3],
                    // prevent avatar to be print
                    format: {
                      body: function body(inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                          if (item.classList.contains('user-name')) {
                            result = result + item.lastChild.textContent;
                          } else result = result + item.innerText;
                        });
                        return result;
                      }
                    }
                  }
                },
                {
                  extend: 'excel',
                  title: title,
                  text: '<i class="mdi mdi-file-excel-outline me-1" ></i>Excel',
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: [2, 3],
                    // prevent avatar to be display
                    format: {
                      body: function body(inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                          if (item.classList.contains('user-name')) {
                            result = result + item.lastChild.textContent;
                          } else result = result + item.innerText;
                        });
                        return result;
                      }
                    }
                  }
                },
                {
                  extend: 'pdf',
                  title: title,
                  text: '<i class="mdi mdi-file-pdf-box me-1"></i>Pdf',
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: [2, 3],
                    // prevent avatar to be display
                    format: {
                      body: function body(inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                          if (item.classList.contains('user-name')) {
                            result = result + item.lastChild.textContent;
                          } else result = result + item.innerText;
                        });
                        return result;
                      }
                    }
                  }
                },
                {
                  extend: 'copy',
                  title: title,
                  text: '<i class="mdi mdi-content-copy me-1" ></i>Copy',
                  className: 'dropdown-item',
                  exportOptions: {
                    columns: [2, 3],
                    // prevent avatar to be copy
                    format: {
                      body: function body(inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                          if (item.classList.contains('user-name')) {
                            result = result + item.lastChild.textContent;
                          } else result = result + item.innerText;
                        });
                        return result;
                      }
                    }
                  }
                }
              ]
            },
            {
              text:
                '<i class="mdi mdi-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New ' +
                title +
                '</span>',
              className: 'add-new btn btn-primary',
              attr: {
                'data-bs-toggle': 'offcanvas',
                'data-bs-target': '#offcanvasAdd' + title
              }
            }
          ]
          // For responsive popup
        });
      }

      // Delete Record
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
              url: ''.concat(baseUrl).concat(page, '/').concat(id),
              success: function success() {
                dt.draw();
              },
              error: function error(_error) {
                // console.log(_error);
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

      // edit record
      $(document).on('click', '.edit-record', function () {
        var id = $(this).data('id'),
          dtrModal = $('.dtr-bs-modal.show');

        // hide responsive modal in small screen
        if (dtrModal.length) {
          dtrModal.modal('hide');
        }

        // changing the title of offcanvas
        $('#offcanvasAdd' + title + 'Label').html('Edit ' + title);

        // get data
        $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
          Object.keys(data).forEach(function (key) {
            if (key == 'id') {
              $('#' + page + '_' + key).val(data[key]);
            } else if (key == 'foto') {
              // Input file TIDAK PERLU DIISI, cukup tampilkan preview
              if (data.foto) {
                $('#preview-' + page + '-foto')
                  .attr('src', baseUrl + 'assets/img/upload/kurban/' + data.foto)
                  .show();
                $('#old_foto').val(data.foto); // Untuk backend: info kalau ini foto lama
              } else {
                $('#preview-' + page + '-foto').hide();
                $('#old_foto').val('');
              }
            } else {
              // Semua input selain file bisa diisi seperti biasa
              $('#add-' + page + '-' + key)
                .val(data[key])
                .trigger('change');
            }
          });
        });
      });

      // changing the title
      $('.add-new').on('click', function () {
        $('#' + page + '_id').val('');
        $('#offcanvasAdd' + title + 'Label').html('Add ' + title);
        $('#addNew' + title + 'Form')[0].reset();
        $('#addNew' + title + 'Form select')
          .val(null)
          .trigger('change');
        $('#preview-' + page + '-foto')
          .attr('src', '#')
          .hide();
        $('#add-' + page + '-foto').val('');
        $('#old_foto').val('');
      });

      // validating form and updating data
      var addNewForm = document.getElementById('addNew' + title + 'Form');

      // user form validation
      var fv = FormValidation.formValidation(addNewForm, {
        fields: {
          id_santri: {
            validators: {
              notEmpty: {
                message: 'Santri harus dipilih'
              }
            }
          },
          jenis: {
            validators: {
              notEmpty: {
                message: 'Jenis kurban harus dipilih'
              }
            }
          },
          jumlah: {
            validators: {
              notEmpty: {
                message: 'Jumlah harus diisi'
              }
            }
          },
          tanggal: {
            validators: {
              notEmpty: {
                message: 'Tanggal harus diisi'
              }
            }
          },
          tahun_hijriah: {
            validators: {
              notEmpty: {
                message: 'Tahun Hijriah harus diisi'
              }
            }
          },
          atas_nama: {
            validators: {
              notEmpty: {
                message: 'Atas nama harus diisi'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            // Use this for enabling/changing valid/invalid class
            eleValidClass: '',
            rowSelector: function rowSelector(field, ele) {
              // field is the field name & ele is the field element
              return '.mb-4';
            }
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          // Submit the form when all fields are valid
          // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        }
      }).on('core.form.valid', function () {
        // adding or updating user when form successfully validate
        $.ajax({
          data: new FormData($('#addNew' + title + 'Form')[0]), // ubah ini
          url: ''.concat(baseUrl).concat(page),
          type: 'POST',
          processData: false,
          contentType: false,
          success: function success(status) {
            dt.draw();
            offCanvasForm.offcanvas('hide');

            Swal.fire({
              icon: 'success',
              title: 'Successfully '.concat(status, '!'),
              text: ''.concat(title, ' ').concat(status, ' Successfully.'),
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });
          },
          error: function error(err) {
            offCanvasForm.offcanvas('hide');
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

      // clearing form data when offcanvas hidden
      offCanvasForm.on('hidden.bs.offcanvas', function () {
        fv.resetForm(true);
      });
    });
});
function previewImage(event) {
  const input = event.target;
  const preview = document.getElementById('preview-{{ strtolower($title) }}-foto');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    }
    reader.readAsDataURL(input.files[0]);
  } else {
    preview.src = '#';
    preview.style.display = 'none';
  }
}
</script>

@endsection
