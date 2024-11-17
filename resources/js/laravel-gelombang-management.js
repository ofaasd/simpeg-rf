/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  //initial variabl
  var page = $('#page').val();
  var title = $('#title').val();
  var my_column = $('#my_column').val();
  const pecah = my_column.split('\n');
  let my_data = [];
  console.log(my_data);

  pecah.forEach((item, index) => {
    let temp = item.replace(/ /g, '');
    let data_obj = { data: temp };
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
          targets: 7,
          render: function render(data, type, full, meta) {
            if (full.pmb_online == '1') {
              return '<span class="text-success text-center" title="status aktif"> <i class="mdi mdi-check-circle mdi-20px"></i></span>';
            } else {
              return '<span class="text-danger text-center" title="status tidak aktif"> <i class="mdi mdi-close-circle mdi-20px"></i></span>';
            }
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
              '<a href="').concat(baseUrl).concat('gelombang_detail/').concat(full['id']).concat('" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary" data-bs-original-title="Detail Gelombang"><i class="mdi mdi-clipboard-list mdi-20px"></i></a>'+
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
    $.get(''.concat(baseUrl).concat(page, '/').concat(id, '/edit'), function (data) {
      Object.keys(data).forEach(key => {
        //console.log(key);
        if (key == 'id') $('#' + page + '_' + key).val(data[key]);
        else
          $('#add-' + page + '-' + key)
            .val(data[key])
            .trigger('change');
      });
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
    $.ajax({
      data: $('#addNew' + title + 'Form').serialize(),
      url: ''.concat(baseUrl).concat(page),
      type: 'POST',
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
    fv.resetForm(true);
  });
});
