<section>
    <form action="javascript:void(0)" enctype='multipart/form-data' method="post" id="form_edit_walsan">
    @csrf
        <input type="hidden" name="id" value="{{$psb_peserta->id}}">
        <input type="hidden" name="psb_wali_id" value="{{$psb_wali->id}}" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
              <div class="row g-4">
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="nama_ayah" value="{{$psb_wali->nama_ayah??''}}" class="form-control" id="nama_ayah">
                      <label for="nama_ayah">Nama Ayah</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <select name="pendidikan_ayah" class="form-control" id="pendidikan_ayah">
                          <option value=1 {{(!empty($psb_wali) && $psb_wali->pendidikan_ayah == 1)?'selected':''}}>S2/S3</option>
                          <option value=2 {{(!empty($psb_wali) && $psb_wali->pendidikan_ayah == 2)?'selected':''}}>S1</option>
                          <option value=3 {{(!empty($psb_wali) && $psb_wali->pendidikan_ayah == 3)?'selected':''}}>Diploma</option>
                          <option value=4 {{(!empty($psb_wali) && $psb_wali->pendidikan_ayah == 4)?'selected':''}}>SMA/MA</option>
                          <option value=5 {{(!empty($psb_wali) && $psb_wali->pendidikan_ayah == 5)?'selected':''}}>SMP/MTs</option>
                          <option value=6 {{(!empty($psb_wali) && $psb_wali->pendidikan_ayah == 6)?'selected':''}}>SD/MI</option>
                      </select>
                      <label for="pendidikan_ayah">Pendidikan Ayah</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="pekerjaan_ayah" value="{{$psb_wali->pekerjaan_ayah??''}}" class="form-control" id="pekerjaan_ayah">
                      <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="no_telp" class="form-control" value="{{$psb_wali->no_telp??''}}" id="no_telp" />
                      <label for="no_telp">No. Telpon</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row g-4">
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="nama_ibu" class="form-control" value="{{$psb_wali->nama_ibu??''}}" id="nama_ibu">
                      <label for="nama_ibu">Nama ibu</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <select name="pendidikan_ibu" class="form-control" id="pendidikan_ibu">
                          <option value=1 {{(!empty($psb_wali) && $psb_wali->pendidikan_ibu == 1)?'selected':''}}>S2/S3</option>
                          <option value=2 {{(!empty($psb_wali) && $psb_wali->pendidikan_ibu == 2)?'selected':''}}>S1</option>
                          <option value=3 {{(!empty($psb_wali) && $psb_wali->pendidikan_ibu == 3)?'selected':''}}>Diploma</option>
                          <option value=4 {{(!empty($psb_wali) && $psb_wali->pendidikan_ibu == 4)?'selected':''}}>SMA/MA</option>
                          <option value=5 {{(!empty($psb_wali) && $psb_wali->pendidikan_ibu == 5)?'selected':''}}>SMP/MTs</option>
                          <option value=6 {{(!empty($psb_wali) && $psb_wali->pendidikan_ibu == 6)?'selected':''}}>SD/MI</option>
                      </select>
                      <label for="pendidikan_ibu">Pendidikan ibu</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating form-floating-outline col-md-12">

                      <input type="text" name="pekerjaan_ibu" value="{{$psb_wali->pekerjaan_ibu??''}}" class="form-control" id="pekerjaan_ibu">
                      <label for="pekerjaan_ibu">Pekerjaan ibu</label>
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
        const url_save = "{{URL::to('psb/update_data_walsan')}}";
        $("#form_edit_walsan").submit(function(e){
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
