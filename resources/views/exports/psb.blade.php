<table>
  <thead>
  <tr>
    <th>No. </th>
    <th>No. Tes</th>
    <!-- <th>No. Tes</th> -->
    <th>No. Pendaftaran/Username</th>
    <th>Nama</th>
    <th>NISN</th>
    <th>Anak ke</th>
    <th>Jml Sdr</th>
    <th>Tempat Lahir</th>
    <th>Tanggal Lahir</th>
    <th>Usia</th>
    <th>JK</th>
    <th>Alamat</th>
    <th>Desa/Kelurahan</th>
    <th>Kecamatan</th>
    <th>Kota</th>
    <th>Provinsi</th>
    <th>Kode Pos</th>
    <th>Nama Ayah</th>
    <th>Pendidikan Ayah</th>
    <th>Pekerjaan Ayah</th>
    <th>Nama Ibu</th>
    <th>Pendidikan Ibu</th>
    <th>Pekerjaan Ibu</th>
    <th>No. HP</th>
    <th>Tinggi Badan</th>
    <th>Berat Badan</th>
    <th>Lingkar Dada</th>
    <th>Lingkar Pinggul</th>
    <th>Password</th>
    <th>Status Pembayaran</th>
    <th>Tanggal Pembayaran</th>
  </tr>
  </thead>
  <tbody>
  @php
  $i = 1;
  @endphp
  @foreach($psb as $row)
  @php
  $no_tes = explode('.',$row->no_pendaftaran);
  $list_pen = [1 => 'S2/S3', 'S1' , 'Diploma', 'SMA/MA', 'SMP/MTS', 'SD/MI'];
  @endphp
      <tr>
          <td>{{ $i }}</td>
          <td>RF-{{(strlen($i) == 1)?"0".$i:$i}}</td>
          <!-- <td>{{ $no_tes[2] }}</td> -->
          <td>{{ $row->no_pendaftaran }}</td>
          <td>{{ $row->nama }}</td>
          <td>{{ $psb_asal[$row->id]->nisn }}</td>
          <td>{{ $row->anak_ke }}</td>
          <td>{{ $row->jumlah_saudara }}</td>
          <td>{{ $row->tempat_lahir }}</td>
          <td>{{ date('d-m-Y',$row->tanggal_lahir) }}</td>
          <td>{{ $tahun[$row->id] }}</td>
          <td>{{ $row->jenis_kelamin }}</td>
          <td>{{ $row->alamat }}</td>
          <td>{{ Helper_user::getKelurahan($row->prov_id,$row->kota_id,$row->kecamatan,$row->kelurahan) ?? '' }}</td>
          <td>{{ Helper_user::getKecamatan($row->prov_id,$row->kota_id,$row->kecamatan) ?? '' }}</td>
          <td>{{ Helper_user::getKota($row->prov_id,$row->kota_id) ?? '' }}</td>
          <td>{{ Helper_user::getProvinsi($row->prov_id) ?? '' }}</td>
          <td>{{ $row->kode_pos }}</td>
          <td>{{ $psb_wali[$row->id]->nama_ayah }}</td>
          <td>{{ $list_pen[$psb_wali[$row->id]->pendidikan_ayah] }}</td>
          <td>{{ $psb_wali[$row->id]->pekerjaan_ayah }}</td>
          <td>{{ $psb_wali[$row->id]->nama_ibu }}</td>
          <td>{{ $list_pen[$psb_wali[$row->id]->pendidikan_ibu] }}</td>
          <td>{{ $psb_wali[$row->id]->pekerjaan_ibu }}</td>
          <td>{{ $psb_wali[$row->id]->no_hp }}</td>
          <td>{{ $psb_seragam[$row->id]->tinggi_badan }}</td>
          <td>{{ $psb_seragam[$row->id]->berat_badan }}</td>
          <td>{{ $psb_seragam[$row->id]->lingkar_dada }}</td>
          <td>{{ $psb_seragam[$row->id]->lingkar_pinggul }}</td>
          <td>{{ $psb_user[$row->id]->password_ori }}</td>
          <td>{{ $status_bayar[$bukti_bayar[$row->id]]}}</td>
          <td>{{ $tanggal_bayar[$row->id]}}</td>
      </tr>
  @php
  $i++;
  @endphp
  @endforeach
  </tbody>
</table>
