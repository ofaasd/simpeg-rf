<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    * { font-family: DejaVu Sans, sans-serif; font-size:9pt}
    </style>
</head>
<body>
    <table width="100%" border=0>
        {{-- <tr>
            <td></td>
            <td width="10"></td>
            <td width="10%"></td>
            <td width="30%">Nomor Registrasi</td>
            <td >0123123123</td>
        </tr> --}}
        <tr>
            <td rowspan="3" width="15%"><img src="https://payment.ppatq-rf.id/assets/images/logo.png" alt=""></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            {{-- <td width="30%">Tahun Pelajaran</td> --}}
            {{-- <td>2025</td> --}}
        </tr>
        {{-- <tr>
            <td colspan="4">Jl. KH. Abdullah Km. 2 Bermi-Gembong -Pati 59162</td>
        </tr>
        <tr>
            <td>Website</td>
            <td colspan="3">www.ppatq-rf.sch.id</td>
        </tr>
        <tr>
            <td>Email</td>
            <td colspan="3">ppatqrf@gmail.com</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">&nbsp;</td>
        </tr> --}}
        {{-- <tr>
            <td colspan="5" align="center">المؤسسة روضة الفلاح</td>
        </tr>
        <tr>
            <td colspan="5" align="center"><img src="{{asset('assets/images/')}}/Gambar1.png" alt="" height="30" align="center"></td>
        </tr>--}}
        <tr>
            <td colspan="5" align="center"><b>FORMULIR PENDAFTARAN SANTRI BARU PERIODE 2025</b><br /><b>PONDOK PESANTREN ANAK TAHFIDZUL QUR'AN RAUDLATUL  FALAH</b></td>
        </tr>
        <tr>
            <td colspan="5" align="center">Jl. KH. Abdullah Km. 2 Bermi-Gembong -Pati 59162 <br />www.ppatq-rf.sch.id  ||  www.ppatq-rf.id</td>
        </tr>
        <tr>
            <td colspan="5"><b>Data Santri Baru</b></td>
        </tr>
        {{-- <tr>
            <td valign="top">Username</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td valign="top">Password</td>
            <td colspan="4">{{$password}}</td>
        </tr> --}}
        <tr>
            <td valign="top" >Nama Lengkap</td>
            <td valign="top" colspan="3">{{$psb_peserta->nama}}</td>
            <td valign="top" rowspan="8">
                @if(!empty($berkas->file_photo))
                    <img src="{{URL::to('')}}/assets/images/upload/foto_casan/{{$berkas->file_photo}}" width="150">
                @endif
                <br /><br />
                Pendaftaran : <br />
                Username : {{$user->username}}<br />
                Password : {{$password}}<br />
            </td>
        </tr>
        <tr>
            <td valign="top">Nama Panggilan</td>
            <td valign="top" colspan="3">{{$psb_peserta->nama_panggilan ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">Jenis Kelamin</td>
            <td valign="top" colspan="3">{{($psb_peserta->jenis_kelamin=='L')?'Laki-laki':'Perempuan'}}</td>
        </tr>
        <tr>
            <td valign="top" width="15%">Tempat Tanggal Lahir</td>
            <td valign="top" width="15%">{{$psb_peserta->tempat_lahir}}, {{date('d-m-Y', strtotime($psb_peserta->tanggal_lahir))}}</td>
            <td valign="top" width="15%">Usia</td>
            <td valign="top" width="15%">{{$umur_tahun ?? '<Kosong>'}} Tahun, {{$umur_bulan ?? '<Kosong>'}} Bulan</td>
        </tr>
        <tr>
            <td valign="top">Anak Ke</td>
            <td valign="top" colspan="3">{{$psb_peserta->anak_ke ?? '<Kosong>'}} dari {{$psb_peserta->jumlah_saudara ?? '<Kosong>'}}  bersaudara</td>
        </tr>
        <tr>
            <td valign="top">Alamat Lengkap</td>
            <td valign="top" colspan="3">{{$psb_peserta->alamat ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">Kelurahan</td>
            <td valign="top">{{$kelurahan->nama_kelurahan ?? '<Kosong>'}}</td>
            <td valign="top">Kecamatan</td>
            <td valign="top" >{{$kecamatan->nama_kecamatan ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">Kota</td>
            <td valign="top">{{$kota->nama_kota_kab ?? '<Kosong>'}}</td>
            <td valign="top">Provinsi</td>
            <td valign="top">{{$provinsi->nama_provinsi ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td colspan="5" valign="top"><b>Data Wali Santri</b></td>
        </tr>
        <tr>
            <td valign="top">Nama Ayah</td>
            <td valign="top">{{$psb_wali->nama_ayah ?? '<Kosong>'}}</td>
            <td valign="top">Nama Ibu</td>
            <td valign="top" colspan="2">{{$psb_wali->nama_ibu ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">Pendidikan Ayah</td>
            <td valign="top">{{$list_pendidikan[$psb_wali->pendidikan_ayah] ?? '<Kosong>'}}</td>
            <td valign="top">Pendidikan Ibu</td>
            <td valign="top" colspan="2">{{$list_pendidikan[$psb_wali->pendidikan_ibu] ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">Pekerjaan Ayah</td>
            <td valign="top">{{$psb_wali->pekerjaan_ayah ?? '<Kosong>'}}</td>
            <td valign="top">Pekerjaan Ibu</td>
            <td valign="top" colspan="2">{{$psb_wali->pekerjaan_ibu ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">No. HP</td>
            <td valign="top">{{$psb_wali->no_hp}}</td>
            <td valign="top">Nomor Telpon Rumah</td>
            <td valign="top" colspan="4">{{$psb_wali->no_telp ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top" colspan="5" ><b>Sekolah Asal / Pindahan</b></td>
        </tr>
        <tr>
            <td valign="top">Dari</td>
            <td valign="top" colspan="0">{{$jenjang[$psb_asal->jenjang] ?? '<Kosong>'}}</td>
            <td valign="top">NPSN</td>
            <td valign="top" colspan="2">{{$psb_asal->npsn ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">Nama Sekolah</td>
            <td valign="top" colspan="0">{{$psb_asal->nama_sekolah ?? '<Kosong>'}}</td>
            <td valign="top">NISN</td>
            <td valign="top" colspan="2">{{$psb_asal->nisn ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">NSM/NSS</td>
            <td valign="top" colspan="4">{{$psb_asal->nss ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top" colspan="5" ><b>Ukuran Seragam</b></td>
        </tr>
        <tr>
            <td valign="top">Berat Badan</td>
            <td valign="top" colspan="0">{{$psb_seragam->berat_badan ?? '<Kosong>'}} KG</td>
            <td valign="top">Lingkar Dada</td>
            <td valign="top" colspan="2">{{$psb_seragam->lingkar_dada ?? '<Kosong>'}} CM</td>
        </tr>
        <tr>
            <td valign="top">Tinggi Badan</td>
            <td valign="top" colspan="0">{{$psb_seragam->tinggi_badan ?? '<Kosong>'}} CM</td>
            <td valign="top">Lingkar Pinggul</td>
            <td valign="top" colspan="2">{{$psb_seragam->lingkar_pinggul ?? '<Kosong>'}} CM</td>
        </tr>
        <tr>
            <td valign="top" colspan="5" ><b>Berkas Pendukung</b></td>
        </tr>
        <tr>
            <td valign="top">Photo Calon Santri</td>
            <td valign="top" colspan="4">{{(!empty($berkas->file_photo))? 'Ada (Terakhir Diperbaharui ' . date('d-m-Y',strtotime($berkas->updated_at)) . ')' : '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">File KK</td>
            <td valign="top" colspan="4">{{(!empty($berkas->file_kk))? 'Ada (Terakhir Diperbaharui ' . date('d-m-Y',strtotime($berkas->updated_at)) . ')' : '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">File KTP</td>
            <td valign="top" colspan="4">{{(!empty($berkas->file_ktp))? 'Ada (Terakhir Diperbaharui ' . date('d-m-Y',strtotime($berkas->updated_at)) . ')' : '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">File Rapor/Ijazah</td>
            <td valign="top" colspan="4">{{(!empty($berkas->file_rapor))? 'Ada (Terakhir Diperbaharui ' . date('d-m-Y',strtotime($berkas->updated_at)) . ')' : '<Kosong>'}}</td>
        </tr>
        <tr>
            <td colspan="5" valign="top"><b>Status Pembayaran</b></td>
        </tr>
        <tr>
            <td valign="top">Nama Bank</td>
            <td valign="top" colspan="0">{{$bukti->bank ?? '<Kosong>'}}</td>
            <td valign="top">Bukti Pembayaran</td>
            <td valign="top" colspan="2">{{(!empty($bukti->bukti))? 'Ada' : '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">Atas Nama</td>
            <td valign="top" colspan="0">{{$bukti->atas_nama ?? '<Kosong>'}}</td>
            <td valign="top">Status Pembayaran</td>
            @php $stats = 0; if(!empty($bukti->status)) $stats = $bukti->status @endphp
            <td valign="top" colspan="2">{{$status_pembayaran[$stats] ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top">No. Rekening</td>
            <td valign="top" colspan="4">{{$bukti->no_rekening ?? '<Kosong>'}}</td>
        </tr>
        <tr>
            <td valign="top" colspan="5" style="background:red;color:white; padding:10px;">Digenerate Oleh Sistem PPATQ-RF.ID | Terakhir Di update {{date('Y-m-d H:i:s',strtotime($psb_peserta->updated_at))}}</td>
        </tr>
    </table>
</body>
</html>
