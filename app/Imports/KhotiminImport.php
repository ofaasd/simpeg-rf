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

            // Abaikan baris kosong atau baris separator/contoh yang tidak memiliki nomor induk
            $noInduk = isset($row['no_induk']) 
                ? trim((string)$row['no_induk']) 
                : (isset($row['kode_santri']) ? trim((string)$row['kode_santri']) : null);

            // Bersihkan format jika ada prefix non-angka (misal "ID: ")
            if (empty($noInduk) || !preg_match('/\d+/', $noInduk)) {
                continue;
            }

            // Ambil hanya digit untuk No Induk
            preg_match('/(\d+)/', $noInduk, $matches);
            $cleanNoInduk = $matches[1] ?? $noInduk;

            $this->totalRows++;

            // 1. Parsing Kelas (Toleran dari 'kelas_saat_khatam' maupun 'kelas')
            $rawKelas = isset($row['kelas_saat_khatam']) 
                ? strtoupper(trim((string)$row['kelas_saat_khatam']))
                : (isset($row['kelas']) ? strtoupper(trim((string)$row['kelas'])) : '1A');

            // Hapus spasi pada kelas, misal "6 A" -> "6A"
            $rawKelas = str_replace(' ', '', $rawKelas);
            if (empty($rawKelas)) {
                $rawKelas = '1A';
            }

            // 2. Parsing Tanggal Khatam
            $rawDate = $row['tanggal_khatam'] ?? null;
            $tanggalKhatam = null;
            if (!empty($rawDate)) {
                try {
                    if (is_numeric($rawDate)) {
                        $tanggalKhatam = ExcelDate::excelToDateTimeObject($rawDate)->format('Y-m-d');
                    } else {
                        $cleanDateStr = str_replace('/', '-', trim((string)$rawDate));
                        $tanggalKhatam = Carbon::parse($cleanDateStr)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $tanggalKhatam = date('Y-m-d');
                }
            } else {
                $tanggalKhatam = date('Y-m-d');
            }

            // 3. Parsing Tahun Ajaran & Semester secara Cerdas
            // Bisa menerima: "2025/2026", "2025-2026", "2025", maupun ID angka (misal: 1)
            $rawTa = $row['tahun_ajaran'] ?? ($row['tahun_ajaran_id'] ?? null);
            $rawSemester = $row['semester'] ?? null;
            $idTahunAjaran = null;

            if (!empty($rawTa)) {
                $taStr = trim((string)$rawTa);

                // Jika berupa ID murni angka kecil yang cocok dengan ID ref_tahun_ajaran
                if (is_numeric($taStr) && intval($taStr) < 1000) {
                    $foundById = TahunAjaran::find(intval($taStr));
                    if ($foundById) {
                        $idTahunAjaran = $foundById->id;
                    }
                }

                // Jika berupa format teks tahun (misal "2025/2026" atau "2025")
                if (!$idTahunAjaran && preg_match('/(\d{4})/', $taStr, $yearMatch)) {
                    $awalTahun = $yearMatch[1];
                    $taQuery = TahunAjaran::where('awal', $awalTahun);

                    // Cek jika semester diisi
                    if (!empty($rawSemester)) {
                        $semStr = strtolower(trim((string)$rawSemester));
                        $jenis = (str_contains($semStr, '2') || str_contains($semStr, 'genap')) ? 2 : 1;
                        $taQuery->where('jenis', $jenis);
                    }

                    $foundByYear = $taQuery->first() ?? TahunAjaran::where('awal', $awalTahun)->first();
                    if ($foundByYear) {
                        $idTahunAjaran = $foundByYear->id;
                    }
                }
            }

            // Fallback ke default Tahun Ajaran Aktif jika belum terpetakan
            if (!$idTahunAjaran) {
                $idTahunAjaran = $this->defaultTahunAjaranId;
            }

            // 4. Keterangan
            $keterangan = isset($row['keterangan']) ? trim((string)$row['keterangan']) : null;

            // 5. Validasi & Relasi Santri (Cek santri_detail lalu tb_alumni_santri_detail)
            $santri = Santri::where('no_induk', $cleanNoInduk)->first();
            $santriId = null;
            $tipeSantri = 'santri';

            if ($santri) {
                $santriId = $santri->id;
                $tipeSantri = 'santri';
                $this->santriCount++;
            } else {
                $alumni = TbAlumniSantriDetail::where('no_induk', $cleanNoInduk)->first();
                if ($alumni) {
                    $tipeSantri = 'alumni';
                    $this->alumniCount++;
                } else {
                    // Jika tidak ditemukan di kedua tabel
                    $this->errors[] = "Baris #{$rowIdx}: No Induk '{$cleanNoInduk}' tidak ditemukan di database santri aktif maupun alumni.";
                    continue;
                }
            }

            // 6. Simpan atau perbarui data ke tbl_khotimin
            Khotimin::updateOrCreate(
                [
                    'kode_santri' => (string)$cleanNoInduk,
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
