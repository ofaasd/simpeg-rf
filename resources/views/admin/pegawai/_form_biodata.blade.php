<form action="javascript:void(0)" class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Form">
  @csrf
  <input type="hidden" id="id" name="id" id="{{strtolower($title)}}_id" value='{{$var['EmployeeNew']->id}}'>
  <div class="form-floating form-floating-outline mb-4">
    <input type="text" class="form-control" id="add-{{strtolower($title)}}-nama" value='{{$var['EmployeeNew']->nama}}' placeholder="Nama Pegawa; Ex : Abdul Ghofar" name="nama" />
    <label for="add-{{strtolower($title)}}-nama">Nama</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <input type="text" class="form-control" id="add-{{strtolower($title)}}-tempat_lahir" value='{{$var['EmployeeNew']->tempat_lahir}}' placeholder="tempat lahir" name="tempat_lahir" />
    <label for="add-{{strtolower($title)}}-tempat_lahir">Tempat Lahir</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <input type="date" class="form-control" id="add-{{strtolower($title)}}-tanggal_lahir" value='{{date('Y-m-d',strtotime($var['EmployeeNew']->tanggal_lahir))}}' placeholder="tanggal lahir" name="tanggal_lahir" />
    <label for="add-{{strtolower($title)}}-tanggal_lahir">Tanggal Lahir</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <select class="form-control" id="add-{{strtolower($title)}}-jenis_kelamin" name="jenis_kelamin">
      <option value='Laki-laki' {{($var['EmployeeNew']->jenis_kelamin=='Laki-laki')?"selected":""}}>Laki-laki</option>
      <option value='Perempuan' {{($var['EmployeeNew']->jenis_kelamin=='Perempuan')?"selected":""}}>Perempuan</option>
    </select>
    <label for="add-{{strtolower($title)}}-jenis_kelamin">Jenis Kelamin</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <select class="form-control" id="add-{{strtolower($title)}}-jabatan_new" name="jabatan_new">
      @foreach($var['structural'] as $jabatan)
        <option value='{{$jabatan->id}}' {{($var['EmployeeNew']->jabatan_new==$jabatan->id)?"selected":""}}>{{$jabatan->name}}</option>
      @endforeach
    </select>
    <label for="add-{{strtolower($title)}}-jabatan_new">Jabatan</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <textarea  class="form-control" id="add-{{strtolower($title)}}-alamat" placeholder="Alamat" name="alamat">{{$var['EmployeeNew']->nama}}</textarea>
    <label for="add-{{strtolower($title)}}-alamat">Alamat</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <select class="form-control" id="add-{{strtolower($title)}}-pendidikan" name="pendidikan">
      @foreach($var['Grades'] as $grade)
        <option value='{{$grade->id}}' {{($var['EmployeeNew']->pendidikan==$grade->id)?"selected":""}}>{{$grade->name}}</option>
      @endforeach
    </select>
    <label for="add-{{strtolower($title)}}-pendidikan">Pendidikan Terakhir</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <input type="date" class="form-control" id="add-{{strtolower($title)}}-pengangkatan" value='{{$var['EmployeeNew']->pengangkatan}}' placeholder="pengangkatan" name="pengangkatan" />
    <label for="add-{{strtolower($title)}}-pengangkatan">Tanggal Pengangkatan</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <input type="text" class="form-control" id="add-{{strtolower($title)}}-lembaga_induk" value='{{$var['EmployeeNew']->lembaga_induk}}' placeholder="lembaga induk" name="lembaga_induk" />
    <label for="add-{{strtolower($title)}}-lembaga_induk">Lembaga Induk</label>
  </div>
  <div class="form-floating form-floating-outline mb-4">
    <input type="text" class="form-control" id="add-{{strtolower($title)}}-lembaga_diikuti" placeholder="Lembaga yang diikuti" value='{{$var['EmployeeNew']->lembaga_diikuti}}' name="lembaga_diikuti" />
    <label for="add-{{strtolower($title)}}-lembaga_diikuti">Lembaga yang Diikuti</label>
  </div>
  <button class="btn btn-primary me-sm-3 me-1 data-submit" id='edit-record'>Submit</button>
</form>
<script>
  window.onload = () => {
    $('#edit-record').on('click',function () {

      $.ajax({
        data: $('#addNewPegawaiForm').serialize(),
        url: ''.concat(baseUrl).concat('pegawai'),
        type: 'POST',
        success: function success(status) {
          // sweetalert
          Swal.fire({
            icon: 'success',
            title: 'Successfully '.concat(status, '!'),
            text: ''.concat('Pegawai ', ' ').concat(status, ' Successfully.'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        },
        error: function error(err) {
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
  }
</script>
