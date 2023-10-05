<form action="javascript:void(0)" enctype="multipart/form-data" class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}FormKeluarga">
    @csrf
    <input type="hidden" id="id" name="id" id="{{strtolower($title)}}_id" value='{{$var['santri']->id}}'>
    <div class="form-floating form-floating-outline mb-4">
      <input type="text" class="form-control" id="add-{{strtolower($title)}}-no_induk" placeholder="No. Induk Santri" name="no_induk" required value="{{$var['santri']->no_induk}}" />
      <label for="add-{{strtolower($title)}}-no_induk">No. Induk</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="text" class="form-control" id="add-{{strtolower($title)}}-nama" placeholder="Nama Pegawa; Ex : Abdul Ghofar" name="nama" value="{{$var['santri']->nama}}" required />
      <label for="add-{{strtolower($title)}}-nama">Nama</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="text" class="form-control" id="add-{{strtolower($title)}}-nisn" placeholder="NISN" value="{{$var['santri']->nisn}}" name="nisn" />
      <label for="add-{{strtolower($title)}}-nisn">NISN</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="number" class="form-control" id="add-{{strtolower($title)}}-anak_ke" placeholder="Anak ke" value="{{$var['santri']->anak_ke}}" name="anak_ke" />
      <label for="add-{{strtolower($title)}}-anak_ke">Anak ke</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="text" class="form-control" id="add-{{strtolower($title)}}-tempat_lahir" placeholder="tempat lahir" value="{{$var['santri']->tempat_lahir}}" name="tempat_lahir" />
      <label for="add-{{strtolower($title)}}-tempat_lahir">Tempat Lahir</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="date" class="form-control" id="add-{{strtolower($title)}}-tanggal_fix" placeholder="tanggal lahir" value="{{$var['santri']->tanggal_lahir}}" name="tanggal_fix" />
      <label for="add-{{strtolower($title)}}-tanggal_fix">Tanggal Lahir</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <select class="form-control" id="add-{{strtolower($title)}}-jenis_kelamin" name="jenis_kelamin">
        <option value='L' {{($var['santri']->jenis_kelamin == 'L')?'selected':''}}>Laki-laki</option>
        <option value='P' {{($var['santri']->jenis_kelamin == 'P')?'selected':''}}>Perempuan</option>
      </select>
      <label for="add-{{strtolower($title)}}-jenis_kelamin">Jenis Kelamin</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <textarea  class="form-control" id="add-{{strtolower($title)}}-alamat" placeholder="Alamat" name="alamat">{{$var['santri']->alamat}}</textarea>
      <label for="add-{{strtolower($title)}}-alamat">Alamat</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <select class="form-control" id="add-{{strtolower($title)}}-provinsi" placeholder="provinsi" name="provinsi">
        <option value="0">---Pilih Provinsi---</option>
        @foreach($var['prov'] as $row)
        <option value="{{$row->prov_id}}" {{($var['santri']->provinsi == $row->prov_id)?'selected':''}}>{{$row->prov_name}}</option>
        @endforeach
      </select>
      <label for="add-{{strtolower($title)}}-provinsi">Provinsi</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <select class="form-control" id="add-{{strtolower($title)}}-kabkota" placeholder="kabkota" name="kabkota">
        <option value="0">---Pilih Kota---</option>
      </select>
      <label for="add-{{strtolower($title)}}-kabkota">Kab/Kota</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="text" class="form-control" id="add-{{strtolower($title)}}-kecamatan" placeholder="kecamatan" name="kecamatan" value="{{$var['santri']->kecamatan}}" />
      <label for="add-{{strtolower($title)}}-kecamatan">Kecamatan</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="text" class="form-control" id="add-{{strtolower($title)}}-kelurahan" placeholder="kelurahan" name="kelurahan" value="{{$var['santri']->kelurahan}}" />
      <label for="add-{{strtolower($title)}}-kelurahan">Kelurahan</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="text" class="form-control" id="add-{{strtolower($title)}}-kode_pos" placeholder="kode_pos" name="kode_pos" value="{{$var['santri']->kode_pos}}" />
      <label for="add-{{strtolower($title)}}-kode_pos">Kode Pos</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
      <input type="text" class="form-control" id="add-{{strtolower($title)}}-no_hp" placeholder="no_hp" name="no_hp" value="{{$var['santri']->no_hp}}" />
      <label for="add-{{strtolower($title)}}-no_hp">No. HP</label>
    </div>
    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id='edit-record'>Submit</button>
</form>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $('#addNew{{$title}}FormKeluarga').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        showBlock();
        $.ajax({
          data: formData,
          url: ''.concat(baseUrl).concat('santri/update_keluarga'),
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
              text: ''.concat('Keluarga Santri ', ' ').concat(' Updated Successfully.'),
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

      var get_kota = function get_kota(my_id, page, value) {

            //alert('masuk sini');
            //alert();
            $.ajax({
                data: {
                id: my_id
                },
                url: ''.concat(baseUrl).concat(page).concat('/get_kota'),
                type: 'POST',
                success: function success(data) {
                // sweetalert
                $('#add-{{$title}}-kabkota').html('');
                Object.keys(data).forEach(function (key) {
                    $('#add-{{$title}}-kabkota').append(
                    '<option value=' + data[key].city_id + ' '.concat('>' + data[key].city_name + '</option>')
                    );
                });

                if (value != 0) {
                    $('#add-{{$title}}-kabkota').val(value);
                }
                }
            });
        }
        $("#add-{{$title}}-provinsi").change(function() {
            get_kota($(this).val(),'{{$title}}',0);
        });
        get_kota($('#add-{{$title}}-provinsi').val(),'{{$title}}',{{$var['santri']->kabkota}});
    });

  </script>
