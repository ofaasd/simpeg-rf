<?php

namespace App\Exports;

use App\Models\Khotimin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KhotiminExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected array $filters;
    protected int $rowNumber = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Khotimin::with(['santri', 'alumni', 'tahunAjaran']);

        // Filter Tahun Ajaran
        if (!empty($this->filters['id_tahun_ajaran'])) {
            $query->where('id_tahun_ajaran', $this->filters['id_tahun_ajaran']);
        }

        // Filter Rentang Tanggal Mulai
        if (!empty($this->filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_khatam', '>=', $this->filters['tanggal_mulai']);
        }

        // Filter Rentang Tanggal Selesai
        if (!empty($this->filters['tanggal_selesai'])) {
            $query->whereDate('tanggal_khatam', '<=', $this->filters['tanggal_selesai']);
        }

        // Filter Status Santri / Alumni
        if (!empty($this->filters['tipe_santri']) && $this->filters['tipe_santri'] !== 'all') {
            $query->where('tipe_santri', $this->filters['tipe_santri']);
        }

        // Filter Kelas
        if (!empty($this->filters['kelas']) && $this->filters['kelas'] !== 'all') {
            $query->where('kelas', strtoupper($this->filters['kelas']));
        }

        return $query->orderBy('tanggal_khatam', 'desc')->get();
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

    public function map($row): array
    {
        $this->rowNumber++;
        $nama = $row->santri->nama ?? ($row->alumni->nama ?? '-');
        $isAlumni = ($row->tipe_santri === 'alumni') || (!$row->santri && $row->alumni);
        $status = $isAlumni ? 'Alumni' : 'Santri Aktif';
        $ta = $row->tahunAjaran ? ($row->tahunAjaran->awal . '/' . $row->tahunAjaran->akhir) : '-';
        $semester = $row->tahunAjaran ? ('Semester ' . $row->tahunAjaran->jenis) : '-';
        $tgl = $row->tanggal_khatam ? $row->tanggal_khatam->format('d/m/Y') : '-';

        return [
            $this->rowNumber,
            $row->kode_santri,
            $nama,
            $status,
            strtoupper($row->kelas),
            $ta,
            $semester,
            $tgl,
            $row->keterangan ?? '-',
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
