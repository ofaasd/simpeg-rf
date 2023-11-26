<section>
    <form action="javascript:void(0)" enctype='multipart/form-data' method="post" id="form_asal_sekolah">
    @csrf
        <input type="hidden" name="psb_asal_sekolah" value="{{$psb_asal->id}}" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
              <div class="row g-4">
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <select name="jenjang" class="form-control col-md-4" id="jenjang">
                          <option value=1 {{(!empty($psb_asal) && $psb_asal->jenjang == 1)?'selected':''}}>TK</option>
                          <option value=2 {{(!empty($psb_asal) && $psb_asal->jenjang == 2)?'selected':''}}>RA</option>
                          <option value=3 {{(!empty($psb_asal) && $psb_asal->jenjang == 3)?'selected':''}}>SD/MI</option>

                      </select>
                      <label for="jenjang">Dari</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="kelas" class="form-control col-md-12" value="{{$psb_asal->kelas??''}}" id="kelas" placeholder="Cth: TK B/SD Kelas 3">
                      <label for="kelas">Kelas Terakhir</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="nama_sekolah" class="form-control col-md-12" value="{{$psb_asal->nama_sekolah??''}}" id="nama_sekolah" placeholder="Cth: TK Tunas Bakti">
                      <label for="nama_sekolah">Nama Sekolah</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <button button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row g-4">
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="nss" class="form-control col-md-12" id="nss" value="{{$psb_asal->nss??''}}" placeholder="">
                      <label for="nss">NSM/NSS</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="npsn" class="form-control col-md-12" id="npsn" value="{{$psb_asal->npsn??''}}" placeholder="">
                      <label for="npsn">NPSN</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="nisn" class="form-control col-md-12" id="nisn" value="{{$psb_asal->nisn??''}}" placeholder="">
                      <label for="nisn">NISN</label>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </form>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        //let data = "";
        const url_save = "{{URL::to('psb/update_data_asal_sekolah')}}";
        $("#form_asal_sekolah").submit(function(e){
            e.preventDefault();
            //alert("asdasd");
            let data = new FormData(this);
            //alert(data);
            $.ajax({
                method:"POST",
                url: url_save,
                processData: false,
                contentType: false,
                data : data,
                success : function(data){
                    data = JSON.parse(data);
                    if(data[0].code == 1){
                        Swal.fire({
                            title: 'success!',
                            text: 'Data Berhasil Disimpan',
                            icon: 'success',
                            confirmButtonText: 'Tutup',
                            timer : 2000,
                            customClass: {
                              confirmButton: 'btn btn-success'
                            }
                        });

                    }else{
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Data Gagal Disimpan',
                            icon: 'error',
                            confirmButtonText: 'Tutup',
                            timer : 2000,
                            customClass: {
                              confirmButton: 'btn btn-success'
                            }
                        })
                    }
                }
            });

        })
    });
</script>
