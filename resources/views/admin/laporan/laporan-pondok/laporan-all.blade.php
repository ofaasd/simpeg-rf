<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
/* Gaya untuk dokumen */
body { 
    font-family: 'Times New Roman', serif; 
    font-size: 12pt; 
    line-height: 1.5; 
    margin: 0; 
    padding: 0; 
    background-color: white;
}

.contai {
    width: 210mm; /* Ukuran kertas A4 portrait */
    height: 297mm;
    margin: 0 auto;
    box-sizing: border-box;
    background-color: white;
}

h2 {
    text-align: center;
    font-size: 18pt;
    margin-bottom: 10px;
}

.table-container {
    margin-top: 10px;
    margin-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th, table td {
    border: 1px solid black;
    padding: 5px;
    text-align: left;
    vertical-align: middle;
    font-size: 10pt;
}

thead th {
    background-color: #e9ecef;
    font-weight: bold;
    text-align: center;
}

.text-center {
    text-align: center;
}

/* Gaya untuk cetak */
@media print {
    body {
        margin: 0;
    }
    .contai {
        box-shadow: none;
        margin: 0;
    }
    table {
        page-break-inside: auto;
    }
    thead {
        display: table-header-group;
    }
    tbody {
        display: table-row-group;
    }
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}
</style>
</head>
<body>
    @php
        \Carbon\Carbon::setLocale('id'); // Set locale ke Indonesia
    @endphp
<div class="contai">
    @if (isset($santri))
        <section id="santri">
            <div class="table-container table-responsive">
                <h2 style="font-size: 0.8rem;">Kesantrian</h2>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No Induk / NISN / NIK</th>
                            <th>Nama</th>
                            <th>Anak Ke</th>
                            <th>Tempat Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Kelas</th>
                            <th>Kamar</th>
                            <th>Tahfidz</th>
                            <th>Nama Ayah</th>
                            <th>Pendidikan Ayah</th>
                            <th>Pekerjaan Ayah</th>
                            <th>Nama Ibu</th>
                            <th>Pendidikan Ibu</th>
                            <th>Pekerjaan Ibu</th>
                            <th>No HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santri as $row)
                            <tr>
                                <td>
                                    <p>{{ $row->no_induk ?? '-' }}</p>
                                    <p>{{ $row->nisn ?? '-' }}</p>
                                    <p>{{ $row->nik ?? '-' }}</p>
                                </td>
                                <td>{{ $row->nama ?? '-' }}</td>
                                <td style="text-align: center">{{ $row->anak_ke ?? '-' }}</td>
                                <td>{{ $row->tempat_lahir ?? '-' }}, {{ !empty($row->tanggal_lahir) ? \Carbon\Carbon::parse($row->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                                <td style="text-align: center">{{ $row->jenis_kelamin ?? '-' }}</td>
                                <td>{{ $row->alamat ?? '-' }}, {{ $row->kelurahan ?? '-' }}, {{ $row->kecamatan ?? '-' }}, {{ $row->kabupatenKota ?? '-' }}, {{ $row->namaProvinsi ?? '-' }}. {{ $row->kode_pos ?? '-' }}</td>
                                <td style="text-align: center">{{ $row->kelas ?? '-' }}</td>
                                <td style="text-align: center">{{ $row->namaKamar ?? '-' }}</td>
                                <td>{{ $row->kelasTahfidz ?? '-' }}</td>
                                <td>{{ $row->nama_lengkap_ayah ?? '-' }}</td>
                                <td class="text-center">{{ strlen($row->pendidikan_ayah ?? '') <= 4 ? strtoupper($row->pendidikan_ayah ?? '') : $row->pendidikan_ayah }}</td>
                                <td>{{ $row->pekerjaan_ayah ?? '-' }}</td>
                                <td>{{ $row->nama_lengkap_ibu ?? '-' }}</td>
                                <td class="text-center">{{ strlen($row->pendidikan_ibu ?? '') <= 4 ? strtoupper($row->pendidikan_ibu ?? '') : $row->pendidikan_ibu }}</td>
                                <td>{{ $row->pekerjaan_ibu ?? '-' }}</td>
                                <td>{{ $row->no_hp ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center">Data Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @if (isset($staff))
        <section style="page-break-before: always; margin: 0;">
            <div class="table-container">
                <h2 style="font-size: 0.8rem;">Staff</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Pendidikan</th>
                            <th>Jabatan</th>
                            <th>Pengangkatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staff as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $row->nik }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->tempat_lahir }}{{ !empty($row->tanggal_lahir) ? ', ' . \Carbon\Carbon::parse($row->tanggal_lahir)->format('d-m-Y') : '' }}</td>
                                <td>{{ $row->jenis_kelamin }}</td>
                                <td>{{ $row->alamat }}</td>
                                <td>{{ $row->pendidikan }}</td>
                                <td>{{ $row->namaJabatan }}</td>
                                <td>{{ $row->pengangkatan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Data Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @if (isset($aset))
        <section style="page-break-before: always; margin: 0;">
            <div class="table-container">
                <h2 style="font-size: 0.8rem;">Aset</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bangunan</th>
                            <th>Barang</th>
                            <th>Elektronik</th>
                            <th>Ruang</th>
                            <th>Tanah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Maksimum jumlah baris untuk mengakomodasi data dengan iterasi
                            $maxRows = max(
                                count($aset['bangunan'] ?? []),
                                count($aset['barang'] ?? []),
                                count($aset['elektronik'] ?? []),
                                count($aset['ruang'] ?? []),
                                count($aset['tanah'] ?? [])
                            );
                        @endphp

                        @if ($maxRows > 0)
                            @for ($i = 0; $i < $maxRows; $i++)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $aset['bangunan'][$i]['nama'] ?? '-' }}</td>
                                    <td>{{ $aset['barang'][$i]['nama'] ?? '-' }}</td>
                                    <td>{{ $aset['elektronik'][$i]['nama'] ?? '-' }}</td>
                                    <td>{{ $aset['ruang'][$i]['nama'] ?? '-' }}</td>
                                    <td>{{ $aset['tanah'][$i]['nama'] ?? '-' }}</td>
                                </tr>
                            @endfor
                        @else
                            <tr>
                                <td colspan="6" class="text-center">Data Kosong</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @if (isset($psb))
        <section style="page-break-before: always; margin: 0;">
            <div class="table-container">
                <h2 style="font-size: 0.8rem;">Penerimaan Santri Baru Online</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Nama Panggilan</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat, Tanggal Lahir</th>
                            <th>Jumlah Saudara</th>
                            <th>Anak Ke</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Tanggal Validasi</th>
                            <th>Status Ujian</th>
                            <th>Status Diterima</th>
                            <th>Gelombang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($psb as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $row->nik }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->nama_panggilan }}</td>
                                <td style="text-align: center">{{ $row->jenis_kelamin }}</td>
                                <td>
                                    {{ $row->tempat_lahir }}, 
                                    {{ \Carbon\Carbon::createFromTimestamp($row->tanggal_lahir)->format('d/m/Y') }}
                                </td>
                                <td style="text-align: center">{{ $row->jumlah_saudara }}</td>
                                <td style="text-align: center">{{ $row->anak_ke }}</td>
                                <td>
                                    {{ $row->alamat }}, {{ $row->namaKeluarahan }}, 
                                    {{ $row->namaKecamatan }}, {{ $row->kotaKabupaten }}, 
                                    {{ $row->provinsi }}. {{ $row->kode_pos }}
                                </td>
                                <td>
                                    @switch($row->status)
                                        @case(0)
                                            Data Belum Divalidasi
                                            @break
                                        @case(1)
                                            Sudah Validasi
                                            @break
                                        @case(2)
                                            Data Diterima oleh Panitia PSB
                                            @break
                                        @case(4)
                                            Peserta Lulus
                                            @break
                                        @default
                                            Status Tidak Dikenal
                                    @endswitch
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::createFromTimestamp($row->tanggal_validasi)->format('d/m/Y') }}
                                </td>
                                <td>
                                    @switch($row->status_ujian)
                                        @case(0)
                                            Belum Ujian
                                            @break
                                        @case(1)
                                            Lulus
                                            @break
                                        @case(2)
                                            Tidak Lulus
                                            @break
                                        @default
                                            Status Tidak Dikenal
                                    @endswitch
                                </td>
                                <td>
                                    @switch($row->status_diterima)
                                        @case(0)
                                            Dalam Proses
                                            @break
                                        @case(1)
                                            Diterima
                                            @break
                                        @case(2)
                                            Tidak Diterima
                                            @break
                                        @default
                                            Status Tidak Dikenal
                                    @endswitch
                                </td>
                                <td style="text-align: center">{{ $row->gelombang }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Data PSB tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @if (isset($cekLaporanPembayaran))
        <section style="page-break-before: always; margin: 0;">
            <div class="table-container">
                <h2 style="font-size: 0.8rem;">Laporan Pembayaran</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            @foreach (range(1, 12) as $month)
                                <th class="text-center">{{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cekLaporanPembayaran as $index => $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $row['namaWali'] }}</td>
                                @foreach ($row['pembayaran'] as $status)
                                    <td class="text-center">{{ $status ? 'v' : '-' }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif


    @if (isset($keuanganValidasi))
        <section style="page-break-before: always; margin: 0;">
            <div class="table-container">
                <h2 style="font-size: 0.8rem;">Validasi Pembayaran</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Total Valid</th>
                            <th>Total Tidak Valid</th>
                            <th>Pembayaran Valid</th>
                            <th>Pembayaran Tidak Valid</th>
                            <th>Jumlah Tunggakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keuanganValidasi as $row)
                            <tr>
                                <td class="text-center">{{ \Carbon\Carbon::create()->month($row['month'])->translatedFormat('F') }}</td>
                                <td class="text-right">{{ number_format($row['total_valid'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($row['total_invalid'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($row['pembayaranValid'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($row['pembayaranTidakValid'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($row['jumlahTunggakan'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    
    @if (isset($pemeriksaanSantri))
        <section style="page-break-before: always; margin: 0;">
            <div class="table-container">
                <h2 style="font-size: 0.8rem;">Pemeriksaan Santri</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Santri</th>
                            <th>Tinggi Badan (cm)</th>
                            <th>Berat Badan (kg)</th>
                            <th>Lingkar Pinggul (cm)</th>
                            <th>Lingkar Dada (cm)</th>
                            <th>Kondisi Gigi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemeriksaanSantri as $namaMurroby => $santris)
                            {{-- Header Grup untuk Nama Murroby --}}
                            <tr style="font-weight: bold; background-color: #f2f2f2;">
                                <td colspan="7" style="text-align: center;">{{ $namaMurroby }}</td>
                            </tr>

                            {{-- Data Santri di bawah Grup --}}
                            @foreach ($santris as $santri)
                                <tr>
                                    <td class="text-center">{{ $loop->parent->iteration . '.' . $loop->iteration }}</td>
                                    <td>{{ $santri->nama }}</td>
                                    <td class="text-center">{{ $santri->tinggiBadan ?? '-' }}</td>
                                    <td class="text-center">{{ $santri->beratBadan ?? '-' }}</td>
                                    <td class="text-center">{{ $santri->lingkarPinggul ?? '-' }}</td>
                                    <td class="text-center">{{ $santri->lingkarDada ?? '-' }}</td>
                                    <td>{{ $santri->kondisiGigi ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif


    @if (isset($kesehatanSantri))
        <section style="page-break-before: always; margin: 0;">
            <div class="table-container">
                <h2 style="font-size: 0.8rem;">Kesehatan Santri</h2>
                <table class="dataTable table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            {{-- <th>Murroby</th> --}}
                            <th>Tgl Sakit</th>
                            <th>Gangguan Kesehatan</th>
                            <th>Keterangan</th>
                            <th>Tindakan</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kesehatan as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kesehatanSantri[$row->santri_id]->nama ?? '-' }}</td>
                                <td style="text-transform: uppercase;">{{ $kesehatanSantri[$row->santri_id]->kelas ?? '-' }}</td>
                                {{-- <td>{{ $kesehatanSantri[$row->santri_id]->kamar_id ?? '-' }}</td> --}}
                                <td>{{ $row->tanggal_sakit ? \Carbon\Carbon::createFromTimestamp($row->tanggal_sakit)->format('d-m-Y') : '-' }}</td>
                                <td>{{ $row->sakit ?? '-' }}</td>
                                <td>{{ $row->keterangan_sakit ?? '-' }}</td>
                                <td>{{ $row->tindakan ?? '-' }}</td>
                                <td>{{ $row->keterangan_sembuh ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

</div>
</body>
</html>