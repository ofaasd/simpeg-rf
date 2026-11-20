<?php

namespace App\Imports;

use App\Models\Khotimin;
use App\Models\Santri;
use App\Models\TbAlumniSantriDetail;
use App\Models\TahunAjaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class KhotiminImport implements ToCollection, WithHeadingRow
{
    protected ?int $defaultTahunAjaranId;
    public int $totalRows = 0;
    public int $successCount = 0;
    public int $santriCount = 0;
    public int $alumniCount = 0;
    public array $errors = [];

    public function __construct(?int $defaultTahunAjaranId = null)
    {
        $this->defaultTahunAjaranId = $defaultTahunAjaranId 
            ?? TahunAjaran::where('is_aktif', 1)->value('id') 
            ?? TahunAjaran::orderBy('id', 'desc')->value('id');
    }

    public function collection(Collection $rows)
    {
        $rowIdx = 1; // Baris heading = 1

        foreach ($rows as $row) {
            $rowIdx++;

            // Abaikan baris kosong
            $noInduk = isset($row['no_induk']) ? trim((string)$row['no_induk']) : null;
            if (empty($noInduk)) {
                continue;
            }

            $this->totalRows++;

            // Parsing Kelas
            $rawKelas = isset($row['kelas']) ? strtoupper(trim((string)$row['kelas'])) : '1A';
            if (empty($rawKelas)) {
                $rawKelas = '1A';
            }

            // Parsing Tanggal Khatam
            $rawDate = $row['tanggal_khatam'] ?? null;
            $tanggalKhatam = null;
            if (!empty($rawDate)) {
                try {
                    if (is_numeric($rawDate)) {
                        $tanggalKhatam = ExcelDate::excelToDateTimeObject($rawDate)->format('Y-m-d');
                    } else {
                        $tanggalKhatam = Carbon::parse(str_replace('/', '-', (string)$rawDate))->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $tanggalKhatam = date('Y-m-d');
                }
            } else {
                $tanggalKhatam = date('Y-m-d');
            }

            // Parsing Tahun Ajaran
            $idTahunAjaran = !empty($row['tahun_ajaran_id']) 
                ? intval($row['tahun_ajaran_id']) 
                : $this->defaultTahunAjaranId;

            // Keterangan
            $keterangan = isset($row['keterangan']) ? trim((string)$row['keterangan']) : null;

            // Cari Santri di santri_detail atau alumni di tb_alumni_santri_detail
            $santri = Santri::where('no_induk', $noInduk)->first();
            $santriId = null;
            $tipeSantri = 'santri';

            if ($santri) {
                $santriId = $santri->id;
                $tipeSantri = 'santri';
                $this->santriCount++;
            } else {
                $alumni = TbAlumniSantriDetail::where('no_induk', $noInduk)->first();
                if ($alumni) {
                    $tipeSantri = 'alumni';
                    $this->alumniCount++;
                } else {
                    // Jika tidak ditemukan di kedua tabel, simpan catatan warning
                    $this->errors[] = "Baris #{$rowIdx}: No Induk '{$noInduk}' tidak ditemukan di data santri aktif maupun alumni.";
                    continue;
                }
            }

            // Simpan / update data ke tbl_khotimin
            Khotimin::updateOrCreate(
                [
                    'kode_santri' => $noInduk,
                    'id_tahun_ajaran' => $idTahunAjaran,
                ],
                [
                    'santri_id' => $santriId,
                    'tipe_santri' => $tipeSantri,
                    'tanggal_khatam' => $tanggalKhatam,
                    'kelas' => $rawKelas,
                    'keterangan' => $keterangan,
                ]
            );

            $this->successCount++;
        }
    }
}
