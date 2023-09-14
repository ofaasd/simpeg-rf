<form action="javascript:void(0)" enctype="multipart/form-data" class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}FormKeluarga">
    @csrf
    <input type="hidden" id="id" name="id" id="{{strtolower($title)}}_id" value='{{$var['santri']->id}}'>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" id="add-{{strtolower($title)}}-nik_kk" placeholder="NIK KK" name="nik_kk" value='{{$var['santri']->nik_kk}}'  />
        <label for="add-{{strtolower($title)}}-no_induk">NIK KK</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" id="add-{{strtolower($title)}}-nama_lengkap_ayah" placeholder="Nama Lengkap Ayah; Ex : Abdul Ghofar" name="nama_lengkap_ayah" value='{{$var['santri']->nama_lengkap_ayah}}' />
        <label for="add-{{strtolower($title)}}-nama_lengkap_ayah">Nama Ayah</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" id="add-{{strtolower($title)}}-pendidikan_ayah" placeholder="pendidikan ayah" name="pendidikan_ayah"  value='{{$var['santri']->pendidikan_ayah}}' />
        <label for="add-{{strtolower($title)}}-pendidikan_ayah">Pendidikan Ayah</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" id="add-{{strtolower($title)}}-pekerjaan_ayah" placeholder="pekerjaan ayah" name="pekerjaan_ayah"  value='{{$var['santri']->pekerjaan_ayah}}' />
        <label for="add-{{strtolower($title)}}-pekerjaan_ayah">Pekerjaan Ayah</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" id="add-{{strtolower($title)}}-nama_lengkap_ibu" placeholder="nama lengkap ibu; Ex : Siti Julaihah" name="nama_lengkap_ibu" value='{{$var['santri']->nama_lengkap_ibu}}' />
        <label for="add-{{strtolower($title)}}-nama_lengkap_ibu">Nama Ibu</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" id="add-{{strtolower($title)}}-pendidikan_ibu" placeholder="pendidikan ibu" name="pendidikan_ibu"  value='{{$var['santri']->pendidikan_ibu}}' />
        <label for="add-{{strtolower($title)}}-pendidikan_ibu">Pendidikan Ibu</label>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" id="add-{{strtolower($title)}}-pekerjaan_ibu" placeholder="pekerjaan ibu" name="pekerjaan_ibu"  value='{{$var['santri']->pekerjaan_ibu}}' />
        <label for="add-{{strtolower($title)}}-pekerjaan_ibu">Pekerjaan Ibu</label>
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