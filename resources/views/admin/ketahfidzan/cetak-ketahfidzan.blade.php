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

table {
    width: 100%;
    border-collapse: collapse;
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
    <section style="page-break-before: always; margin: 0;">
        <div class="table-container">
            <h2 style="font-size: 0.8rem;">Laporan Ketahfidzan {{ $nmMurroby }}. Tahun {{ $tahun }}</h2>
            <table class="dataTable table">
                <thead>
                    @php
                        \Carbon\Carbon::setLocale('id');
                    @endphp
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            @if ($isAll)
                                <th>Murroby</th>
                            @endif
                            @foreach (range(1, 12) as $bulan)
                                <th>{{ \Carbon\Carbon::createFromFormat('!m', $bulan)->translatedFormat('F') }}</th>
                            @endforeach
                        </tr>
                </thead>
                <tbody>
                @php $no = 1; @endphp
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row['nmSantri'] }} ({{ $row['klsSantri'] }})</td>
                            @if ($isAll)
                                <td>{{ $row['nmMurroby'] }}</td>
                            @endif
                            @foreach ($row['bulan'] as $maxJuzSurah)
                                <td>{{ $maxJuzSurah }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
</body>
</html>