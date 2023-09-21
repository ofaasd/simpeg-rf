<form action="javascript:void(0)" enctype="multipart/form-data" class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Formtahfidz">
    @csrf
    <input type="hidden" id="id" name="id" id="{{strtolower($title)}}_id" value='{{$var['santri']->id}}'>
    @if($var['santri']->tahfidz_id == 0 || empty($var['santri']->tahfidz_id))
      <div class="alert alert-danger alert-tahfidz">Anda Belum Memilih Kelompok Tahfidz. Silahkan pilih kelas kemudian simpan perubahan</div>
    @endif
    <div class="form-floating form-floating-outline mb-4">
        <select class="form-control select2" id="add-{{strtolower($title)}}-tahfidz_id" name="tahfidz_id">
          <option value=0>Pilih Kelompok Tahfidz</option>
          @foreach($var['tahfidz'] as $tahfidz)
            <option value='{{$tahfidz->id}}' {{($var['santri']->tahfidz_id == $tahfidz->id)?"selected":""}}>{{$tahfidz->name}} - {{$tahfidz->pegawai->nama}}</option>
          @endforeach
        </select>
        <label for="add-{{strtolower($title)}}-tahfidz_id">Tahfidz</label>
    </div>
    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id='edit-record'>Submit</button>
</form>

  <div class="card" id="card-list-tahfidz">
    <div class="card-header">
      <p>List Teman Tahfidz (<span id='info_tahfidz'>{{$var['curr_tahfidz']->name  ?? ""}} - {{$var['curr_tahfidz']->pegawai->nama  ?? ""}}</span>)</p>
    </div>
    <div class="card-content" id="">
      <table class="table table-stripped">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>No. Induk</th>
          </tr>
        </thead>
        <tbody id='teman_tahfidz'>

        </tbody>
      </table>
    </div>
  </div>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      const get_teman_tahfidz = (my_id,page) => {
        if($('#add-{{strtolower($title)}}-tahfidz_id').val() == 0){
          $("#card-list-tahfidz").hide();
        }else{
          $("#card-list-tahfidz").show();
          $(".alert-tahfidz").hide();
        }
        $.ajax({
          data: {
          id: my_id
          },
          url: ''.concat(baseUrl).concat(page).concat('/teman_tahfidz'),
          type: 'POST',
          success: function success(data) {
            // sweetalert
            $('#teman_tahfidz').html('');
            let $i = 1;
            Object.keys(data).forEach(function (key) {
                $('#teman_tahfidz').append(
                `<tr><td>${$i}</td><td>${data[key].nama}</td><td>${data[key].kelas}</td><td>${data[key].no_induk}</td></tr>`
                );
                $i++;
            });
          }
        });
      }
      $('#addNew{{$title}}Formtahfidz').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        showBlock();
        $.ajax({
          data: formData,
          url: ''.concat(baseUrl).concat('santri/update_tahfidz'),
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function success(status) {
            // sweetalert
            showUnblock();
            $('#info_tahfidz').html($("#add-{{strtolower($title)}}-tahfidz_id option:selected").text());
            get_teman_tahfidz($("#add-{{strtolower($title)}}-tahfidz_id").val(),'{{$title}}' )
            Swal.fire({
              icon: 'success',
              title: 'Successfully '.concat(' Updated !'),
              text: ''.concat('tahfidz Santri ', ' ').concat(' Updated Successfully.'),
              customClass: {
                confirmButton: 'btn btn-success'
              }
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

        get_teman_tahfidz($("#add-{{strtolower($title)}}-tahfidz_id").val(),'{{$title}}' )
    });

  </script>
