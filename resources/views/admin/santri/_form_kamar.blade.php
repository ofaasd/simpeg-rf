<form action="javascript:void(0)" enctype="multipart/form-data" class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}FormKamar">
    @csrf
    <input type="hidden" id="id" name="id" id="{{strtolower($title)}}_id" value='{{$var['santri']->id}}'>
    @if($var['santri']->kamar_id == 0)
      <div class="alert alert-danger">Anda Belum Memilih Kamar. Silahkan pilih kelas kemudian simpan perubahan</div>
    @endif
    <div class="form-floating form-floating-outline mb-4">
        <select class="form-control select2" id="add-{{strtolower($title)}}-kamar_id" name="kamar_id">
          <option value=0>Pilih Kamar</option>
          @foreach($var['kamar'] as $kamar)
            <option value='{{$kamar->id}}' {{($var['santri']->kamar_id == $kamar->id)?"selected":""}}>{{$kamar->code}} - {{$kamar->name}} - {{$kamar->pegawai->nama}}</option>
          @endforeach
        </select>
        <label for="add-{{strtolower($title)}}-kamar_id">Kamar</label>
    </div>    
    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id='edit-record'>Submit</button>
</form>

  <div class="card card-list">
    <div class="card-header">
      <p>List Teman kamar (<span id='info_kamar'>{{$var['curr_kamar']->code ?? ""}} - {{$var['curr_kamar']->name  ?? ""}} - {{$var['curr_kamar']->pegawai->nama  ?? ""}}</span>)</p>
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
        <tbody id='teman_kamar'>

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
      const get_teman_kamar = (my_id,page) => {
        if($('#add-{{strtolower($title)}}-kamar_id').val() == 0){
          $(".card-list").hide();
        }else{
          $(".card-list").show();
          $(".alert").hide();
        }
        $.ajax({
          data: {
          id: my_id
          },
          url: ''.concat(baseUrl).concat(page).concat('/teman_kamar'),
          type: 'POST',
          success: function success(data) {
            // sweetalert
            $('#teman_kamar').html('');
            let $i = 1;
            Object.keys(data).forEach(function (key) {
                $('#teman_kamar').append(
                `<tr><td>${$i}</td><td>${data[key].nama}</td><td>${data[key].kelas}</td><td>${data[key].no_induk}</td></tr>`
                );
                $i++;
            });
          }
        });
      }
      $('#addNew{{$title}}FormKamar').submit(function(e) {
        e.preventDefault();
  
        var formData = new FormData(this);
        showBlock();
        $.ajax({
          data: formData,
          url: ''.concat(baseUrl).concat('santri/update_kamar'),
          type: 'POST',
          cache: false,
          contentType: false,
          processData: false,
          success: function success(status) {
            // sweetalert
            showUnblock();
            $('#info_kamar').html($("#add-{{strtolower($title)}}-kamar_id option:selected").text());
            get_teman_kamar($("#add-{{strtolower($title)}}-kamar_id").val(),'{{$title}}' )
            Swal.fire({
              icon: 'success',
              title: 'Successfully '.concat(' Updated !'),
              text: ''.concat('Kamar Santri ', ' ').concat(' Updated Successfully.'),
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
        
        get_teman_kamar($("#add-{{strtolower($title)}}-kamar_id").val(),'{{$title}}' )
    });
    
  </script>