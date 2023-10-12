@if($var['murroby'] == 1)

<h3>Tambah Santri Baru</h3>
<form action="javascript:void(0)" id="santri_murroby">
  <input type="hidden" name='pegawai_id' value="{{$var['EmployeeNew']->id}}">
  <div div class="mb-4">
    <select name="santri[]" class="form-control select2" id='add-{{strtolower($title)}}-santri' multiple required>
      @foreach($var['santri_all'] as $santri)
        <option value="{{$santri->no_induk}}">{{$santri->nama}} - {{$santri->no_induk}}</option>
      @endforeach
    </select>
  </div>
  <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id='edit-record'>Submit</button>
</form>
<table class='table table-hover datatables'>
  <thead>
    <tr>
      <th width="10">No.</th>
      <th>Nama Santri</th>
      <th>No. Induk</th>
      <th>Kelas</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody id="cont_murroby">
    @php $i = 1; @endphp

      @foreach($var['santri'] as $santri)
        <tr>
          <td>{{$i}}</td>
          <td>{{$santri->nama}}</td>
          <td>{{$santri->no_induk}}</td>
          <td>{{$santri->kelas}}</td>
          <td><button class="btn btn-sm btn-icon delete-record" data-id="{{$santri->no_induk}}"><i class="mdi mdi-delete-outline mdi-20px"></i></button></td>
        </tr>
        @php $i++; @endphp
      @endforeach

  </tbody>
</table>
@else
<div class="alert alert-danger">
  <p>jabatan Ustadz/ustadzah bukan murroby atau jika jabatan sudah diubah menjadi murroby, harap Daftarkan Ustadz/Ustadzah melalui link <a href="{{url('kamar')}}">Berikut</a></p>
</div>
@endif

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    const title = 'Santri Murroby';
    const dt = $(".datatables");
    const page = 'pegawai/hapus_murroby_santri';
    dt.DataTable();
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
            success: function success(data) {
              $("#cont_murroby").html("");
                let i=1;
                console.log(data);
                data.forEach((item,index) => {
                  $("#cont_murroby").append(`<tr>
                      <td>${i}</td>
                      <td>${item.nama}</td>
                      <td>${item.no_induk}</td>
                      <td>${item.kelas}</td>
                      <td><button class="btn btn-sm btn-icon delete-record" data-id="${item.no_induk}"><i class="mdi mdi-delete-outline mdi-20px"></i></button></td>
                    </tr>`);
                    i++;
                });
              if (data.status != '') {
                Swal.fire({
                  icon: 'success',
                  title: 'Deleted!',
                  text: 'The Record has been deleted!',
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Delete is not Allowed',
                  text: 'Permission Denied',
                  customClass: {
                    confirmButton: 'btn btn-error'
                  }
                });
              }
            },
            error: function error(_error) {
              console.log(_error);
            }
          });

          // success sweetalert
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
    $(".select2").select2({
      dropdownParent: $(this).parent()
    });
    $('#santri_murroby').submit(function(e) {
      e.preventDefault();

      var formData = new FormData(this);
      showBlock();
      $.ajax({
        data: formData,
        url: ''.concat(baseUrl).concat('pegawai/simpan_santri_murroby'),
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function success(status) {
          // sweetalert
          showUnblock();
          Swal.fire({
            icon: 'success',
            title: 'Successfully '.concat(' Updated !'),
            text: ''.concat('Santri ', ' ').concat(' Updated Successfully.'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });

          $(".select2").val();
          $("#cont_murroby").html("");
          let i=1;
          console.log(status);
          status.forEach((item,index) => {
            $("#cont_murroby").append(`<tr>
                <td>${i}</td>
                <td>${item.nama}</td>
                <td>${item.no_induk}</td>
                <td>${item.kelas}</td>
              </tr>`);
              i++;
          });
        },
        error: function error(err) {
          showUnblock();
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
  });
</script>
