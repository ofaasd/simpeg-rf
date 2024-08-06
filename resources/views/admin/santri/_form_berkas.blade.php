<table class="table">
  <thead>
    <tr>
      <td>Nama Berkas</td>
      <td>URL</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>File KK</td>
      <td>
        @if(!empty($var['berkas']->file_kk))
          <a target="_blank" href="https://payment.ppatq-rf.id/assets/upload/berkas/{{$var['berkas']->file_kk}}" class="btn btn-primary">Lihat File</a>
        @else
          <span class="btn btn-danger">Belum Ada FIle</span>
        @endif
      </td>
    </tr>
    <tr>
      <td>File Akta</td>
      <td>
        @if(!empty($var['berkas']->file_akta))
          <a target="_blank" href="https://payment.ppatq-rf.id/assets/upload/berkas/{{$var['berkas']->file_akta}}" class="btn btn-primary">Lihat File</a>
        @else
          <span class="btn btn-danger">Belum Ada FIle</span>
        @endif
      </td>
    </tr>
  </tbody>
</table>
