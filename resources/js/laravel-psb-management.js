/**
 * Page User List
 * data table minimal tanpa ada action button dan tombol print
 */

'use strict';

// Datatable (jquery)
$(function () {
  //initial variabl
  var page = $('#page').val();
  var title = $('#title').val();
  var my_column = $('#my_column').val();
  var pecah = my_column.split('\n');
  var my_data = [];
  console.log(my_data);
  pecah.forEach(function (item, index) {
    var temp = item.replace(/ /g, '');
    if (temp != '') {
      var data_obj = {
        data: temp
      };
      //alert(data_obj.data);
      my_data.push(data_obj);
    }
  });
  console.log(my_data);
  //alert(JSON.stringify(my_column.split('\n')));
  // Variable declaration for table
  var dt_table = $('.datatables-' + page),
    select2 = $('.select2'),
    view = baseUrl + page,
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
          // User full name
          targets: 4,
          responsivePriority: 4,
          render: function render(data, type, full, meta) {
            var $name = full['nama'];
            var $id = full['id'];
            // For Avatar badge
            var stateNum = Math.floor(Math.random() * 6);
            var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
            var $state = states[stateNum],
              $name = full['nama'],
              $initials = $name.match(/\b\w/g) || [],
              $output;
            $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
            $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';

            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' +
              view +
              '/' +
              $id +
              '" class="text-body text-truncate"><span class="fw-semibold">' +
              $name +
              '</span></a>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          targets: 2,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.nik, '</span>');
          }
        },
        {
          targets: 3,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.no_pendaftaran, '</span>');
          }
        },
        {
          targets: 5,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.usia, '</span>');
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 6,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.status, '</span>');
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 7,
          render: function render(data, type, full, meta) {
            return (
              '<div class="d-inline-block text-nowrap"><a href="' +
              baseUrl +
              'psb/' +
              full.id +
              '/edit"><i class="mdi mdi-pencil-outline mdi-20px"></i></a>' +
              '<button class="btn btn-sm btn-icon delete-record" data-id="' +
              full['id'] +
              '"><i class="mdi mdi-delete-outline mdi-20px"></i></button></div>'
            );
          }
        }
      ],
      order: [[2, 'asc']],
      language: {
        search: '',
        searchPlaceholder: 'Search..'
      }
      // Buttons with Dropdown
    });
  }

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
});
