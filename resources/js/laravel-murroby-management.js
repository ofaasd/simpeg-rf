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
    let temp = item.replace(/ /g, '');
    let data_obj = { data: temp };
    //alert(data_obj.data);
    my_data.push(data_obj);
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
          targets: 2,
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
            if(full.photo){
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '"><img src="'+full.url_photo+'/' + full.photo +'"></span>';
            }else{
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }

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
          targets: 3,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.jenis_kelamin, '</span>');
          }
        },
        {
          targets: 4,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.jabatan, '</span>');
          }
        },
        {
          targets: 5,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.alamat, '</span>');
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 6,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.pendidikan, '</span>');
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 7,
          render: function render(data, type, full, meta) {
            return '<span>'.concat(full.jumlah_santri, '</span>');
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
});
