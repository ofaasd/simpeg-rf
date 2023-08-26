<table id='golongan'>
  <thead>
    <tr>
      <th>Golongan Ruang</th>
      <th>TMT</th>
      <th>Sampai</th>
      <th>Keterangan</th>
      <th>Hapus</th>
    </tr>
  </thead>
  <tbody>

  </tbody>
</table>
<a href="#" class="btn btn-primary me-sm-3 me-1 data-submit" id='tambah'>Tambah Baris</a>
<script>
  window.onload = () =>{
    const t = $("#golongan").dataTable();
    const tambah = $("#tambah");
    tambah.click(() => {
      t.draw
    });
  }
</script>
