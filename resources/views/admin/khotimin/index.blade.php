@extends('layouts/layoutMaster')

@section('title', 'Data Khotimin - SIM Pondok')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Header Card -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card bg-label-primary border-0 shadow-sm">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-md bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center">
              <i class="mdi mdi-book-check-outline mdi-24px"></i>
            </div>
            <div>
              <h5 class="mb-0 text-primary fw-bold">Data Khotimin Santri & Alumni</h5>
              <small class="text-muted">Pencatatan data santri aktif maupun alumni yang telah menyelesaikan khataman Al-Qur'an / Kitab.</small>
            </div>
          </div>
          <div class="d-flex align-items-center flex-wrap gap-2">
            <button type="button" class="btn btn-outline-success" id="btnOpenExportModal">
              <i class="mdi mdi-file-excel-outline me-1"></i> Export Data
            </button>
            <button type="button" class="btn btn-outline-primary" id="btnOpenImportModal">
              <i class="mdi mdi-file-upload-outline me-1"></i> Import Data
            </button>
            <button type="button" class="btn btn-primary" id="btnOpenAddModal">
              <i class="mdi mdi-plus me-1"></i> Tambah Khotimin
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistik Cards -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div class="content-left">
              <span class="text-muted small">Total Seluruh Khotimin</span>
              <div class="d-flex align-items-center my-1">
                <h4 class="mb-0 me-2 fw-bold" id="statTotalKhotimin">{{ $totalKhotimin }}</h4>
                <span class="badge bg-label-primary">Santri</span>
              </div>
              <small class="text-muted">Akumulasi santri aktif & alumni</small>
            </div>
            <div class="avatar avatar-md">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="mdi mdi-account-group mdi-24px"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div class="content-left">
              <span class="text-muted small">Khotimin Tahun Ajaran Aktif</span>
              <div class="d-flex align-items-center my-1">
                <h4 class="mb-0 me-2 fw-bold text-success" id="statKhotiminAktif">{{ $khotiminTahunIni }}</h4>
                <span class="badge bg-label-success">Santri</span>
              </div>
              <small class="text-muted">
                {{ $activeTa ? ($activeTa->awal . '/' . $activeTa->akhir . ' - Smst ' . $activeTa->jenis) : 'Tidak ada TA aktif' }}
              </small>
            </div>
            <div class="avatar avatar-md">
              <span class="avatar-initial rounded bg-label-success">
                <i class="mdi mdi-calendar-check mdi-24px"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-12 col-xl-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div class="content-left">
              <span class="text-muted small">Khotimin Status Alumni</span>
              <div class="d-flex align-items-center my-1">
                <h4 class="mb-0 me-2 fw-bold text-info" id="statKhotiminAlumni">{{ $totalAlumniKhotimin }}</h4>
                <span class="badge bg-label-info">Alumni</span>
              </div>
              <small class="text-muted">Arsip lulusan terdata</small>
            </div>
            <div class="avatar avatar-md">
              <span class="avatar-initial rounded bg-label-info">
                <i class="mdi mdi-school-outline mdi-24px"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Card -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header border-bottom py-3">
      <h6 class="card-title mb-0 fw-bold"><i class="mdi mdi-filter-variant me-1 text-primary"></i> Filter Data</h6>
    </div>
    <div class="card-body pt-3">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label small fw-semibold" for="filterTahunAjaran">Tahun Ajaran</label>
          <select id="filterTahunAjaran" class="form-select form-select-sm">
            <option value="">-- Semua Tahun Ajaran --</option>
            @foreach($tahunAjaranList as $ta)
              <option value="{{ $ta->id }}" {{ ($activeTa && $activeTa->id == $ta->id) ? 'selected' : '' }}>
                {{ $ta->awal }}/{{ $ta->akhir }} (Semester {{ $ta->jenis }}) {{ $ta->is_aktif ? '★ [Aktif]' : '' }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold" for="filterKelas">Kelas</label>
          <select id="filterKelas" class="form-select form-select-sm">
            <option value="">-- Semua Kelas --</option>
            <optgroup label="Kelas A">
              <option value="1A">1A</option>
              <option value="2A">2A</option>
              <option value="3A">3A</option>
              <option value="4A">4A</option>
              <option value="5A">5A</option>
              <option value="6A">6A</option>
            </optgroup>
            <optgroup label="Kelas B">
              <option value="1B">1B</option>
              <option value="2B">2B</option>
              <option value="3B">3B</option>
              <option value="4B">4B</option>
              <option value="5B">5B</option>
              <option value="6B">6B</option>
            </optgroup>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold" for="filterTipe">Status Santri</label>
          <select id="filterTipe" class="form-select form-select-sm">
            <option value="">-- Semua Status --</option>
            <option value="santri">Santri Aktif</option>
            <option value="alumni">Alumni</option>
          </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="btnResetFilter">
            <i class="mdi mdi-refresh me-1"></i> Reset
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Table Card -->
  <div class="card shadow-sm border-0">
    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
      <h6 class="card-title mb-0 fw-bold"><i class="mdi mdi-table me-1 text-primary"></i> Daftar Khotimin</h6>
    </div>
    <div class="card-datatable table-responsive">
      <table class="table table-hover table-striped datatables-khotimin w-100">
        <thead class="table-light">
          <tr>
            <th width="4%">No</th>
            <th width="12%">No Induk</th>
            <th>Nama Santri</th>
            <th width="11%">Status</th>
            <th width="8%">Kelas</th>
            <th width="16%">Tahun Ajaran</th>
            <th width="12%">Tanggal Khatam</th>
            <th>Keterangan</th>
            <th width="10%" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Form Tambah / Edit Khotimin -->
<div class="modal fade" id="modalKhotimin" tabindex="-1" aria-labelledby="modalKhotiminTitle" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formKhotimin">
        @csrf
        <input type="hidden" name="id" id="khotimin_id">
        <div class="modal-header bg-label-primary py-3">
          <h5 class="modal-title fw-bold" id="modalKhotiminTitle">
            <i class="mdi mdi-plus-box-outline me-1"></i> Tambah Data Khotimin
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-3">
          <!-- Pilih Santri / Alumni via No Induk -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="modal_selected_santri">
              Santri / Alumni <span class="text-danger">*</span>
            </label>
            <select class="form-select select2-santri" id="modal_selected_santri" name="selected_santri" required style="width: 100%;">
              <option value="">-- Cari No Induk atau Nama Santri / Alumni --</option>
              <optgroup label="Santri Aktif (santri_detail)">
                @foreach($santriList as $s)
                  <option value="santri:{{ $s->no_induk }}" data-kode="{{ $s->no_induk }}" data-kelas="{{ strtoupper($s->kelas ?? '') }}">
                    {{ $s->no_induk }} - {{ $s->nama }} [Santri Aktif - Kelas {{ strtoupper($s->kelas ?? '-') }}]
                  </option>
                @endforeach
              </optgroup>
              <optgroup label="Alumni (tb_alumni_santri_detail)">
                @foreach($alumniList as $a)
                  <option value="alumni:{{ $a->no_induk }}" data-kode="{{ $a->no_induk }}" data-kelas="{{ strtoupper($a->kelas ?? '') }}">
                    {{ $a->no_induk }} - {{ $a->nama }} [Alumni]
                  </option>
                @endforeach
              </optgroup>
            </select>
            <div class="invalid-feedback">Silakan pilih santri atau alumni.</div>
          </div>

          <!-- Tahun Ajaran -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="modal_id_tahun_ajaran">
              Tahun Ajaran <span class="text-danger">*</span>
            </label>
            <select class="form-select" id="modal_id_tahun_ajaran" name="id_tahun_ajaran" required>
              <option value="">-- Pilih Tahun Ajaran --</option>
              @foreach($tahunAjaranList as $ta)
                <option value="{{ $ta->id }}" {{ ($activeTa && $activeTa->id == $ta->id) ? 'selected' : '' }}>
                  {{ $ta->awal }}/{{ $ta->akhir }} (Semester {{ $ta->jenis }}) {{ $ta->is_aktif ? '★ [Aktif]' : '' }}
                </option>
              @endforeach
            </select>
            <div class="invalid-feedback">Silakan pilih tahun ajaran.</div>
          </div>

          <div class="row g-2">
            <!-- Kelas: Dropdown 1A - 6A & 1B - 6B -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold" for="modal_kelas">
                Kelas <span class="text-danger">*</span>
              </label>
              <select class="form-select" id="modal_kelas" name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <optgroup label="Kelas A">
                  <option value="1A">1A</option>
                  <option value="2A">2A</option>
                  <option value="3A">3A</option>
                  <option value="4A">4A</option>
                  <option value="5A">5A</option>
                  <option value="6A">6A</option>
                </optgroup>
                <optgroup label="Kelas B">
                  <option value="1B">1B</option>
                  <option value="2B">2B</option>
                  <option value="3B">3B</option>
                  <option value="4B">4B</option>
                  <option value="5B">5B</option>
                  <option value="6B">6B</option>
                </optgroup>
              </select>
              <div class="invalid-feedback">Kelas wajib dipilih.</div>
            </div>

            <!-- Tanggal Khatam -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold" for="modal_tanggal_khatam">
                Tanggal Khatam <span class="text-danger">*</span>
              </label>
              <input type="date" class="form-control" id="modal_tanggal_khatam" name="tanggal_khatam" value="{{ date('Y-m-d') }}" required />
              <div class="invalid-feedback">Tanggal khatam wajib diisi.</div>
            </div>
          </div>

          <!-- Keterangan -->
          <div class="mb-2">
            <label class="form-label fw-semibold" for="modal_keterangan">Keterangan / Catatan</label>
            <textarea class="form-control" id="modal_keterangan" name="keterangan" rows="2" placeholder="Catatan tambahan (opsional)..."></textarea>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmitKhotimin">
            <i class="mdi mdi-content-save-outline me-1"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Filter & Export Khotimin -->
<div class="modal fade" id="modalExportKhotimin" tabindex="-1" aria-labelledby="modalExportTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('khotimin.export') }}" method="GET" target="_blank">
        <div class="modal-header bg-label-success py-3">
          <h5 class="modal-title fw-bold" id="modalExportTitle">
            <i class="mdi mdi-file-excel-outline me-1"></i> Export Data Khotimin
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-3">
          <p class="text-muted small mb-3">
            Tentukan kriteria filter untuk mendownload data dalam format Excel (.xlsx). Anda dapat memilih periode Tahun Ajaran tertentu atau rentang tanggal khatam.
          </p>

          <!-- Filter Status Santri / Alumni -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="export_tipe_santri">Status Santri / Alumni</label>
            <select class="form-select" id="export_tipe_santri" name="tipe_santri">
              <option value="all">Semua (Santri Aktif & Alumni)</option>
              <option value="alumni" selected>Khusus Alumni</option>
              <option value="santri">Khusus Santri Aktif</option>
            </select>
          </div>

          <!-- Filter Periode Tahun Ajaran -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="export_id_tahun_ajaran">Periode Tahun Ajaran</label>
            <select class="form-select" id="export_id_tahun_ajaran" name="id_tahun_ajaran">
              <option value="">-- Semua Periode Tahun Ajaran --</option>
              @foreach($tahunAjaranList as $ta)
                <option value="{{ $ta->id }}">
                  {{ $ta->awal }}/{{ $ta->akhir }} (Semester {{ $ta->jenis }}) {{ $ta->is_aktif ? '★ [Aktif]' : '' }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Rentang Tanggal Khatam -->
          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="export_tanggal_mulai">Tanggal Mulai</label>
              <input type="date" class="form-control" id="export_tanggal_mulai" name="tanggal_mulai" />
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="export_tanggal_selesai">Tanggal Selesai</label>
              <input type="date" class="form-control" id="export_tanggal_selesai" name="tanggal_selesai" />
            </div>
            <small class="text-muted">Kosongkan jika ingin mengekspor seluruh tanggal.</small>
          </div>

          <!-- Filter Kelas -->
          <div class="mb-2">
            <label class="form-label fw-semibold" for="export_kelas">Kelas</label>
            <select class="form-select" id="export_kelas" name="kelas">
              <option value="all">-- Semua Kelas --</option>
              <optgroup label="Kelas A">
                <option value="1A">1A</option>
                <option value="2A">2A</option>
                <option value="3A">3A</option>
                <option value="4A">4A</option>
                <option value="5A">5A</option>
                <option value="6A">6A</option>
              </optgroup>
              <optgroup label="Kelas B">
                <option value="1B">1B</option>
                <option value="2B">2B</option>
                <option value="3B">3B</option>
                <option value="4B">4B</option>
                <option value="5B">5B</option>
                <option value="6B">6B</option>
              </optgroup>
            </select>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success">
            <i class="mdi mdi-download me-1"></i> Download Excel (.xlsx)
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Import Khotimin -->
<div class="modal fade" id="modalImportKhotimin" tabindex="-1" aria-labelledby="modalImportTitle" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="formImportKhotimin" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-label-primary py-3">
          <h5 class="modal-title fw-bold" id="modalImportTitle">
            <i class="mdi mdi-file-upload-outline me-1"></i> Import Data Khotimin
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-3">
          <!-- Banner & Download Template -->
          <div class="alert alert-info py-2 px-3 mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center">
              <i class="mdi mdi-information-outline mdi-24px me-2 text-info"></i>
              <div>
                <strong class="d-block text-dark">Format Export Dapat Langsung Digunakan!</strong>
                <span class="small text-muted">File hasil Export Data Khotimin (.xlsx) dapat langsung Anda isi/edit dan di-import kembali.</span>
              </div>
            </div>
            <a href="{{ route('khotimin.template') }}" class="btn btn-sm btn-info text-nowrap">
              <i class="mdi mdi-download me-1"></i> Download Format Template Excel
            </a>
          </div>

          <!-- Petunjuk Pengisian Kolom -->
          <div class="card border border-primary bg-light mb-3">
            <div class="card-header py-2 bg-label-primary d-flex align-items-center justify-content-between">
              <span class="fw-bold small text-primary">
                <i class="mdi mdi-help-circle-outline me-1"></i> Petunjuk & Ketentuan Pengisian Format Excel:
              </span>
            </div>
            <div class="card-body py-2 px-3">
              <ul class="list-unstyled mb-0 small" style="line-height: 1.7;">
                <li>
                  <span class="badge bg-danger me-1">Wajib</span> <strong>No Induk:</strong>
                  Nomor induk santri (NIS). Harus terdaftar di data Santri Aktif atau data Alumni sistem.
                </li>
                <li>
                  <span class="badge bg-danger me-1">Wajib</span> <strong>Kelas Saat Khatam:</strong>
                  Wajib diisi pilihan kelas yang valid, yaitu: <code>1A</code>, <code>2A</code>, <code>3A</code>, <code>4A</code>, <code>5A</code>, <code>6A</code> atau <code>1B</code>, <code>2B</code>, <code>3B</code>, <code>4B</code>, <code>5B</code>, <code>6B</code>.
                </li>
                <li>
                  <span class="badge bg-danger me-1">Wajib</span> <strong>Tanggal Khatam:</strong>
                  Tanggal pelaksanaan khatam. Format <code>DD/MM/YYYY</code> (misal: <code>20/11/2026</code>) atau <code>YYYY-MM-DD</code> atau format tanggal cell Excel.
                </li>
                <li>
                  <span class="badge bg-secondary me-1">Opsional</span> <strong>Tahun Ajaran:</strong>
                  Dapat diisi format tahun (contoh: <code>2025/2026</code> atau <code>2025</code>). Jika dikosongkan, otomatis menggunakan Tahun Ajaran Aktif di bawah.
                </li>
                <li>
                  <span class="badge bg-secondary me-1">Opsional</span> <strong>Nama Santri & Status:</strong>
                  Kolom nama dan status bersifat referensi visual bagi Anda. Sistem secara otomatis mencocokkan profil resmi & status santri/alumni berdasarkan Nomor Induk.
                </li>
                <li>
                  <span class="badge bg-secondary me-1">Opsional</span> <strong>Keterangan:</strong>
                  Catatan khataman tambahan (misal: <em>Khatam 30 Juz Al-Qur'an</em>).
                </li>
              </ul>
            </div>
          </div>

          <!-- File Upload Input -->
          <div class="mb-3">
            <label class="form-label fw-semibold" for="import_file">
              Pilih File Excel / CSV <span class="text-danger">*</span>
            </label>
            <input type="file" class="form-control" id="import_file" name="file" accept=".xlsx, .xls, .csv" required />
            <small class="text-muted">Mendukung format .xlsx, .xls, atau .csv (maksimal 10MB).</small>
          </div>

          <!-- Tahun Ajaran Default -->
          <div class="mb-2">
            <label class="form-label fw-semibold" for="import_id_tahun_ajaran">Tahun Ajaran Default (Fallback)</label>
            <select class="form-select" id="import_id_tahun_ajaran" name="id_tahun_ajaran">
              @foreach($tahunAjaranList as $ta)
                <option value="{{ $ta->id }}" {{ ($activeTa && $activeTa->id == $ta->id) ? 'selected' : '' }}>
                  {{ $ta->awal }}/{{ $ta->akhir }} (Semester {{ $ta->jenis }}) {{ $ta->is_aktif ? '★ [Aktif]' : '' }}
                </option>
              @endforeach
            </select>
            <small class="text-muted">Digunakan secara otomatis apabila baris pada Excel tidak mencantumkan nilai Tahun Ajaran.</small>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmitImport">
            <i class="mdi mdi-upload me-1"></i> Mulai Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Init Select2 pada Modal
  $('#modal_selected_santri').select2({
    dropdownParent: $('#modalKhotimin'),
    placeholder: '-- Cari No Induk atau Nama --',
    allowClear: true,
    width: '100%'
  });

  // Auto-fill kelas ketika santri/alumni dipilih jika kelas belum dipilih
  $('#modal_selected_santri').on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var kelasSantri = selectedOption.data('kelas');
    if (kelasSantri && !$('#khotimin_id').val()) {
      $('#modal_kelas').val(kelasSantri.toUpperCase());
    }
  });

  // Init DataTable Server-side
  var dtTable = $('.datatables-khotimin').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('khotimin.index') }}",
      data: function(d) {
        d.filter_tahun_ajaran = $('#filterTahunAjaran').val();
        d.filter_kelas = $('#filterKelas').val();
        d.filter_tipe = $('#filterTipe').val();
      }
    },
    columns: [
      { data: 'no', name: 'no', orderable: false, searchable: false },
      { 
        data: 'kode_santri', 
        name: 'kode_santri',
        render: function(data) {
          return '<span class="fw-semibold">' + (data || '-') + '</span>';
        }
      },
      { 
        data: 'nama_santri', 
        name: 'nama_santri',
        render: function(data) {
          return '<span class="fw-bold text-dark">' + (data || '-') + '</span>';
        }
      },
      { 
        data: 'tipe_santri', 
        name: 'tipe_santri',
        render: function(data, type, row) {
          if (row.tipe_santri_raw === 'alumni' || data === 'Alumni') {
            return '<span class="badge bg-label-info"><i class="mdi mdi-school me-1"></i>Alumni</span>';
          }
          return '<span class="badge bg-label-success"><i class="mdi mdi-account-check me-1"></i>Santri Aktif</span>';
        }
      },
      { 
        data: 'kelas', 
        name: 'kelas',
        render: function(data) {
          return '<span class="badge bg-label-primary fw-bold">' + (data || '-') + '</span>';
        }
      },
      { data: 'tahun_ajaran', name: 'id_tahun_ajaran' },
      { data: 'tanggal_khatam', name: 'tanggal_khatam' },
      { data: 'keterangan', name: 'keterangan' },
      {
        data: 'id',
        orderable: false,
        searchable: false,
        className: 'text-center',
        render: function(data, type, row) {
          return `
            <div class="d-inline-flex gap-1">
              <button type="button" class="btn btn-sm btn-icon btn-label-warning btn-edit" data-id="${data}" title="Edit">
                <i class="mdi mdi-pencil-outline"></i>
              </button>
              <button type="button" class="btn btn-sm btn-icon btn-label-danger btn-delete" data-id="${data}" data-nama="${row.nama_santri}" title="Hapus">
                <i class="mdi mdi-trash-can-outline"></i>
              </button>
            </div>
          `;
        }
      }
    ],
    order: [[6, 'desc']], // Sort by tanggal_khatam
    language: {
      search: "",
      searchPlaceholder: "Cari nomor induk / nama / kelas...",
      lengthMenu: "Tampil _MENU_ data",
      info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
      infoEmpty: "Data tidak ditemukan",
      zeroRecords: "Tidak ada data yang sesuai",
      paginate: {
        first: "Awal",
        last: "Akhir",
        next: "Lanjut",
        previous: "Kembali"
      }
    }
  });

  // Filter Event
  $('#filterTahunAjaran, #filterKelas, #filterTipe').on('change', function() {
    dtTable.draw();
  });

  $('#btnResetFilter').on('click', function() {
    $('#filterTahunAjaran').val('{{ $activeTa ? $activeTa->id : "" }}');
    $('#filterKelas').val('');
    $('#filterTipe').val('');
    dtTable.draw();
  });

  // Buka Modal Tambah
  $('#btnOpenAddModal').on('click', function() {
    $('#formKhotimin')[0].reset();
    $('#khotimin_id').val('');
    $('#modal_selected_santri').val('').trigger('change');
    $('#modal_id_tahun_ajaran').val('{{ $activeTa ? $activeTa->id : "" }}');
    $('#modal_kelas').val('');
    $('#modal_tanggal_khatam').val('{{ date("Y-m-d") }}');
    $('#modalKhotiminTitle').html('<i class="mdi mdi-plus-box-outline me-1"></i> Tambah Data Khotimin');
    $('#modalKhotimin').modal('show');
  });

  // Buka Modal Export
  $('#btnOpenExportModal').on('click', function() {
    // Sesuaikan default filter export dengan filter tabel yang sedang aktif
    var activeTaFilter = $('#filterTahunAjaran').val();
    if (activeTaFilter) {
      $('#export_id_tahun_ajaran').val(activeTaFilter);
    }
    var activeKelasFilter = $('#filterKelas').val();
    if (activeKelasFilter) {
      $('#export_kelas').val(activeKelasFilter);
    }
    $('#modalExportKhotimin').modal('show');
  });

  // Buka Modal Import
  $('#btnOpenImportModal').on('click', function() {
    $('#formImportKhotimin')[0].reset();
    $('#modalImportKhotimin').modal('show');
  });

  // Submit Import Form via AJAX
  $('#formImportKhotimin').on('submit', function(e) {
    e.preventDefault();
    var form = $(this)[0];
    var formData = new FormData(form);
    var submitBtn = $('#btnSubmitImport');
    var originalBtnText = submitBtn.html();

    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mengimpor...');

    $.ajax({
      url: "{{ route('khotimin.import') }}",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function(res) {
        submitBtn.prop('disabled', false).html(originalBtnText);
        if (res.status === 'success' || res.success) {
          $('#modalImportKhotimin').modal('hide');

          var alertHtml = res.message;
          if (res.data && res.data.errors && res.data.errors.length > 0) {
            alertHtml += '<hr><div class="text-start small text-danger" style="max-height:120px; overflow-y:auto;">' + res.data.errors.join('<br>') + '</div>';
          }

          Swal.fire({
            icon: 'success',
            title: 'Import Berhasil!',
            html: alertHtml,
          });
          dtTable.draw(false);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: res.message || 'Gagal memproses file import.'
          });
        }
      },
      error: function(xhr) {
        submitBtn.prop('disabled', false).html(originalBtnText);
        var msg = 'Terjadi kesalahan saat mengunggah file.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = xhr.responseJSON.message;
        }
        Swal.fire({
          icon: 'error',
          title: 'Perhatian!',
          html: msg
        });
      }
    });
  });

  // Form Submit (Create & Update)
  $('#formKhotimin').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    var submitBtn = $('#btnSubmitKhotimin');
    var originalBtnText = submitBtn.html();

    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...');

    $.ajax({
      url: "{{ route('khotimin.store') }}",
      method: "POST",
      data: form.serialize(),
      dataType: "json",
      success: function(res) {
        submitBtn.prop('disabled', false).html(originalBtnText);
        if (res.status === 'success' || res.success) {
          $('#modalKhotimin').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: res.message || 'Data khotimin berhasil disimpan.',
            timer: 1800,
            showConfirmButton: false
          });
          dtTable.draw(false);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: res.message || 'Terjadi kesalahan saat menyimpan data.'
          });
        }
      },
      error: function(xhr) {
        submitBtn.prop('disabled', false).html(originalBtnText);
        var msg = 'Terjadi kesalahan server.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = xhr.responseJSON.message;
        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
          var errList = [];
          $.each(xhr.responseJSON.errors, function(k, v) {
            errList.push(v[0]);
          });
          msg = errList.join('<br>');
        }
        Swal.fire({
          icon: 'error',
          title: 'Perhatian!',
          html: msg
        });
      }
    });
  });

  // Edit Action
  $(document).on('click', '.btn-edit', function() {
    var id = $(this).data('id');
    var btn = $(this);
    btn.prop('disabled', true);

    $.ajax({
      url: "{{ url('/khotimin') }}/" + id + "/edit",
      type: "GET",
      dataType: "json",
      success: function(res) {
        btn.prop('disabled', false);
        if (res.success && res.data) {
          var d = res.data;
          $('#khotimin_id').val(d.id);
          $('#modal_selected_santri').val(d.selected_santri).trigger('change');
          $('#modal_id_tahun_ajaran').val(d.id_tahun_ajaran);
          $('#modal_kelas').val(d.kelas);
          $('#modal_tanggal_khatam').val(d.tanggal_khatam);
          $('#modal_keterangan').val(d.keterangan);

          $('#modalKhotiminTitle').html('<i class="mdi mdi-pencil-box-outline me-1"></i> Edit Data Khotimin');
          $('#modalKhotimin').modal('show');
        } else {
          Swal.fire('Gagal!', 'Data tidak dapat dimuat.', 'error');
        }
      },
      error: function() {
        btn.prop('disabled', false);
        Swal.fire('Error!', 'Tidak dapat mengambil data dari server.', 'error');
      }
    });
  });

  // Delete Action
  $(document).on('click', '.btn-delete', function() {
    var id = $(this).data('id');
    var nama = $(this).data('nama') || 'santri ini';

    Swal.fire({
      title: 'Hapus Data Khotimin?',
      text: 'Data khotimin atas nama "' + nama + '" akan dihapus secara permanen.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<i class="mdi mdi-trash-can-outline me-1"></i> Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ url('/khotimin') }}/" + id,
          type: "DELETE",
          dataType: "json",
          success: function(res) {
            if (res.success || res.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Dihapus!',
                text: res.message || 'Data khotimin berhasil dihapus.',
                timer: 1500,
                showConfirmButton: false
              });
              dtTable.draw(false);
            } else {
              Swal.fire('Gagal!', res.message || 'Gagal menghapus data.', 'error');
            }
          },
          error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menghapus data.';
            Swal.fire('Error!', msg, 'error');
          }
        });
      }
    });
  });
});
</script>
@endsection
