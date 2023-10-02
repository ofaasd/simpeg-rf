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
      <!-- <th>Action</th> -->
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
          <!-- <td><a href="{{url('pegawai/hapus_murroby_santri/' . $santri->no_induk)}}"><span class="mdi mdi-delete"></span></a></td> -->
        </tr>
        @php $i++; @endphp
      @endforeach

  </tbody>
</table>
@endif

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    $(".datatables").DataTable();
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
          $("#cont_murroby").html();
          let i=1;
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
