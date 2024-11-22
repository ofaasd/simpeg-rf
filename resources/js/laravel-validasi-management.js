/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  //initial variabl
  var page = $('#page').val();
  var title = $('#title').val();
  var url = $('#url').val();
  var my_column = $('#my_column').val();
  var pecah = my_column.split('\n');
  var my_data = [];
  console.log(my_data);
  pecah.forEach(function (item, index) {
    var temp = item.replace(/ /g, '');
    var data_obj = {
      data: temp
    };
    //alert(data_obj.data);
    my_data.push(data_obj);
  });
  //alert(data_obj);
  console.log(my_data);
  //alert(JSON.stringify(my_column.split('\n')));
  // Variable declaration for table
  var dt_table = $('.datatables-' + page),
    select2 = $('.select2'),
    view = baseUrl + 'app/' + page + '/view/',
    offCanvasForm = $('#offcanvasAdd' + title);
  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select Country',
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
        url: baseUrl + url + '_filter/' + $("#id_gelombang").val()
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
            var hasil = ''.concat(
              '<div class="d-inline-block text-nowrap">' +
                '<button class="btn btn-sm btn-icon edit-record" data-id="'
                  .concat(
                    full['id'],
                    '" data-bs-toggle="offcanvas" title="Detail Pembayaran" data-bs-target="#offcanvasAdd'
                  )
                  .concat(title, '"><i class="mdi mdi-pencil-outline mdi-20px"></i></button>')
            );
            var pengumuman = full['pengumuman_validasi_wa'];
            if (pengumuman == 0) {
              //   hasil.concat(' ', '<a href=' + baseUrl + '/validasi/kirim_file_pengumuman/' + full['id'] + '><i class="mdi mdi-message mdi-20px text-success"></i></a>');
              hasil =
                hasil +
                '<a href="javascript:void(0)" title="Kirim WA Pengumuman Pendaftaran" data-id="' +
                full['id'] +
                '" class="btn_send_pengumuman"><i class="mdi mdi-whatsapp mdi-20px text-success"></i></a>';
            } else {
              hasil = hasil + ' <i class="mdi mdi-whatsapp mdi-20px text-secondary"></i>';
            }
            return hasil;
          }
        },

        {
          targets: 5,
          className: 'text-center',
          render: function render(data, type, full, meta) {
            const $verified = full['bayar'];
            const $pengumuman_warning = full['pengumuman_warning'];
            let hasil = '';
            if ($verified == 0) {
              hasil = '<i class="mdi mdi-shield-off-outline mdi-20px text-danger" ></i>';
              if ($pengumuman_warning == 0) {
                hasil =
                  hasil +
                  '<a href="javascript:void(0)" title="Kirim WA Peringatan untuk Bayar" data-id="' +
                  full['id'] +
                  '" class="btn_send_warning"><i class="mdi mdi-whatsapp mdi-20px text-success"></i></a>';
              } else {
                hasil = hasil + ' <i class="mdi mdi-whatsapp mdi-20px text-secondary"></i>';
              }
            } else if ($verified == 1) {
              hasil = '<i class="mdi mdi-shield-check-outline mdi-20px text-secondary"></i>';
            } else {
              hasil = '<i class="mdi mdi-shield-check-outline mdi-20px text-success"></i>';
            }

            return hasil;
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
      }
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
    $.get(''.concat(baseUrl).concat(url, '/').concat(id, '/edit'), function (data) {
      $('#add-' + page + '-psb_peserta_id').val(id);
      Object.keys(data).forEach(function (key) {
        //console.log(key);
        if (key == 'id') $('#' + page + '_' + key).val(data[key]);
        else
          $('#add-' + page + '-' + key)
            .val(data[key])
            .trigger('change');

        if (key == 'bukti') {
          if (data[key]) {
            const $pecah = data[key].split('.');
            if ($pecah[1] == 'pdf') {
              $('#bukti_file')
                .html(`<object data="https://psb.ppatq-rf.id/assets/images/upload/file_bukti/${data[key]}" type="application/pdf" width="100%" height="400">
                          <p>PDF Link : <a href="https://psb.ppatq-rf.id/assets/images/upload/file_bukti/${data[key]}">to the PDF!</a></p>
                        </object>`);
            } else {
              $('#bukti_file').html(
                "<img src='https://psb.ppatq-rf.id/assets/images/upload/file_bukti/" + data[key] + "' width='100%'>"
              );
            }
          }
        }
      });
    });
  });
  $(document).on('click', '.btn_send_pengumuman', function () {
    const id = $(this).data('id');
    $.ajax({
      url: baseUrl + 'psb_new/kirim_file_pengumuman/' + id,
      method: 'GET',
      success: function success(status) {
        dt.draw();
        // sweetalert
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
  $(document).on('click', '.btn_send_warning', function () {
    const id = $(this).data('id');
    $.ajax({
      url: baseUrl + 'psb_new/kirim_file_warning/' + id,
      method: 'GET',
      success: function success(status) {
        dt.draw();
        // sweetalert
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

  // changing the title
  $('.add-new').on('click', function () {
    $('#' + page + '_id').val(''); //reseting input field
    $('#offcanvasAdd' + title + 'Label').html('Add ' + title);
  });

  // validating form and updating data
  var addNewForm = document.getElementById('addNew' + title + 'Form');

  // user form validation
  var fv = FormValidation.formValidation(addNewForm, {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter Name'
          }
        }
      },
      description: {
        validators: {
          notEmpty: {
            message: 'Please enter your description'
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
    //update file bukti jika ada ke api
    const formElement = $('#addNew' + title + 'Form');
    let data = new FormData(formElement[0]);
    const url_save = 'https://psb.ppatq-rf.id/api/simpan_bukti_bayar_api_admin';
    $.ajax({
      data: data,
      url: url_save,
      processData: false,
      contentType: false,
      method: 'POST',
      success: function success(status) {
        dt.draw();
        offCanvasForm.offcanvas('hide');

        // sweetalert
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
    //fv.resetForm(true);
    $('#addNew' + title + 'Form').trigger('reset');
    $('#bukti_file').html('');
  });
});
