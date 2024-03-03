<table class="dataTable table">
  <thead>
    <tr>
      <td>No.</td>
      <td>Nama Agenda</td>
      <td>Tanggal Mulai</td>
      <td>Tanggal Selesai</td>
      <td>Kategori</td>
      <td>Aksi</td>
    </tr>
  </thead>
  <tbody id="table_agen">
    @php
    $i = 1;
    @endphp
    @foreach($agenda as $row)
      <tr>
        <td>{{$i}}</td>
        <td>{{$row->judul}}</td>
        <td>{{date('d-m-Y', strtotime($row->tanggal_mulai))}}</td>
        <td>{{date('d-m-Y', strtotime($row->tanggal_selesai))}}</td>
        <td>{{$kategori[$row->kategori]}}</td>
        <td>
          <div class="btn-group btn-group-sm" role="group" aria-label="First group">
            <button type="button" id="btnEdit" data-id="{{$row->id}}" class="btn btn-primary edit_agenda waves-effect" data-bs-toggle="modal" data-bs-target="#modal_agenda" data-status="agenda"><i class="mdi mdi-pencil me-1"></i></button>
            <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-record" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
          </div>
        </td>
      </tr>
    @php
    $i++;
    @endphp
    @endforeach
  </tbody>

</table>

<script>
  function zeroPadded(val) {
    if (val >= 10)
      return val;
    else
      return '0' + val;
  }
  document.addEventListener("DOMContentLoaded", function(event) {
    $(".select2").select2({
      dropdownParent: $("#modal_sakit")
    });
    $(".select2-sembuh").select2({
      disabled : true,
      dropdownParent: $("#modal_sembuh")
    });
    $('#modal_sakit').on('hidden.bs.modal', function () {
        $('#formAgenda').trigger("reset");
    });
    $('.dataTable').dataTable();
    $('#formAgenda').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      formData.append('isi',$("#full-editor").html());
      //showBlock();
      insert_update(formData);
    });

    $(document).on('click', '.edit_agenda', function () {
      const id = $(this).data('id');
      // get data
      $.get(''.concat(baseUrl).concat('agenda/').concat(id, '/edit'), function (data) {
      let date = '';
      Object.keys(data).forEach(key => {
          //console.log(key);

          if(key == 'id'){
            $('#id_agenda')
              .val(data[key])
              .trigger('change');
          }else if(key == 'isi'){
            $("#full-editor").html(data[key]);
          }else if(key == 'gambar'){
            $("#link_gambar").html('<a href="' + baseUrl + 'assets/img/upload/foto_agenda/' + data[key] + '" target="_blank">Link Gambar</a>');
          }else{
            $('#' + key)
                .val(data[key])
                .trigger('change');
          }
      });
      });
    });
    $(document).on('click', '.delete-record', function () {
      const id = $(this).data('id');
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
            url: ''.concat(baseUrl).concat('agenda/').concat(id),
            success: function success() {
              reload_table(bulan,tahun);
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
  function insert_update(formData){
    $.ajax({
        data: formData,
        url: ''.concat(baseUrl).concat('agenda'),
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function success(status) {
          // sweetalert unblock data
          //showUnblock();
          //hilangkan modal
          $('#modal_agenda').modal('hide');
          //reset form
          reload_table();
          //refresh table
          Swal.fire({
            icon: 'success',
            title: 'Successfully '.concat(' Updated !'),
            text: ''.concat('Data ', ' ').concat(' Berhasil Ditambahkan'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        },
        error: function error(err) {
          //showUnblock();
          Swal.fire({
            title: 'Duplicate Entry!',
            text:  'Data Not Saved !',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        }
      });
  }
  function reload_table(){
    showBlock();
    $.ajax({
      url: ''.concat(baseUrl).concat('agenda/reload'),
      type: 'POST',
      success: function success(data) {
        $("#table_agenda").html(data);
        showUnblock();
      },
    });
  }


  </script>
