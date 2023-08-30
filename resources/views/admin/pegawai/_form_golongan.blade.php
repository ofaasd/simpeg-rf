@section('page-script')

@endsection

<div class="col-12">
  <div class="alert alert-primary">Riwayat golongan ruang pegawai yang dianggap sebagai status pegawai sekarang adalah riwayat yang paling akhir.</div>
  <form class="form-repeater" id='form-repeat'>
  <input type="hidden" id="employee_id" name="employee_id" id="{{strtolower($title)}}_id" value='{{$var['EmployeeNew']->id}}'>
  <input type="hidden" id='location' name="location" value="del_golru">
    <div data-repeater-list="group_a">
      @if($var['emp_golrus']->count() < 1)
      <div data-repeater-item>
        <div class="row">
          <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
            <div class="form-floating form-floating-outline">
              <!-- class jangan diubah ke nama lain -->
              <input type="hidden" class='id_key' name="id" value="0">
              <!-- Ubah untuk mengubah url ke lainnya -->
              <select id="form-repeater-1-4" class="form-select select2" name='golru'>
                @foreach($var['golrus'] as $golrus)
                  <option value="{{$golrus->id}}">{{$golrus->name}}</option>
                @endforeach
                </select>
              <label for="form-repeater-1-1">Golongan Ruang</label>
            </div>
          </div>
          <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
            <div class="form-floating form-floating-outline">
              <input type="date" name='tmt' id="form-repeater-1-2" class="form-control" />
              <label for="form-repeater-1-2">TMT</label>
            </div>
          </div>
          <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
            <div class="form-floating form-floating-outline">
              <input type="date" name="sampai" class="form-control">
              <label for="form-repeater-1-3">Sampai</label>
            </div>
          </div>
          <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
            <div class="form-floating form-floating-outline">
              <textarea class='form-control' name="keterangan"></textarea>
              <label for="form-repeater-1-4">Keterangan</label>
            </div>
          </div>
          <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
            <a class="btn btn-label-danger" data-repeater-delete>
              <i class="mdi mdi-close me-1"></i>
              <span class="align-middle">Delete</span>
            </a>
          </div>
        </div>
        <hr>
      </div>
      @else
        @foreach($var['emp_golrus']->get() as $row)
        <div data-repeater-item>
          <div class="row">
            <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
              <div class="form-floating form-floating-outline">
                <input type="hidden" class='id_key' name="id" value="{{$row->id}}">
                <select id="form-repeater-1-4" class="form-select select2" name='golru'>
                  @foreach($var['golrus'] as $golrus)
                    <option value="{{$golrus->id}}" {{($row->golru_id == $golrus->id)?"selected":""}}>{{$golrus->name}}</option>
                  @endforeach
                  </select>
                <label for="form-repeater-1-1">Golongan Ruang</label>
              </div>
            </div>
            <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
              <div class="form-floating form-floating-outline">
                <input type="date" name='tmt' id="form-repeater-1-2" class="form-control" value="{{$row->date_start}}" />
                <label for="form-repeater-1-2">TMT</label>
              </div>
            </div>
            <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
              <div class="form-floating form-floating-outline">
                <input type="date" name="sampai" class="form-control" value="{{$row->date_end}}">
                <label for="form-repeater-1-3">Sampai</label>
              </div>
            </div>
            <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
              <div class="form-floating form-floating-outline">
                <textarea class='form-control' name="keterangan">{{$row->keterangan}}</textarea>
                <label for="form-repeater-1-4">Keterangan</label>
              </div>
            </div>
            <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
              <a class="btn btn-label-danger" onclick="delete({{$row->id}})" data-repeater-delete>
                <i class="mdi mdi-close me-1"></i>
                <span class="align-middle">Delete</span>
              </a>
            </div>
          </div>
          <hr>
        </div>
        @endforeach
      @endif
    </div>
    <div class="mb-0">
      <a class="btn btn-primary" style="color:#fff" data-repeater-create>
        <i class="mdi mdi-plus me-1"></i>
        <span class="align-middle">Add</span>
      </a>
      <button type="submit" class="btn btn-success">
        <i class="mdi mdi-content-save me-1"></i>
        <span class="align-middle">Simpan</span>
      </button>
    </div>
  </form>

  </div>
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    $('#form-repeat').submit(function(e) {

      e.preventDefault();
      showBlock();
      var formData = new FormData(this);

      $.ajax({
        data: formData,
        url: ''.concat(baseUrl).concat('pegawai/store_golru'),
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function success(status) {
          // sweetalert
          showUnblock();
          Swal.fire({
            icon: 'success',
            title: 'Successfully '.concat(status.nama, ' Updated !'),
            text: ''.concat('Pegawai ', ' ').concat(status.nama, ' Updated Successfully.'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });

          //update id tiap field
          let i = 0;
          status.forEach( (item,index) => {
            console.log(item.id);
            $("input[name='group_a["+ i +"][id]']").val(item.id);
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
