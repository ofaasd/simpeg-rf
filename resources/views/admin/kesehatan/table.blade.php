<table class="dataTable table">
  <thead>
    <tr>
      <td>Nama</td>
      <td>Kelas</td>
      <td>Murroby</td>
      <td>Tgl Sakit</td>
      <td>Gangguan Kesehatan</td>
      <td>Keterangan</td>
      <td>Tindakan</td>
      <td>Deskripsi</td>
      <td>Keterangan</td>
      <td>Aksi</td>
    </tr>
  </thead>
  <tbody id="table_uang_saku">
    @php
    $i = 1;
    @endphp
    @foreach($kesehatan as $row)
      <tr>
        <td>{{$list_santri[$row->santri_id]->nama}}</td>
        <td>{{$list_santri[$row->santri_id]->kelas}}</td>
        <td>{{$list_santri[$row->santri_id]->kamar_id}}</td>
        <td>{{date('d-m-Y', $row->tanggal_sakit)}}</td>
        <td>{{$row->sakit}}</td>
        <td>{{$row->keterangan_sakit}}</td>
        <td>{{$row->tindakan}}</td>
        <td>{{$row->keterangan_sembuh}}</td>
        <td>{{$row->keterangan_sembuh}}</td>
        <td>
          <div class="btn-group btn-group-sm" role="group" aria-label="First group">
            {{-- <button type="button" id="btnSakit" data-id="{{$row->id}}" class="btn btn-primary edit_sakit waves-effect" data-bs-toggle="modal" data-bs-target="#modal_sakit" data-status="sakit"><i class="mdi mdi-pencil me-1"></i></button> --}}
            <button type="button" id="btnSembuh" data-id="{{$row->id}}" class="btn btn-success edit_sakit waves-effect" data-bs-toggle="modal" data-bs-target="#modal_sakit" data-status="sembuh"><i class="mdi mdi-shield-edit me-1"></i></button>
            <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-record" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
          </div>
        </td>
      </tr>
    @php
    $i++;
    @endphp
    @endforeach
  </tbody>

</table>
<script>
  $(".select2").select2({
    dropdownParent: $("#modal_sakit")
  });
  $(".select2-sembuh").select2({
    disabled : true,
    dropdownParent: $("#modal_sembuh")
  });
  $('#modal_sakit').on('hidden.bs.modal', function () {
      $('#formSakit').trigger("reset");
      $(".sembuh_area").hide();
  });
  $('.dataTable').dataTable();
  $('#formSakit').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    //showBlock();
    insert_update(formData);
  });
  $("#bulan").change(function(){
    const bulan = $(this).val();
    const tahun = $("#tahun").val();
    reload_table(bulan,tahun);
  });
  $("#tahun").change(function(){
    const bulan = $("#bulan").val();
    const tahun = $(this).val();
    reload_table(bulan,tahun);
  });
  $(document).on('click', '.edit_sakit', function () {
    const id = $(this).data('id');
    const status = $(this).data('status');
    if(status == "sembuh"){
      $(".sembuh_area").show();
    }else{
      $(".sembuh_area").hide();
    }
    // get data
    $.get(''.concat(baseUrl).concat('kesehatan/').concat(id, '/edit'), function (data) {
    let date = '';
    Object.keys(data).forEach(key => {
        //console.log(key);

        if(key == 'id'){
          $('#id_sakit')
            .val(data[key])
            .trigger('change');
        }else if(key == 'tanggal_sakit'){
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
          url: ''.concat(baseUrl).concat('kesehatan/').concat(id),
          success: function success() {
            reload_table(bulan,tahun);
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
  function insert_update(formData){
    const bulan = $("#bulan").val();
    const tahun = $("#tahun").val();
    $.ajax({
        data: formData,
        url: ''.concat(baseUrl).concat('kesehatan'),
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function success(status) {
          // sweetalert unblock data
          //showUnblock();
          //hilangkan modal
          $('#modal_sakit').modal('hide');
          //reset form
          reload_table(bulan,tahun);
          resetFormSakit();
          //refresh table
          Swal.fire({
            icon: 'success',
            title: 'Successfully '.concat(' Updated !'),
            text: ''.concat('Data ', ' ').concat(' Berhasil Ditambahkan'),
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        },
        error: function error(err) {
          //showUnblock();
          Swal.fire({
            title: 'Duplicate Entry!',
            text:  'Data Not Saved !',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        }
      });
  }
  function reload_table(bulan, tahun){
    showBlock();
    $.ajax({
      data: {'bulan' : bulan, "tahun" : tahun},
      url: ''.concat(baseUrl).concat('kesehatan/reload'),
      type: 'POST',
      success: function success(data) {
        $("#table_kesehatan").html(data);
        showUnblock();
      },
    });
  }
  function resetFormSakit(){
    $("#id_sakit").val("");
    $("#santri_id").val(0).trigger('change');
    $("#sakit").val("");''
    $("#tanggal_sakit").val("");
    $("#keterangan_sakit").val("");
  }

  </script>
