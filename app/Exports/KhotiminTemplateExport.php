<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KhotiminTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'no_induk',
            'kelas',
            'tanggal_khatam',
            'tahun_ajaran_id',
            'keterangan',
        ];
    }

    public function array(): array
    {
        return [
            [
                '1052',
                '6A',
                '2026-11-20',
                '1',
                'Contoh: Khatam 30 Juz Al-Qur\'an (Santri Aktif)',
            ],
            [
                '644',
                '6B',
                '2025-05-15',
                '1',
                'Contoh: Khatam 30 Juz (Alumni)',
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
                    'startColor' => ['rgb' => '0D6EFD'],
                ],
            ],
        ];
    }
}
