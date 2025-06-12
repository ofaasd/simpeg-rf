@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-kurban.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('js/laravel-academic-management.js')}}"></script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
    <textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
    <input type="hidden" name="page" id='page' value='kurban'>
    <input type="hidden" name="title" id='title' value='Kurban'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Nama</th>
          <th>Jumlah</th>
          <th>Jenis Kurban</th>
          <th>Atas Nama</th>
          <th>Tanggal</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>

  <!-- Offcanvas to add new {{strtolower($title)}} -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAdd{{$title}}" aria-labelledby="offcanvasAdd{{$title}}Label">
    <div class="offcanvas-header">
      <h5 id="offcanvasAdd{{$title}}Label" class="offcanvas-title">Add {{$title}}</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-{{strtolower($title)}} pt-0" id="addNew{{$title}}Form">
        <input type="hidden" name="id" id="{{strtolower($title)}}_id">

       <div class="form-floating form-floating-outline mb-4">
        <select class="form-select select2" id="add-{{ strtolower($title) }}-id_santri" name="id_santri" required>
          <option selected disabled>Pilih Santri</option>
          @foreach($santri as $s)
            <option value="{{ $s->id }}">{{ $s->nama }}</option>
          @endforeach
        </select>
        <label for="add-{{ strtolower($title) }}-id_santri">Santri</label>
      </div>
        <div class="form-floating form-floating-outline mb-4">
          <select class="form-select" id="add-{{ strtolower($title) }}-jenis" name="jenis" required>
            <option selected disabled>Pilih Jenis</option>
            <option value="1">Sapi</option>
            <option value="2">Kambing</option>
            <option value="3">Domba</option>
            <option value="4">Lainnya</option>
          </select>
          <label for="add-{{ strtolower($title) }}-jenis">Jenis Kurban</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="number" class="form-control" id="add-{{strtolower($title)}}-jumlah" placeholder="Jumlah" name="jumlah" required />
          <label for="add-{{strtolower($title)}}-jumlah">Jumlah</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="date" class="form-control" id="add-{{strtolower($title)}}-tanggal" placeholder="Tanggal Kurban" name="tanggal" required />
          <label for="add-{{strtolower($title)}}-tanggal">Tanggal</label>
        </div>

        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{ strtolower($title) }}-atas_nama" name="atas_nama" placeholder="Atas Nama">
          <label for="add-{{ strtolower($title) }}-atas_nama">Atas Nama</label>
        </div>
         <!-- Input Upload Gambar -->
        <div class="mb-4">
          <label for="add-{{ strtolower($title) }}-foto" class="form-label">Upload Foto</label>
          <input class="form-control" type="file" id="add-{{ strtolower($title) }}-foto" name="foto" accept="image/*" onchange="previewImage(event)">
          <img id="preview-{{ strtolower($title) }}-foto" src="#" alt="Preview Foto" style="display:none; margin-top:10px; max-height:150px; object-fit:contain;" />
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
<script>
function previewImage(event) {
  const input = event.target;
  const preview = document.getElementById('preview-{{ strtolower($title) }}-foto');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    }
    reader.readAsDataURL(input.files[0]);
  } else {
    preview.src = '#';
    preview.style.display = 'none';
  }
}
</script>

@endsection
