<?php

namespace App\Exports;

use App\Models\TahunAjaran;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KhotiminTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new KhotiminTemplateDataSheet(),
            new KhotiminTemplatePetunjukSheet(),
        ];
    }
}

/**
 * Sheet 1: Data Khotimin (Format sama persis dengan hasil export)
 */
class KhotiminTemplateDataSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'Data Khotimin';
    }

    public function headings(): array
    {
        return [
            'No',
            'No Induk',
            'Nama Santri / Alumni',
            'Status',
            'Kelas Saat Khatam',
            'Tahun Ajaran',
            'Semester',
            'Tanggal Khatam',
            'Keterangan',
        ];
    }

    public function array(): array
    {
        $activeTa = TahunAjaran::where('is_aktif', 1)->first() ?? TahunAjaran::orderBy('id', 'desc')->first();
        $taStr = $activeTa ? ($activeTa->awal . '/' . $activeTa->akhir) : '2025/2026';
        $semesterStr = $activeTa ? ('Semester ' . $activeTa->jenis) : 'Semester 1';

        return [
            [
                '1',
                '1052',
                'Contoh: Achmad Sulthan Hafiz',
                'Santri Aktif',
                '6A',
                $taStr,
                $semesterStr,
                '20/11/2026',
                'Contoh: Khatam 30 Juz Al-Qur\'an',
            ],
            [
                '2',
                '644',
                'Contoh: Achmad Fazdil Lailul Mubarok',
                'Alumni',
                '6B',
                $taStr,
                $semesterStr,
                '15/05/2025',
                'Contoh: Khatam Kitab Jurumiyyah',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '696CFF'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}

/**
 * Sheet 2: Petunjuk & Referensi Pengisian
 */
class KhotiminTemplatePetunjukSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'Petunjuk & Referensi';
    }

    public function headings(): array
    {
        return [
            'Nama Kolom',
            'Status Wajib',
            'Format / Contoh Nilai',
            'Keterangan & Ketentuan Relasi',
        ];
    }

    public function array(): array
    {
        $rows = [
            [
                'No',
                'Opsional',
                '1, 2, 3, dst.',
                'Nomor urut baris (bebas / tidak diproses sistem).',
            ],
            [
                'No Induk',
                'WAJIB',
                'Contoh: 1052 atau 644',
                'Kunci identitas utama santri. Harus sesuai dengan No Induk yang ada pada data Santri Aktif atau Alumni.',
            ],
            [
                'Nama Santri / Alumni',
                'Opsional',
                'Contoh: Ahmad Fauzi',
                'Hanya referensi visual pengguna. Nama resmi akan otomatis dicocokkan oleh sistem berdasarkan No Induk.',
            ],
            [
                'Status',
                'Opsional',
                'Santri Aktif / Alumni',
                'Otomatis terdeteksi oleh sistem apakah No Induk terdaftar sebagai Santri Aktif atau Alumni.',
            ],
            [
                'Kelas Saat Khatam',
                'WAJIB',
                '1A, 2A, 3A, 4A, 5A, 6A atau 1B, 2B, 3B, 4B, 5B, 6B',
                'Pilihan kelas saat khatam. Wajib menggunakan format huruf kapital dan angka tanpa spasi.',
            ],
            [
                'Tahun Ajaran',
                'Opsional',
                'Contoh: 2025/2026 atau 2025',
                'Format tahun ajaran. Jika dikosongkan, sistem otomatis menggunakan Tahun Ajaran Aktif saat import.',
            ],
            [
                'Semester',
                'Opsional',
                'Semester 1 / Semester 2 (atau 1 / 2)',
                'Semester penilaian. Jika dikosongkan, disesuaikan dengan semester tahun ajaran.',
            ],
            [
                'Tanggal Khatam',
                'WAJIB',
                '20/11/2026 atau 2026-11-20',
                'Tanggal pelaksanaan khatam. Format DD/MM/YYYY atau YYYY-MM-DD atau format tanggal cell Excel.',
            ],
            [
                'Keterangan',
                'Opsional',
                'Contoh: Khatam 30 Juz / Khatam Kitab',
                'Catatan tambahan terkait khataman.',
            ],
        ];

        // Tambahkan baris pemisah referensi Tahun Ajaran yang tersedia
        $rows[] = ['', '', '', ''];
        $rows[] = ['--- DAFTAR TAHUN AJARAN SISTEM ---', '', '', ''];

        $taList = TahunAjaran::orderBy('id', 'desc')->get();
        foreach ($taList as $ta) {
            $statusStr = $ta->is_aktif ? '★ AKTIF' : 'Tidak Aktif';
            $rows[] = [
                "ID: {$ta->id}",
                $statusStr,
                "{$ta->awal}/{$ta->akhir} (Semester {$ta->jenis})",
                "Gunakan teks '{$ta->awal}/{$ta->akhir}' pada kolom Tahun Ajaran",
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '28C76F'],
                ],
            ],
        ];
    }
}
