
<table class="dataTable">
  <thead>
    <tr>
      <td>No.</td>
      <td>Tgl</td>
      <td>TB</td>
      <td>BB</td>
      <td>LP</td>
      <td>LD</td>
      <td>Gigi</td>
      <td>Action</td>
    </tr>
  </thead>
  <tbody>
    @php $i=1; @endphp
    @foreach($var['pemeriksaan'] as $pemeriksaan)
    <tr>
      <td>{{$i}}</td>
      <td>{{date('d-m-Y',$pemeriksaan->tanggal_pemeriksaan)}}</td>
      <td>{{$pemeriksaan->tinggi_badan}}</td>
      <td>{{$pemeriksaan->berat_badan}}</td>
      <td>{{$pemeriksaan->lingkar_pinggul}}</td>
      <td>{{$pemeriksaan->lingkar_dada}}</td>
      <td>{{$pemeriksaan->kondisi_gigi}}</td>
      <td><button class="btn btn-primary btn-xs edit_pemeriksaan" data-bs-toggle="modal" data-bs-target="#modal_pemeriksaan" data-id="{{$pemeriksaan->id}}"><span class="mdi mdi-pencil"></span></button><button class="btn btn-danger btn-xs delete-record" data-id="{{$pemeriksaan->id}}"><span class="mdi mdi-delete"></span></button></td>
    </tr>
    @php $i++; @endphp
    @endforeach
  </tbody>
</table>
<script>
  $('#modal_pemeriksaan').on('hidden.bs.modal', function () {
      $('#formPemeriksaan').trigger("reset");
  });
  $('#modal_pemeriksaan').on('shown.bs.modal', function () {
      $("#no_induk").val("{{$var['santri']->no_induk}}");
  });
  $('.dataTable').dataTable();
  $('#formPemeriksaan').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    //showBlock();
    // insert_update(formData);
  });
  $(document).on('click', '.edit_pemeriksaan', function () {
    const id = $(this).data('id');
    const status = $(this).data('status');
    // get data
    $.get(''.concat(baseUrl).concat('santri/pemeriksaan/').concat(id), function (data) {
    let date = '';
    Object.keys(data).forEach(key => {
        //console.log(key);

        if(key == 'id'){
          $('#id_pemeriksaan')
            .val(data[key])
            .trigger('change');
        }else if(key == 'tanggal_pemeriksaan'){
          tanggal = parseInt(data[key]) * 1000;
          date = new Date(tanggal).toLocaleString("sv-SE", {timeZone: "Asia/Jakarta"}).slice(0, 10);
          $('#' + key)
            .val(date)
            .trigger('change');
            //alert(date);
        }else if(key == 'tanggal_sembuh'){
          if(parseInt(data[key]) > 0){
            tanggal = parseInt(data[key]) * 1000;
            date = new Date(tanggal).toLocaleString("sv-SE", {timeZone: "Asia/Jakarta"}).slice(0, 10);
            $('#' + key)
              .val(date)
              .trigger('change');
              //alert(date);
          }
        }else{
          $('#' + key)
              .val(data[key])
              .trigger('change');
        }
    });
    });
  });
  $(document).on('click', '.delete-record', function () {
    const id = $(this).data('id');
    const bulan = $("#bulan").val();
    const tahun = $("#tahun").val();

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: ''.concat(baseUrl).concat('santri/delete_pemeriksaan/').concat(id),
          success: function success() {
            reload_table();
          },
          error: function error(_error) {
            console.log(_error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The Record has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The record is not deleted!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
</script>
