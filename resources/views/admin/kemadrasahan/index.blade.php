@extends('layouts/layoutMaster')

@section('title', 'Kemadrasahan - Penilaian Santri')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Header Card -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card bg-label-primary border-0 shadow-sm">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 py-3">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-md bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center">
              <i class="mdi mdi-book-open-page-variant-outline mdi-24px"></i>
            </div>
            <div>
              <h5 class="mb-0 text-primary fw-bold">Manajemen Penilaian Kemadrasahan</h5>
              <small class="text-muted">Pencatatan dan pelaporan capaian materi santri per kelas, semester, dan bulan.</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            @if($isAdmin)
              <span class="badge bg-label-primary"><i class="mdi mdi-shield-account me-1"></i>Administrator</span>
            @elseif($isWaliKelas)
              <span class="badge bg-label-success"><i class="mdi mdi-school me-1"></i>Wali Kelas: {{ strtoupper($kelasList->first()->nama_kelas ?? '') }}</span>
            @else
              <span class="badge bg-label-warning"><i class="mdi mdi-alert-circle-outline me-1"></i>Bukan Wali Kelas</span>
            @endif
            <span class="badge bg-primary" id="badgeTotalSantri">Memuat santri...</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(!$isAdmin && !$isWaliKelas)
    <!-- Peringatan jika Ustadz belum ditugaskan sebagai Wali Kelas -->
    <div class="card border-warning mb-4 shadow-sm">
      <div class="card-body py-4 text-center">
        <i class="mdi mdi-alert-circle-outline text-warning mdi-48px d-block mb-2"></i>
        <h5 class="fw-bold mb-1">Akses Penilaian Kemadrasahan Dibatasi</h5>
        <p class="text-muted mb-0">
          Akun Anda belum ditugaskan sebagai <strong>Wali Kelas</strong> pada master referensi kelas.<br>
          Manajemen penilaian kemadrasahan hanya dapat diakses oleh Wali Kelas untuk kelas yang diampunya, atau oleh Administrator.
        </p>
      </div>
    </div>
  @else
  <!-- Filter Parameter Penilaian -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-3">
      <div class="d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold"><i class="mdi mdi-filter-variant me-1 text-primary"></i> Parameter Penilaian</h6>
        @if(!$isAdmin && $isWaliKelas)
          <span class="badge bg-label-info"><i class="mdi mdi-lock-outline me-1"></i>Dibatasi pada Kelas {{ strtoupper($kelasList->first()->nama_kelas ?? '') }}</span>
        @endif
      </div>
    </div>
    <div class="card-body pt-3">
      <div class="row g-3">
        <!-- Pilihan Wali Kelas / Kelas -->
        <div class="col-md-4 col-sm-6">
          <label class="form-label fw-semibold" for="selectWaliKelas">
            Kelas & Wali Kelas <span class="text-danger">*</span>
            @if(!$isAdmin)
              <i class="mdi mdi-lock-outline text-muted ms-1" title="Terkunci pada kelas yang Anda ampu"></i>
            @endif
          </label>
          <select id="selectWaliKelas" class="form-select select2" {{ !$isAdmin ? 'disabled' : '' }}>
            @foreach($kelasList as $kelas)
              @if($kelas->user_id)
                <option value="{{ $kelas->user_id }}" 
                  data-kelas="{{ $kelas->kode_kelas }}" 
                  data-nama="{{ $kelas->nama_wali_kelas }}" 
                  {{ $kelas->user_id == $defaultUserId ? 'selected' : '' }}>
                  Kelas {{ strtoupper($kelas->nama_kelas) }} &mdash; {{ $kelas->nama_wali_kelas ?? 'Belum ada nama' }}
                </option>
              @endif
            @endforeach
          </select>
          @if(!$isAdmin)
            <small class="text-muted d-block mt-1">Otomatis terkunci pada kelas yang Anda ampu.</small>
          @endif
        </div>

        <!-- Semester -->
        <div class="col-md-2 col-sm-6">
          <label class="form-label fw-semibold" for="selectSemester">Semester <span class="text-danger">*</span></label>
          <select id="selectSemester" class="form-select">
            <option value="1" {{ $currentSemester == 1 ? 'selected' : '' }}>1 (Ganjil)</option>
            <option value="2" {{ $currentSemester == 2 ? 'selected' : '' }}>2 (Genap)</option>
          </select>
        </div>

        <!-- Bulan -->
        <div class="col-md-2 col-sm-6">
          <label class="form-label fw-semibold" for="selectBulan">Bulan <span class="text-danger">*</span></label>
          <select id="selectBulan" class="form-select">
            @foreach($bulanList as $num => $namaBulan)
              <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>{{ $num }} - {{ $namaBulan }}</option>
            @endforeach
          </select>
        </div>

        <!-- Mata Pelajaran -->
        <div class="col-md-2 col-sm-6">
          <label class="form-label fw-semibold" for="selectMapel">Mata Pelajaran <span class="text-danger">*</span></label>
          <select id="selectMapel" class="form-select">
            <option value="">-- Pilih Mapel --</option>
            @foreach($mapelList as $mapel)
              <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
            @endforeach
          </select>
        </div>

        <!-- Minggu Ke- -->
        <div class="col-md-2 col-sm-6">
          <label class="form-label fw-semibold" for="selectMinggu">Minggu Ke- <span class="text-danger">*</span></label>
          <select id="selectMinggu" class="form-select">
            <option value="1">Minggu 1</option>
            <option value="2">Minggu 2</option>
            <option value="3">Minggu 3</option>
            <option value="4">Minggu 4</option>
            <option value="5">Minggu 5</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- Navigation Tabs: Input Massal vs Daftar Santri -->
  <div class="nav-align-top mb-4">
    <ul class="nav nav-tabs nav-fill" role="tablist">
      <li class="nav-item">
        <button type="button" class="nav-link active fw-semibold" role="tab" data-bs-toggle="tab" data-bs-target="#tab-bulk" aria-controls="tab-bulk" aria-selected="true">
          <i class="mdi mdi-account-group-outline me-1"></i> 1. Input Massal Satu Kelas (Bulk)
        </button>
      </li>
      <li class="nav-item">
        <button type="button" class="nav-link fw-semibold" role="tab" data-bs-toggle="tab" data-bs-target="#tab-santri" aria-controls="tab-santri" aria-selected="false">
          <i class="mdi mdi-format-list-bulleted me-1"></i> 2. Daftar Santri & Input Satuan / Rekap
        </button>
      </li>
    </ul>

    <div class="tab-content border-0 shadow-sm p-4 bg-white rounded-bottom">
      <!-- TAB 1: Input Massal (Bulk) -->
      <div class="tab-pane fade show active" id="tab-bulk" role="tabpanel">
        <div class="alert alert-primary d-flex align-items-center mb-4" role="alert">
          <i class="mdi mdi-information-outline mdi-24px me-2"></i>
          <div>
            <strong>Mode Input Massal:</strong> Nilai materi dan deskripsi yang Anda inputkan akan otomatis disimpan ke <strong>seluruh santri di kelas terpilih</strong> sekaligus sesuai semester, bulan, mapel, dan minggu yang dipilih di filter atas.
          </div>
        </div>

        <form id="formBulkNilai">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold" for="bulkMateri">Judul Materi <span class="text-danger">*</span></label>
              <input type="text" id="bulkMateri" name="materi" class="form-control" placeholder="Contoh: Tajwid & Fasholatan / Bab Hukum Nun Mati" required />
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold" for="bulkDeskripsi">Deskripsi Capaian / Evaluasi Penilaian <span class="text-danger">*</span></label>
              <textarea id="bulkDeskripsi" name="deskripsiPenilaian" class="form-control" rows="4" placeholder="Contoh: Siswa telah mampu memahami hukum nun sukun dan tanwin dengan baik serta mempraktikkannya dalam tilawah." required></textarea>
            </div>

            <div class="col-12 mt-4 text-end">
              <button type="reset" class="btn btn-label-secondary me-2">
                <i class="mdi mdi-refresh me-1"></i> Reset
              </button>
              <button type="submit" class="btn btn-primary" id="btnSubmitBulk">
                <i class="mdi mdi-content-save-all me-1"></i> Simpan Nilai ke Seluruh Santri di Kelas
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- TAB 2: Daftar Santri & Rekap -->
      <div class="tab-pane fade" id="tab-santri" role="tabpanel">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
          <div>
            <h6 class="mb-0 fw-bold">Daftar Santri Kelas Terpilih</h6>
            <small class="text-muted">Klik tombol aksi untuk menginput nilai perorangan atau melihat riwayat rekap nilai santri.</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <input type="text" id="searchSantri" class="form-control form-control-sm" placeholder="Cari nama santri / no. induk..." style="max-width: 250px;" />
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefreshSantri" title="Segarkan Data Santri">
              <i class="mdi mdi-refresh"></i>
            </button>
          </div>
        </div>

        <div class="table-responsive text-nowrap">
          <table class="table table-hover align-middle" id="tableSantri">
            <thead class="table-light">
              <tr>
                <th width="50">No</th>
                <th width="120">No. Induk</th>
                <th>Nama Santri</th>
                <th width="100">Kelas</th>
                <th width="80">Jenis Kelamin</th>
                <th width="180" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody id="tbodySantri">
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                  <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                  Memuat daftar santri...
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Input Single Nilai Santri -->
<div class="modal fade" id="modalSingleNilai" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold" id="modalSingleTitle">Input Nilai Santri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formSingleNilai">
        <div class="modal-body">
          <input type="hidden" id="singleNoInduk" name="noInduk" />
          
          <div class="alert alert-secondary py-2 px-3 mb-3">
            <div class="d-flex align-items-center gap-2">
              <i class="mdi mdi-account-circle mdi-24px text-primary"></i>
              <div>
                <strong id="singleNamaSantri">-</strong>
                <small class="text-muted d-block">No. Induk: <span id="singleNoIndukLabel">-</span></small>
              </div>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label" for="singleSemester">Semester</label>
              <select id="singleSemester" name="semester" class="form-select form-select-sm">
                <option value="1">1 (Ganjil)</option>
                <option value="2">2 (Genap)</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label" for="singleBulan">Bulan</label>
              <select id="singleBulan" name="bulan" class="form-select form-select-sm">
                @foreach($bulanList as $num => $namaBulan)
                  <option value="{{ $num }}">{{ $num }} - {{ $namaBulan }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label" for="singleMapel">Mata Pelajaran</label>
              <select id="singleMapel" name="idMapel" class="form-select form-select-sm" required>
                <option value="">-- Pilih Mapel --</option>
                @foreach($mapelList as $mapel)
                  <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label" for="singleMinggu">Minggu Ke-</label>
              <select id="singleMinggu" name="mingguKe" class="form-select form-select-sm">
                <option value="1">Minggu 1</option>
                <option value="2">Minggu 2</option>
                <option value="3">Minggu 3</option>
                <option value="4">Minggu 4</option>
                <option value="5">Minggu 5</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="singleMateri">Materi <span class="text-danger">*</span></label>
            <input type="text" id="singleMateri" name="materi" class="form-control" placeholder="Materi yang dinilai" required />
          </div>

          <div class="mb-2">
            <label class="form-label fw-semibold" for="singleDeskripsi">Deskripsi Penilaian <span class="text-danger">*</span></label>
            <textarea id="singleDeskripsi" name="deskripsiPenilaian" class="form-control" rows="3" placeholder="Deskripsi capaian nilai..." required></textarea>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmitSingle">
            <i class="mdi mdi-content-save me-1"></i> Simpan Nilai
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL: Edit Nilai (Spesifik Item) -->
<div class="modal fade" id="modalEditNilai" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold">Edit Detail Penilaian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditNilai">
        <div class="modal-body">
          <input type="hidden" id="editDetailId" />

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label" for="editMapel">Mata Pelajaran</label>
              <select id="editMapel" name="idMapel" class="form-select form-select-sm" required>
                @foreach($mapelList as $mapel)
                  <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label" for="editMinggu">Minggu Ke-</label>
              <select id="editMinggu" name="mingguKe" class="form-select form-select-sm">
                <option value="1">Minggu 1</option>
                <option value="2">Minggu 2</option>
                <option value="3">Minggu 3</option>
                <option value="4">Minggu 4</option>
                <option value="5">Minggu 5</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="editMateri">Materi <span class="text-danger">*</span></label>
            <input type="text" id="editMateri" name="materi" class="form-control" required />
          </div>

          <div class="mb-2">
            <label class="form-label fw-semibold" for="editDeskripsi">Deskripsi Penilaian <span class="text-danger">*</span></label>
            <textarea id="editDeskripsi" name="deskripsiPenilaian" class="form-control" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmitEdit">
            <i class="mdi mdi-check me-1"></i> Perbarui Nilai
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL: Rekap Nilai Santri -->
<div class="modal fade" id="modalRekapSantri" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header border-bottom bg-light py-3">
        <div class="d-flex align-items-center gap-2">
          <i class="mdi mdi-school mdi-24px text-primary"></i>
          <div>
            <h5 class="modal-title mb-0 fw-bold" id="rekapModalSantriNama">Rekap Nilai Kemadrasahan</h5>
            <small class="text-muted" id="rekapModalSantriInfo">-</small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" id="rekapModalBody">
        <div class="text-center py-5 text-muted">
          <div class="spinner-border text-primary mb-2" role="status"></div>
          <div>Mengambil data rekap nilai...</div>
        </div>
      </div>
      <div class="modal-footer border-top py-2">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
  @endif
</div>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
  const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  let currentSantriList = [];
  let activeNoIndukRekap = null;

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': CSRF_TOKEN
    }
  });

  // Load awal data santri
  loadSantri();

  // Event handler ganti Wali Kelas / Kelas
  $('#selectWaliKelas').on('change', function() {
    loadSantri();
  });

  // Event handler refresh santri
  $('#btnRefreshSantri').on('click', function() {
    loadSantri();
  });

  // Filter pencarian santri
  $('#searchSantri').on('keyup', function() {
    const keyword = $(this).val().toLowerCase();
    $('#tbodySantri tr').each(function() {
      const rowText = $(this).text().toLowerCase();
      $(this).toggle(rowText.indexOf(keyword) > -1);
    });
  });

  // ==========================================
  // 1. FUNGSI LOAD DAFTAR SANTRI
  // ==========================================
  function loadSantri() {
    if ($('#selectWaliKelas').length === 0) {
      $('#badgeTotalSantri').text('0 Santri');
      return;
    }
    const selectedOpt = $('#selectWaliKelas option:selected');
    const idUser = selectedOpt.val();
    const kodeKelas = selectedOpt.data('kelas');

    if (!idUser && !kodeKelas) {
      $('#tbodySantri').html('<tr><td colspan="6" class="text-center text-muted py-4">Silakan pilih Kelas / Wali Kelas terlebih dahulu.</td></tr>');
      $('#badgeTotalSantri').text('0 Santri');
      return;
    }

    $('#tbodySantri').html(`
      <tr>
        <td colspan="6" class="text-center py-4 text-muted">
          <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
          Memuat data santri...
        </td>
      </tr>
    `);

    $.ajax({
      url: "{{ url('/kemadrasahan/santri') }}/" + (kodeKelas || idUser),
      method: "GET",
      dataType: "json",
      success: function(res) {
        if (res.success && Array.isArray(res.data)) {
          currentSantriList = res.data;
          $('#badgeTotalSantri').text(currentSantriList.length + ' Santri');
          renderTableSantri(currentSantriList);
        } else {
          currentSantriList = [];
          $('#badgeTotalSantri').text('0 Santri');
          $('#tbodySantri').html(`
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                <i class="mdi mdi-alert-circle-outline text-warning mdi-24px d-block mb-1"></i>
                ${res.message || 'Tidak ada santri yang terdaftar di kelas ini.'}
              </td>
            </tr>
          `);
        }
      },
      error: function(xhr) {
        console.error("Error load santri", xhr);
        $('#tbodySantri').html(`
          <tr>
            <td colspan="6" class="text-center text-danger py-4">
              <i class="mdi mdi-close-circle-outline mdi-24px d-block mb-1"></i>
              Gagal memuat data santri.
            </td>
          </tr>
        `);
      }
    });
  }

  function renderTableSantri(list) {
    if (!list || list.length === 0) {
      $('#tbodySantri').html('<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data santri ditemukan.</td></tr>');
      return;
    }

    let rows = '';
    list.forEach(function(s, idx) {
      const noInduk = s.noInduk || s.no_induk || '-';
      const nama = s.namaSantri || s.nama || '-';
      const kelas = s.kodeKelas || $('#selectWaliKelas option:selected').data('kelas') || '-';
      const jk = s.jenisKelamin || s.jk || '-';
      const jkBadge = (jk === 'L' || jk === 'Laki-laki') 
        ? '<span class="badge bg-label-info">L</span>' 
        : (jk === 'P' || jk === 'Perempuan' ? '<span class="badge bg-label-danger">P</span>' : '<span class="badge bg-label-secondary">-</span>');

      rows += `
        <tr>
          <td>${idx + 1}</td>
          <td><span class="fw-semibold text-primary">${noInduk}</span></td>
          <td>
            <div class="d-flex align-items-center">
              <div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded-circle bg-label-primary font-weight-bold">
                  ${nama.charAt(0).toUpperCase()}
                </span>
              </div>
              <div class="d-flex flex-column">
                <span class="fw-semibold text-heading">${nama}</span>
              </div>
            </div>
          </td>
          <td><span class="badge bg-label-secondary text-uppercase">${kelas}</span></td>
          <td>${jkBadge}</td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-primary me-1 btn-input-single" 
              data-no-induk="${noInduk}" 
              data-nama="${nama}">
              <i class="mdi mdi-plus-box-outline me-1"></i> Input Nilai
            </button>
            <button type="button" class="btn btn-sm btn-label-info btn-lihat-rekap" 
              data-no-induk="${noInduk}" 
              data-nama="${nama}">
              <i class="mdi mdi-eye-outline me-1"></i> Rekap
            </button>
          </td>
        </tr>
      `;
    });

    $('#tbodySantri').html(rows);
  }

  // ==========================================
  // 2. SUBMIT FORM INPUT MASSAL (BULK)
  // ==========================================
  $('#formBulkNilai').on('submit', function(e) {
    e.preventDefault();

    const idUser = $('#selectWaliKelas').val();
    const idMapel = $('#selectMapel').val();
    const semester = $('#selectSemester').val();
    const bulan = $('#selectBulan').val();
    const mingguKe = $('#selectMinggu').val();
    const materi = $('#bulkMateri').val().trim();
    const deskripsiPenilaian = $('#bulkDeskripsi').val().trim();
    const kodeKelas = $('#selectWaliKelas option:selected').data('kelas');

    if (!idUser) {
      showToast('error', 'Silakan pilih Wali Kelas / Kelas terlebih dahulu!');
      return;
    }
    if (!idMapel) {
      showToast('error', 'Silakan pilih Mata Pelajaran!');
      return;
    }
    if (!materi || !deskripsiPenilaian) {
      showToast('error', 'Judul materi dan deskripsi penilaian wajib diisi!');
      return;
    }

    const namaKelas = $('#selectWaliKelas option:selected').text().trim();
    const namaMapel = $('#selectMapel option:selected').text().trim();

    Swal.fire({
      title: 'Konfirmasi Input Massal',
      html: `Simpan nilai ini ke <strong>seluruh santri</strong> di <strong>${namaKelas}</strong>?<br><br>
             <div class="text-start p-3 bg-light rounded small">
               <div><strong>Mapel:</strong> ${namaMapel}</div>
               <div><strong>Semester:</strong> ${semester} | <strong>Bulan:</strong> ${bulan} | <strong>Minggu:</strong> ${mingguKe}</div>
               <div><strong>Materi:</strong> ${materi}</div>
             </div>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Simpan ke Seluruh Santri',
      cancelButtonText: 'Batal',
      customClass: {
        confirmButton: 'btn btn-primary me-2',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        const btn = $('#btnSubmitBulk');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

        $.ajax({
          url: "{{ route('kemadrasahan.store') }}",
          method: "POST",
          data: {
            idUser: idUser,
            tipeInput: 'bulk',
            bulan: bulan,
            semester: semester,
            idMapel: idMapel,
            mingguKe: mingguKe,
            materi: materi,
            deskripsiPenilaian: deskripsiPenilaian,
            kodeKelas: kodeKelas
          },
          success: function(res) {
            btn.prop('disabled', false).html('<i class="mdi mdi-content-save-all me-1"></i> Simpan Nilai ke Seluruh Santri di Kelas');
            if (res.success) {
              Swal.fire('Berhasil!', res.message || 'Nilai berhasil disimpan ke seluruh santri.', 'success');
              $('#formBulkNilai')[0].reset();
            } else {
              Swal.fire('Perhatian', res.message || 'Gagal menyimpan nilai massal.', 'warning');
            }
          },
          error: function(xhr) {
            btn.prop('disabled', false).html('<i class="mdi mdi-content-save-all me-1"></i> Simpan Nilai ke Seluruh Santri di Kelas');
            const msg = xhr.responseJSON ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Terjadi kesalahan saat menyimpan.';
            Swal.fire('Error', msg, 'error');
          }
        });
      }
    });
  });

  // ==========================================
  // 3. MODAL & SUBMIT INPUT SINGLE NILAI
  // ==========================================
  $(document).on('click', '.btn-input-single', function() {
    const noInduk = $(this).data('no-induk');
    const nama = $(this).data('nama');

    $('#singleNoInduk').val(noInduk);
    $('#singleNoIndukLabel').text(noInduk);
    $('#singleNamaSantri').text(nama);

    // Sync default dengan filter atas
    $('#singleSemester').val($('#selectSemester').val());
    $('#singleBulan').val($('#selectBulan').val());
    $('#singleMapel').val($('#selectMapel').val());
    $('#singleMinggu').val($('#selectMinggu').val());
    $('#singleMateri').val($('#bulkMateri').val());
    $('#singleDeskripsi').val($('#bulkDeskripsi').val());

    $('#modalSingleNilai').modal('show');
  });

  $('#formSingleNilai').on('submit', function(e) {
    e.preventDefault();

    const idUser = $('#selectWaliKelas').val();
    const noInduk = $('#singleNoInduk').val();
    const idMapel = $('#singleMapel').val();
    const semester = $('#singleSemester').val();
    const bulan = $('#singleBulan').val();
    const mingguKe = $('#singleMinggu').val();
    const materi = $('#singleMateri').val().trim();
    const deskripsiPenilaian = $('#singleDeskripsi').val().trim();
    const kodeKelas = $('#selectWaliKelas option:selected').data('kelas');

    if (!idMapel) {
      showToast('error', 'Silakan pilih Mata Pelajaran!');
      return;
    }

    const btn = $('#btnSubmitSingle');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

    $.ajax({
      url: "{{ route('kemadrasahan.store') }}",
      method: "POST",
      data: {
        idUser: idUser,
        tipeInput: 'single',
        noInduk: noInduk,
        bulan: bulan,
        semester: semester,
        idMapel: idMapel,
        mingguKe: mingguKe,
        materi: materi,
        deskripsiPenilaian: deskripsiPenilaian,
        kodeKelas: kodeKelas
      },
      success: function(res) {
        btn.prop('disabled', false).html('<i class="mdi mdi-content-save me-1"></i> Simpan Nilai');
        if (res.success) {
          $('#modalSingleNilai').modal('hide');
          showToast('success', res.message || 'Nilai santri berhasil disimpan.');
        } else {
          Swal.fire('Perhatian', res.message || 'Gagal menyimpan nilai.', 'warning');
        }
      },
      error: function(xhr) {
        btn.prop('disabled', false).html('<i class="mdi mdi-content-save me-1"></i> Simpan Nilai');
        const msg = xhr.responseJSON ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Terjadi kesalahan.';
        Swal.fire('Error', msg, 'error');
      }
    });
  });

  // ==========================================
  // 4. MODAL REKAP NILAI SANTRI (VIEW, EDIT, DELETE)
  // ==========================================
  $(document).on('click', '.btn-lihat-rekap', function() {
    const noInduk = $(this).data('no-induk');
    const nama = $(this).data('nama');
    activeNoIndukRekap = noInduk;

    $('#rekapModalSantriNama').text('Rekap Nilai: ' + nama);
    $('#rekapModalSantriInfo').text('No. Induk: ' + noInduk);
    $('#modalRekapSantri').modal('show');

    loadRekapSantri(noInduk);
  });

  function loadRekapSantri(noInduk) {
    $('#rekapModalBody').html(`
      <div class="text-center py-5 text-muted">
        <div class="spinner-border text-primary mb-2" role="status"></div>
        <div>Mengambil data rekap nilai...</div>
      </div>
    `);

    $.ajax({
      url: "{{ url('/kemadrasahan/rekap') }}/" + noInduk,
      method: "GET",
      dataType: "json",
      success: function(res) {
        if (res.success && res.data) {
          renderRekapContent(res.data);
        } else {
          $('#rekapModalBody').html(`
            <div class="text-center py-5 text-muted">
              <i class="mdi mdi-alert-circle-outline mdi-36px text-warning d-block mb-2"></i>
              ${res.message || 'Belum ada data nilai kemadrasahan untuk santri ini.'}
            </div>
          `);
        }
      },
      error: function(xhr) {
        console.error("Error load rekap", xhr);
        $('#rekapModalBody').html(`
          <div class="text-center py-5 text-danger">
            <i class="mdi mdi-close-circle-outline mdi-36px d-block mb-2"></i>
            Gagal mengambil data rekap.
          </div>
        `);
      }
    });
  }

  function renderRekapContent(data) {
    const santri = data.santri || {};
    const ganjil = data.ganjil || {};
    const genap = data.genap || {};

    let html = `
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <table class="table table-sm table-borderless">
            <tr><th width="120" class="text-muted">Nama:</th><td class="fw-bold">${santri.nama || '-'}</td></tr>
            <tr><th class="text-muted">No. Induk:</th><td>${santri.no_induk || '-'}</td></tr>
            <tr><th class="text-muted">Kelas:</th><td><span class="badge bg-label-primary text-uppercase">${santri.kelas || '-'}</span></td></tr>
          </table>
        </div>
      </div>

      <!-- Tab Semester Ganjil & Genap -->
      <ul class="nav nav-pills mb-3" role="tablist">
        <li class="nav-item">
          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-rekap-ganjil">
            Semester 1 (Ganjil)
          </button>
        </li>
        <li class="nav-item">
          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-rekap-genap">
            Semester 2 (Genap)
          </button>
        </li>
      </ul>

      <div class="tab-content border-0 p-0">
        <div class="tab-pane fade show active" id="tab-rekap-ganjil" role="tabpanel">
          ${renderSemesterList(ganjil, 1)}
        </div>
        <div class="tab-pane fade" id="tab-rekap-genap" role="tabpanel">
          ${renderSemesterList(genap, 2)}
        </div>
      </div>
    `;

    $('#rekapModalBody').html(html);
  }

  function renderSemesterList(bulanGroup, semesterNum) {
    if (!bulanGroup || Object.keys(bulanGroup).length === 0) {
      return `
        <div class="alert alert-light text-center py-4 border">
          <i class="mdi mdi-information-outline mdi-24px text-muted d-block mb-1"></i>
          Belum ada rekaman nilai untuk Semester ${semesterNum}.
        </div>
      `;
    }

    let out = '<div class="accordion" id="accordionSem' + semesterNum + '">';
    let idx = 0;

    $.each(bulanGroup, function(key, items) {
      idx++;
      const collapseId = 'collapseSem' + semesterNum + '_' + idx;
      const namaBulanHeader = items.nama_bulan || ('Bulan ' + key);
      const detailList = items.detail || [];

      out += `
        <div class="accordion-item card mb-2 border shadow-none">
          <h2 class="accordion-header" id="headingSem${semesterNum}_${idx}">
            <button class="accordion-button py-2 ${idx > 1 ? 'collapsed' : ''}" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
              <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                <span class="fw-bold"><i class="mdi mdi-calendar-month-outline me-1 text-primary"></i> ${namaBulanHeader}</span>
                <span class="badge bg-label-primary rounded-pill">${detailList.length} Nilai</span>
              </div>
            </button>
          </h2>
          <div id="${collapseId}" class="accordion-collapse collapse ${idx === 1 ? 'show' : ''}" data-bs-parent="#accordionSem${semesterNum}">
            <div class="accordion-body p-0">
              <div class="table-responsive text-nowrap">
                <table class="table table-sm table-striped mb-0">
                  <thead class="table-light">
                    <tr>
                      <th width="70">Minggu</th>
                      <th width="160">Mata Pelajaran</th>
                      <th>Materi</th>
                      <th>Deskripsi Capaian</th>
                      <th width="140">Pengampu</th>
                      <th width="90" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
      `;

      if (detailList.length === 0) {
        out += `<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada detail penilaian.</td></tr>`;
      } else {
        detailList.forEach(function(d) {
          const idDetail = d.id || d.id_detail;
          const mapelNama = d.nama_mapel || '-';
          const materi = d.materi || '-';
          const deskripsi = d.deskripsi_penilaian || '-';
          const minggu = d.minggu_ke || '-';
          const ustadz = d.nama_ustadz || '-';

          out += `
            <tr>
              <td><span class="badge bg-label-info">Minggu ${minggu}</span></td>
              <td class="fw-semibold">${mapelNama}</td>
              <td>${materi}</td>
              <td class="text-wrap" style="max-width: 300px;"><small>${deskripsi}</small></td>
              <td><small class="text-muted">${ustadz}</small></td>
              <td class="text-center">
                <button type="button" class="btn btn-icon btn-sm btn-label-warning me-1 btn-edit-detail" 
                  data-id="${idDetail}" title="Edit">
                  <i class="mdi mdi-pencil-outline"></i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-label-danger btn-delete-detail" 
                  data-id="${idDetail}" title="Hapus">
                  <i class="mdi mdi-trash-can-outline"></i>
                </button>
              </td>
            </tr>
          `;
        });
      }

      out += `
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      `;
    });

    out += '</div>';
    return out;
  }

  // ==========================================
  // 5. EDIT NILAI SPESIFIK ITEM
  // ==========================================
  $(document).on('click', '.btn-edit-detail', function() {
    const idDetail = $(this).data('id');
    $('#editDetailId').val(idDetail);

    $.ajax({
      url: "{{ url('/kemadrasahan/detail') }}/" + idDetail,
      method: "GET",
      dataType: "json",
      success: function(res) {
        if (res.success && res.data) {
          const d = res.data;
          $('#editMateri').val(d.materi || '');
          $('#editDeskripsi').val(d.deskripsi_penilaian || '');
          if (d.minggu_ke) $('#editMinggu').val(d.minggu_ke);
          if (d.id_mapel) $('#editMapel').val(d.id_mapel);

          $('#modalEditNilai').modal('show');
        } else {
          showToast('error', res.message || 'Gagal mengambil detail nilai.');
        }
      },
      error: function(xhr) {
        showToast('error', 'Gagal mengambil detail nilai.');
      }
    });
  });

  $('#formEditNilai').on('submit', function(e) {
    e.preventDefault();
    const idDetail = $('#editDetailId').val();
    const materi = $('#editMateri').val().trim();
    const deskripsiPenilaian = $('#editDeskripsi').val().trim();
    const idMapel = $('#editMapel').val();
    const mingguKe = $('#editMinggu').val();

    const btn = $('#btnSubmitEdit');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

    $.ajax({
      url: "{{ url('/kemadrasahan/update') }}/" + idDetail,
      method: "PUT",
      data: {
        materi: materi,
        deskripsiPenilaian: deskripsiPenilaian,
        idMapel: idMapel,
        mingguKe: mingguKe
      },
      success: function(res) {
        btn.prop('disabled', false).html('<i class="mdi mdi-check me-1"></i> Perbarui Nilai');
        if (res.success) {
          $('#modalEditNilai').modal('hide');
          showToast('success', res.message || 'Data nilai berhasil diperbarui.');
          if (activeNoIndukRekap) {
            loadRekapSantri(activeNoIndukRekap);
          }
        } else {
          Swal.fire('Perhatian', res.message || 'Gagal memperbarui nilai.', 'warning');
        }
      },
      error: function(xhr) {
        btn.prop('disabled', false).html('<i class="mdi mdi-check me-1"></i> Perbarui Nilai');
        const msg = xhr.responseJSON ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Terjadi kesalahan.';
        Swal.fire('Error', msg, 'error');
      }
    });
  });

  // ==========================================
  // 6. DELETE NILAI SPESIFIK ITEM
  // ==========================================
  $(document).on('click', '.btn-delete-detail', function() {
    const idDetail = $(this).data('id');

    Swal.fire({
      title: 'Hapus Penilaian?',
      text: 'Data detail penilaian ini akan dihapus secara permanen.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      customClass: {
        confirmButton: 'btn btn-danger me-2',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{ url('/kemadrasahan/delete') }}/" + idDetail,
          method: "DELETE",
          success: function(res) {
            if (res.success) {
              showToast('success', res.message || 'Data nilai berhasil dihapus.');
              if (activeNoIndukRekap) {
                loadRekapSantri(activeNoIndukRekap);
              }
            } else {
              Swal.fire('Gagal', res.message || 'Gagal menghapus nilai.', 'error');
            }
          },
          error: function(xhr) {
            const msg = xhr.responseJSON ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Terjadi kesalahan saat menghapus.';
            Swal.fire('Error', msg, 'error');
          }
        });
      }
    });
  });

  // Helper Toast notification
  function showToast(type, message) {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
    Toast.fire({
      icon: type,
      title: message
    });
  }
});
</script>
@endsection
