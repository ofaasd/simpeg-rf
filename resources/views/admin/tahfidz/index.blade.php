@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-script')
<script src="{{asset('js/laravel-academic-management.js')}}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  const fromSelect = $('#transfer_from_tahfidz');
  const toSelect = $('#transfer_to_tahfidz');
  const tbody = $('#tbody_santri_transfer');
  const checkAll = $('#check_all_santri');
  const countSelected = $('#count_selected_santri');
  const badgeTotal = $('#badge_total_santri');
  const btnSubmit = $('#btnSubmitTransfer');
  const errorAlert = $('#transfer-error-alert');

  function updateSelectedCount() {
    const checked = tbody.find('.santri-checkbox:checked').length;
    const total = tbody.find('.santri-checkbox').length;
    countSelected.text(checked);
    checkAll.prop('checked', total > 0 && checked === total);

    const fromVal = fromSelect.val();
    const toVal = toSelect.val();

    if (checked > 0 && toVal && toVal !== fromVal) {
      btnSubmit.prop('disabled', false);
    } else {
      btnSubmit.prop('disabled', true);
    }
  }

  // Klik tombol Transfer pada baris datatable
  $(document).on('click', '.btn-transfer-tahfidz', function () {
    const id = $(this).data('id');
    fromSelect.val(id).trigger('change');
  });

  // Saat kelompok asal diubah
  fromSelect.on('change', function () {
    const fromId = $(this).val();
    errorAlert.addClass('d-none').html('');
    checkAll.prop('checked', true);

    if (!fromId) {
      tbody.html('<tr><td colspan="5" class="text-center text-muted py-4"><i class="mdi mdi-information-outline mdi-24px d-block mb-1"></i>Silakan pilih kelompok asal terlebih dahulu.</td></tr>');
      badgeTotal.text('0 Santri');
      countSelected.text('0');
      btnSubmit.prop('disabled', true);
      return;
    }

    tbody.html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><span class="ms-2">Memuat data santri...</span></td></tr>');
    btnSubmit.prop('disabled', true);

    $.ajax({
      url: `${baseUrl}tahfidz/get-santri/${fromId}`,
      type: 'GET',
      success: function (res) {
        if (res.status === 'success') {
          const santriList = res.data;
          badgeTotal.text(`${santriList.length} Santri`);

          if (santriList.length === 0) {
            tbody.html('<tr><td colspan="5" class="text-center text-warning py-4"><i class="mdi mdi-alert-circle-outline mdi-24px d-block mb-1"></i>Kelompok ini belum memiliki santri terdaftar.</td></tr>');
            countSelected.text('0');
            btnSubmit.prop('disabled', true);
            return;
          }

          let html = '';
          santriList.forEach((s) => {
            const jkBadge = s.jenis_kelamin === 'L'
              ? '<span class="badge bg-label-info">L</span>'
              : '<span class="badge bg-label-danger">P</span>';
            html += `
              <tr>
                <td class="text-center">
                  <input type="checkbox" class="form-check-input santri-checkbox" name="santri_ids[]" value="${s.id}" checked>
                </td>
                <td>${s.no_induk || '-'}</td>
                <td class="fw-semibold">${s.nama}</td>
                <td><span class="badge bg-label-secondary">${s.kelas || '-'}</span></td>
                <td>${jkBadge}</td>
              </tr>
            `;
          });
          tbody.html(html);
          updateSelectedCount();
        }
      },
      error: function () {
        tbody.html('<tr><td colspan="5" class="text-center text-danger py-4">Gagal memuat data santri. Silakan coba lagi.</td></tr>');
        btnSubmit.prop('disabled', true);
      }
    });
  });

  // Check all toggle
  checkAll.on('change', function () {
    const isChecked = $(this).is(':checked');
    tbody.find('.santri-checkbox').prop('checked', isChecked);
    updateSelectedCount();
  });

  // Individual checkbox toggle
  $(document).on('change', '.santri-checkbox', function () {
    updateSelectedCount();
  });

  // Target group change
  toSelect.on('change', function () {
    updateSelectedCount();
  });

  // Submit transfer form
  $('#formBulkTransferTahfidz').on('submit', function (e) {
    e.preventDefault();
    errorAlert.addClass('d-none').html('');

    const fromId = fromSelect.val();
    const toId = toSelect.val();
    const fromText = fromSelect.find('option:selected').text().trim();
    const toText = toSelect.find('option:selected').text().trim();
    const checkedCount = tbody.find('.santri-checkbox:checked').length;

    if (!fromId) {
      errorAlert.removeClass('d-none').text('Silakan pilih kelompok asal.');
      return;
    }
    if (!toId) {
      errorAlert.removeClass('d-none').text('Silakan pilih kelompok tujuan.');
      return;
    }
    if (fromId === toId) {
      errorAlert.removeClass('d-none').text('Kelompok tujuan tidak boleh sama dengan kelompok asal.');
      return;
    }
    if (checkedCount === 0) {
      errorAlert.removeClass('d-none').text('Pilih minimal satu santri yang ingin dipindahkan.');
      return;
    }

    Swal.fire({
      title: 'Konfirmasi Pemindahan Santri',
      html: `Apakah Anda yakin ingin memindahkan <b>${checkedCount} santri</b><br><br>` +
            `<div class="text-start bg-light p-3 rounded small">` +
            `<div><b>Dari Kelompok:</b> ${fromText}</div>` +
            `<div><b>Ke Kelompok:</b> ${toText}</div>` +
            `</div>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Pindahkan Sekarang!',
      cancelButtonText: 'Batal',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status"></span> Memproses...');

        $.ajax({
          url: `${baseUrl}tahfidz/transfer-santri`,
          type: 'POST',
          data: $('#formBulkTransferTahfidz').serialize(),
          success: function (res) {
            btnSubmit.prop('disabled', false).html('<i class="mdi mdi-account-arrow-right-outline me-1"></i> Proses Pindahkan Santri');
            $('#modalBulkTransferTahfidz').modal('hide');

            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: res.message || 'Santri berhasil dipindahkan!',
              customClass: {
                confirmButton: 'btn btn-success'
              }
            });

            // Reload datatable
            if ($('.datatables-tahfidz').length) {
              $('.datatables-tahfidz').DataTable().ajax.reload(null, false);
            }
          },
          error: function (xhr) {
            btnSubmit.prop('disabled', false).html('<i class="mdi mdi-account-arrow-right-outline me-1"></i> Proses Pindahkan Santri');
            let msg = 'Terjadi kesalahan saat memindahkan santri.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            errorAlert.removeClass('d-none').text(msg);
          }
        });
      }
    });
  });
});
</script>
@endsection

@section('content')
<!-- {{strtolower($title)}} List Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="card-title mb-0">Manajemen Kelompok Tahfidz</h5>
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalBulkTransferTahfidz">
      <i class="mdi mdi-account-switch me-1"></i> Transfer Santri Antar Kelompok
    </button>
  </div>
  <div class="card-datatable table-responsive">
<textarea name='column' id='my_column' style="display:none">@foreach($indexed as $value) {{$value . "\n"}} @endforeach</textarea>
<input type="hidden" name="page" id='page' value='tahfidz'>
<input type="hidden" name="title" id='title' value='Tahfidz'>
    <table class="datatables-{{strtolower($title)}} table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Id</th>
          <th>Code</th>
          <th>Name</th>
          <th>Employee</th>
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
        <input type="hidden" id="add-{{strtolower($title)}}-tahun_ajaran_ids" name="tahun_ajaran_id" value="{{$ta->id}}">
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="add-{{strtolower($title)}}-name" placeholder="Academic Name; Ex : Operator" name="name" aria-label="John Doe" />
          <label for="add-{{strtolower($title)}}-name">Name</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control" id="add-{{strtolower($title)}}-code" name="code" >
            @for($i=1;$i<=50;$i++)
              @foreach($code as $value)
                <option value='{{$i}}{{$value}}'>{{$i}}{{$value}}</option>
              @endforeach
            @endfor
          </select>
          <label for="add-{{strtolower($title)}}-description">Code</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <select  class="form-control select2" id="add-{{strtolower($title)}}-employee_id" name="employee_id" >

              @foreach($employee as $row)
                <option value='{{$row->id}}'>{{$row->nama}}</option>
              @endforeach
          </select>
          <label for="add-{{strtolower($title)}}-employee_id">Employee</label>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
      </form>
    </div>
  </div>

  <!-- Modal Bulk Transfer Santri Tahfidz -->
  <div class="modal fade" id="modalBulkTransferTahfidz" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title mb-0"><i class="mdi mdi-account-switch text-warning me-2"></i>Transfer Santri Antar Kelompok</h5>
            <small class="text-muted">Pindahkan seluruh atau sebagian santri ke kelompok / guru tahfidz yang baru</small>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formBulkTransferTahfidz">
          @csrf
          <div class="modal-body">
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label fw-bold" for="transfer_from_tahfidz">
                  <i class="mdi mdi-arrow-top-right text-danger me-1"></i>Kelompok / Guru Asal (Lama) <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="transfer_from_tahfidz" name="from_tahfidz_id" required>
                  <option value="">-- Pilih Kelompok Asal --</option>
                  @if(!empty($allTahfidz))
                    @foreach($allTahfidz as $t)
                      <option value="{{$t->id}}">
                        {{$t->name}} ({{$t->code}}) - {{$t->pegawai->nama ?? 'Tanpa Guru'}}
                      </option>
                    @endforeach
                  @endif
                </select>
                <div class="d-flex justify-content-between align-items-center mt-1">
                  <small class="text-muted">Kelompok yang santrinya akan dipindahkan</small>
                  <span class="badge bg-label-info" id="badge_total_santri">0 Santri</span>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-bold" for="transfer_to_tahfidz">
                  <i class="mdi mdi-arrow-down-left text-success me-1"></i>Kelompok / Guru Tujuan (Baru) <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="transfer_to_tahfidz" name="to_tahfidz_id" required>
                  <option value="">-- Pilih Kelompok Tujuan --</option>
                  @if(!empty($allTahfidz))
                    @foreach($allTahfidz as $t)
                      <option value="{{$t->id}}">
                        {{$t->name}} ({{$t->code}}) - {{$t->pegawai->nama ?? 'Tanpa Guru'}}
                      </option>
                    @endforeach
                  @endif
                </select>
                <div class="mt-1">
                  <small class="text-muted">Kelompok penerima santri</small>
                </div>
              </div>
            </div>

            <!-- List Santri -->
            <div class="card border mb-3">
              <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                <div class="form-check mb-0">
                  <input class="form-check-input" type="checkbox" id="check_all_santri" checked>
                  <label class="form-check-label fw-semibold" for="check_all_santri">
                    Pilih Semua Santri (<span id="count_selected_santri">0</span> santri terpilih)
                  </label>
                </div>
                <small class="text-muted">Centang untuk memindahkan</small>
              </div>
              <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                <table class="table table-sm table-hover mb-0">
                  <thead class="table-light sticky-top">
                    <tr>
                      <th width="45px" class="text-center">#</th>
                      <th>No. Induk</th>
                      <th>Nama Santri</th>
                      <th>Kelas</th>
                      <th>L/P</th>
                    </tr>
                  </thead>
                  <tbody id="tbody_santri_transfer">
                    <tr>
                      <td colspan="5" class="text-center text-muted py-4">
                        <i class="mdi mdi-information-outline mdi-24px d-block mb-1"></i>
                        Silakan pilih kelompok asal terlebih dahulu untuk melihat daftar santri.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div id="transfer-error-alert" class="alert alert-danger d-none mb-0"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btnSubmitTransfer" disabled>
              <i class="mdi mdi-account-arrow-right-outline me-1"></i> Proses Pindahkan Santri
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
