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
        url: baseUrl + page + '_filter/' + $("#id_gelombang").val()
      },
      columns: my_data,
      columnDefs: [{
        // For Responsive
        className: 'control',
        searchable: false,
        orderable: false,
        responsivePriority: 2,
        targets: 0,
        render: function render(data, type, full, meta) {
          return '';
        }
      }, {
        searchable: false,
        orderable: false,
        targets: 1,
        render: function render(data, type, full, meta) {
          return '<span>'.concat(full.fake_id, '</span>');
        }
      }, {
        // User full name
        targets: 2,
        responsivePriority: 4,
        render: function render(data, type, full, meta) {
          var $name = full['nama'];
          var $id = full['id'];
          var img = 'https://psb.ppatq-rf.id/assets/images/upload/foto_casan/' + full['file_photo'];
          // For Avatar badge
          var stateNum = Math.floor(Math.random() * 6);
          var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
          var $state = states[stateNum],
            $name = full['nama'],
            $initials = $name.match(/\b\w/g) || [],
            $output;
          $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
          if (full['file_photo'] == '') $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';else $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '"><img class="avatar-initial rounded-circle" src="' + img + '" width="100%"></span>';

          // Creates full output for row
          var $row_output = '<div class="d-flex justify-content-start align-items-center user-name">' + '<div class="avatar-wrapper">' + '<div class="avatar avatar-sm me-3">' + $output + '</div>' + '</div>' + '<div class="d-flex flex-column">' + '<a href="' + view + '/' + $id + '" class="text-body text-truncate"><span class="fw-semibold">' + $name + '</span> <br /> (' + full.no_pendaftaran + ') <br /> pass : ' + full.password + '</a>' + '</div>' + '</div>';
          return $row_output;
        }
      }, {
        targets: 3,
        render: function render(data, type, full, meta) {
          return '<span>'.concat(full.tempat_lahir, '</span><br /><span>').concat(full.tanggal_lahir, '</span><br />').concat('(' + full.umur_tahun + ' tahun ' + full.umur_bulan + ' bulan)');
        }
      }, {
        targets: 4,
        render: function render(data, type, full, meta) {
          return '<span>'.concat(full.no_wa, '</span>');
        }
      }, {
        targets: 5,
        render: function render(data, type, full, meta) {
          return '<span>'.concat(full.jenis_kelamin, '</span>');
        }
      }, {
        targets: 6,
        render: function render(data, type, full, meta) {
          return '<span>'.concat(full.tanggal_daftar, '</span>');
        }
      }, {
        targets: 7,
        render: function render(data, type, full, meta) {
          return '<span><small>BB : '.concat(full.berat_badan, ' kg</small></span><br />').concat('<span><small>TB : ').concat(full.tinggi_badan, ' cm</small></span><br />').concat('<span><small>LD : ').concat(full.lingkar_dada, ' cm</small></span><br />').concat('<span><small>LP : ').concat(full.lingkar_pinggul, ' cm</small></span><br />');
        }
      }, {
        searchable: false,
        orderable: false,
        targets: 8,
        render: function render(data, type, full, meta) {
          var file_kk = full['file_kk'] ? "<a href='https://psb.ppatq-rf.id/assets/images/upload/file_kk/"+full['file_kk'] + "' class='text-success' target='_blank'> <i class='mdi mdi-check-circle mdi-20px'></i></a>" : "<span class='text-danger'> <i class='mdi mdi-close-circle mdi-20px'></i></span>";
          var file_ktp = full['file_ktp'] ? "<a href='https://psb.ppatq-rf.id/assets/images/upload/file_ktp/"+full['file_ktp'] + "' class='text-success' target='_blank'> <i class='mdi mdi-check-circle mdi-20px'></i></a>" : "<span class='text-danger'> <i class='mdi mdi-close-circle mdi-20px'></i></span>";
          var file_rapor = full['file_rapor'] ? "<a href='https://psb.ppatq-rf.id/assets/images/upload/file_rapor/"+full['file_rapor'] + "' class='text-success' target='_blank'> <i class='mdi mdi-check-circle mdi-20px'></i></a>" : "<span class='text-danger'> <i class='mdi mdi-close-circle mdi-20px'></i></span>";
          return '<small class="text-light">KK : ' + file_kk + '</small><br/><small class="text-light">KTP : ' + file_ktp + '</small><br/><small class="text-light">Rapor : ' + file_rapor + '</small>';
        }
      }, {
        searchable: false,
        orderable: false,
        targets: 9,
        render: function render(data, type, full, meta) {
          if (parseInt(full.status) == 2) {
            return "<span class='text-success'> <i class='mdi mdi-check-circle mdi-20px'></i></span>";
          } else {
            return "<span class='text-danger'> <i class='mdi mdi-close-circle mdi-20px'></i></span>";
          }
        }
      }, {
        searchable: false,
        orderable: false,
        targets: 10,
        render: function render(data, type, full, meta) {
          return '<a href="https://psb.ppatq-rf.id/assets/formulir/DAFTAR_PPATQ_RF_'+ full['nama_lengkap'] + '_'+ full['no_pendaftaran'] + '.pdf" target="_blank"><i class="mdi mdi-file-pdf-box mdi-20px"></i></a><button class="btn btn-sm btn-icon delete-record" data-id="' + full['id'] + '"><i class="mdi mdi-delete-outline mdi-20px"></i></button></div>';
        }
      }],
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
