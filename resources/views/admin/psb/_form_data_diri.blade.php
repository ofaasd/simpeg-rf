<div class="content-header mb-3">
  <h6 class="mb-0">Data Pribadi</h6>
  <small>Masukan Detail Data Pribadi Santri</small>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
          <input type="text" name="nik" id="nik" class="form-control" placeholder="johndoe" />
          <label for="nik">NIK</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline">
          <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="ex : Abdul Ghofar" aria-label="nama.lengkap" />
          <label for="nama_lengkap">Nama Lengkap</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline">
          <input type="text" name="nama_panggilan" class="form-control" value="" id="nama_panggilan">
          <label for="nama_panggilan">Nama Panggilan</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline">
          <p class="text-light fw-semibold">Jenis Kelamin</p>
          <div class="form-check form-check-inline mt-3" style="margin-left:10px;">
            <input type="radio" name="jenis_kelamin" class="form-check-input"  value='L' id="laki-laki">
            <label class="form-check-label" for="laki-laki">Laki-laki</label>
          </div>
          <div class="form-check form-check-inline mt-3" style="margin-left:10px;">
            <input type="radio" name="jenis_kelamin" class="form-check-input" value='P' id="perempuan">
            <label class="form-check-label" for="perempuan">Perempuan</label>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="text" name="tempat_lahir" class="form-control" value="" id="tempat_lahir">
              <label for="tempat_lahir">Tempat Lahir</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating form-floating-outline">
              <input type="date" name="tanggal_lahir" class="form-control" value="" id="tanggal_lahir">
              <label for="tanggal_lahir">Tanggal Lahir</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline">
          <input type="text" name="no_hp" class="form-control" value="" id="no_hp">
          <label for="no_hp">No. HP (WA)</label>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <div class="form-floating form-floating-outline col-md-12">
              <input type="number" name="jumlah_saudara" class="form-control" id="jumlah_saudara" placeholder="jumlah saudara kandung">
              <label for="jumlah_saudara">Jumlah Saudara Kandung</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating form-floating-outline col-md-12">
              <input type="number" name="anak_ke" class="form-control" id="anak_ke">
              <label for="anak_ke">Anak ke</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline">
          <textarea class="form-control" name="alamat" id="alamat"></textarea>
          <label for="alamat">Alamat Lengkap</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <div class="form-floating form-floating-outline">
              <select class="form-control select2" name="provinsi" id="provinsi">
                <option value=0>--Pilih Provinsi--</option>
                @foreach ($provinsi as $row)
                  <option value="{{$row->id_provinsi}}" >{{$row->nama_provinsi }}</option>
                @endforeach
              </select>
              <label for="provinsi">Provinsi</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating form-floating-outline ">
              <select class="form-control" name="kota" id="kota">
                <option value=0>--Pilih Kota--</option>
              </select>
              <label for="kota">Kota</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <div class="form-floating form-floating-outline ">
              <select class="form-control" name="kecamatan" id="kecamatan">
                <option value=0>--Pilih Kecamatan--</option>
              </select>
              <label for="kecamatan">Kecamatan</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating form-floating-outline ">
              <select class="form-control" name="kelurahan" id="kelurahan">
                <option value=0>--Pilih Kelurahan--</option>
              </select>
              <label for="kelurahan">Kelurahan</label>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline">
          <input type="text" name="kode_pos" class="form-control" value="" id="kode_pos">
          <label for="kode_pos">Kode Pos</label>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 d-flex justify-content-between" style="margin-top:20px">
    <button class="btn btn-outline-secondary btn-prev" disabled> <i class="mdi mdi-arrow-left me-sm-1 me-0"></i>
      <span class="align-middle d-sm-inline-block d-none">Previous</span>
    </button>
    <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="mdi mdi-arrow-right"></i></button>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
  //
  $("#provinsi").on("change",function(){
    $.ajax({
      data: {
          prov_id: $("#provinsi").val(),
          },
      url: ''.concat(baseUrl).concat("psb").concat('/get_kota'),
      method: 'POST',
      success: function success(data) {
      // sweetalert
      $('#kota').html('');
      data = JSON.parse(data);
      data.forEach(function (item){
        $("#kota").append('<option value="' + item.id_kota_kab + '">' + item.nama_kota_kab + '</option>');
      });
      $("#kota").select2();
      }
    });
  });
  $("#kota").on("change",function(){
    $.ajax({
      data: {
            "prov_id" : $("#provinsi").val(),
            "kota_id" : $(this).val()
          },
      url: ''.concat(baseUrl).concat("psb").concat('/get_kecamatan'),
      method: 'POST',
      success: function success(data) {
      // sweetalert
      $('#kecamatan').html('');
      data = JSON.parse(data);
      data.forEach(function (item){
        $("#kecamatan").append('<option value="' + item.id_kecamatan + '">' + item.nama_kecamatan + '</option>');
      });
      $("#kecamatan").select2();
      }
    });
  });
  $("#kecamatan").on("change",function(){
    $.ajax({
      data: {
            "prov_id" : $("#provinsi").val(),
            "kota_id" : $("#kota").val(),
            "kecamatan_id" : $(this).val()
          },
      url: ''.concat(baseUrl).concat("psb").concat('/get_kelurahan'),
      method: 'POST',
      success: function success(data) {
      // sweetalert
      $('#kelurahan').html('');
      data = JSON.parse(data);
      data.forEach(function (item){
        $("#kelurahan").append('<option value="' + item.id_kelurahan + '">' + item.nama_kelurahan + '</option>');
      });
      $("#kelurahan").select2();
      }
    });
  });

});
</script>
