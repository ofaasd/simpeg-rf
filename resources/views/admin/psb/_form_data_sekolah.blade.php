<div class="content-header mb-3">
  <h6 class="mb-0">Data Sekolah Asal</h6>
  <small>Input Data Sekolah Asal</small>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
          <select name="jenjang" class="form-control col-md-2" id="jenjang">
            <option value=1>TK</option>
            <option value=2>RA</option>
            <option value=3>SD/MI</option>

          </select>
          <label for="jenjang">Asal Jenjang</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
        <input type="text" name="kelas" class="form-control col-md-6" value="" id="kelas" placeholder="Cth: TK B/SD Kelas 3">
          <label for="kelas">Kelas Terakhir</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
          <input type="text" name="nama_sekolah" class="form-control col-md-6" value="" id="nama_sekolah" placeholder="Cth: TK Tunas Bakti">
          <label for="nama_sekolah">Nama Sekolah</label>
        </div>
      </div>

    </div>
  </div>
  <div class="col-sm-6">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
          <input type="text" name="nss" class="form-control col-md-6" id="nss" value="" placeholder="">
          <label for="nss">NSM/NSS</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
          <input type="text" name="npsn" class="form-control col-md-6" id="npsn" value="" placeholder="">
          <label for="npsn">NPSN</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
        <input type="text" name="nisn" class="form-control col-md-6" id="nisn" value="" placeholder="">
          <label for="nisn">NISN</label>
        </div>
      </div>

    </div>
  </div>
  <div class="col-md-12">
      <hr>
      <h4>Ukuran Badan (Untuk Seragam)</h4>
      <br>
  </div>
  <div class="col-sm-6">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
            <input type="number" name="tinggi_badan" class="form-control col-md-12" id="tinggi_badan" value="{{$psb_seragam->tinggi_badan??''}}" placeholder="Cth: 180 CM">
            <label for="tinggi_badan">Tinggi Badan</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
            <input type="number" name="berat_badan" class="form-control col-md-12" id="berat_badan" value="{{$psb_seragam->berat_badan??''}}" placeholder="Cth: 80 KG">
            <label for="tinggi_badan">Berat Badan</label>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="row g-4">
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
          <input type="number" name="lingkar_dada" class="form-control col-md-12" id="lingkar_dada" value="{{$psb_seragam->lingkar_dada??''}}" placeholder="Cth: 50 CM">
          <label for="lingkar_dada">Lingkar Dada</label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-floating form-floating-outline col-md-12">
          <input type="number" name="lingkar_pinggul" class="form-control col-md-12" id="lingkar_pinggul" value="{{$psb_seragam->lingkar_pinggul??''}}" placeholder="Cth: 50 CM">
          <label for="lingkar_pinggul">Lingkar Pinggul</label>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 d-flex justify-content-between" style="margin-top:20px">
    <button class="btn btn-outline-secondary btn-prev"> <i class="mdi mdi-arrow-left me-sm-1 me-0"></i>
      <span class="align-middle d-sm-inline-block d-none">Previous</span>
    </button>
    <button class="btn btn-primary btn-next btn-submit">Submit</button>
  </div>
</div>
