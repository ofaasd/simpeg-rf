<form action="javascript:void(0)" enctype="multipart/form-data" class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Formkelas">
    @csrf
    <input type="hidden" id="id" name="id" id="{{strtolower($title)}}_id" value='{{$var['santri']->id}}'>
    @if($var['santri']->kelas == 0 || empty($var['santri']->kamar_id))
      <div class="alert alert-danger alert-kelas">Anda Belum Memilih Kelas. Silahkan pilih kelas kemudian simpan perubahan</div>
    @endif
    <div class="form-floating form-floating-outline mb-4">
        <select class="form-control select2" id="add-{{strtolower($title)}}-kelas_id" name="kelas_id">
          <option value=0>Pilih kelas</option>
          @foreach($var['kelas'] as $kelas)
            <option value='{{$kelas->code}}' {{($var['santri']->kelas == $kelas->code)?"selected":""}}>{{$kelas->code}} - {{$kelas->name}} - {{$kelas->pegawai->nama}}</option>
          @endforeach
        </select>
        <label for="add-{{strtolower($title)}}-kelas_id">kelas</label>
    </div>
    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id='edit-record'>Submit</button>
</form>

  <div class="card" id="card-list-kelas">
    <div class="card-header">
      <p>List Teman kelas (<span id='info_kelas'>{{$var['curr_kelas']->code ?? ""}} - {{$var['curr_kelas']->name  ?? ""}} - {{$var['curr_kelas']->pegawai->nama  ?? ""}}</span>)</p>
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
        <tbody id='teman_kelas'>

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
      const get_teman_kelas = (my_id,page) => {
        if($('#add-{{strtolower($title)}}-kelas_id').val() == 0){
          $(".card-list-kelas").hide();
        }else{
          $(".card-list-kelas").show();
          $(".alert-kelas").hide();
        }
        $.ajax({
          data: {
          id: my_id
          },
          url: ''.concat(baseUrl).concat(page).concat('/teman_kelas'),
          type: 'POST',
          success: function success(data) {
            // sweetalert
            $('#teman_kelas').html('');
            let $i = 1;
            Object.keys(data).forEach(function (key) {
                $('#teman_kelas').append(
                `<tr><td>${$i}</td><td>${data[key].nama}</td><td>${data[key].kelas}</td><td>${data[key].no_induk}</td></tr>`
                );
                $i++;
            });
          }
        });
      }
      $('#addNew{{$title}}Formkelas').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        showBlock();
        $.ajax({
          data: formData,
          url: ''.concat(baseUrl).concat('santri/update_kelas'),
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function success(status) {
            // sweetalert
            showUnblock();
            $('#info_kelas').html($("#add-{{strtolower($title)}}-kelas_id option:selected").text());
            get_teman_kelas($("#add-{{strtolower($title)}}-kelas_id").val(),'{{$title}}' )
            Swal.fire({
              icon: 'success',
              title: 'Successfully '.concat(' Updated !'),
              text: ''.concat('kelas Santri ', ' ').concat(' Updated Successfully.'),
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

        get_teman_kelas($("#add-{{strtolower($title)}}-kelas_id").val(),'{{$title}}' )
    });

  </script>
