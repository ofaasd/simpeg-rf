<table class="dataTable table">
    <thead>
        <tr>
        <td>Nama</td>
        <td>Kelas</td>
        <td>Murroby</td>
        <td>Tanggal Masuk</td>
        <td>Keluhan</td>
        <td>Terapi</td>
        <td>Tanggal Keluar</td>
        <td>Aksi</td>
        </tr>
    </thead>
    <tbody>
        @foreach($rawatInap as $row)
        <tr>
            <td>{{$row->namaSantri}}</td>
            <td>{{$row->kelas}}</td>
            <td>{{$row->namaMurroby}}</td>
            <td>{{date('d-m-Y', $row->tanggal_masuk)}}</td>
            <td>{{$row->keluhan}}</td>
            <td>{{$row->terapi}}</td>
            <td>{{ $row->tanggal_keluar == 0 ? '-' : date('d-m-Y', $row->tanggal_keluar) }}</td>
            <td>
            <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                <button type="button" id="btnSembuh" data-id="{{$row->id}}" class="btn btn-success edit_sakit waves-effect" data-bs-toggle="modal" data-bs-target="#modal_rawat_inap" data-status="sembuh"><i class="mdi mdi-shield-edit me-1"></i></button>
                <button type="button" id="btnDelete" data-id="{{$row->id}}" class="btn btn-danger waves-effect delete-record" data-bs-toggle="modal" data-bs-target="#hapus"><i class="mdi mdi-trash-can me-1"></i></button>
            </div>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

<script>
    $(".select2").select2({
        dropdownParent: $("#modal_rawat_inap")
    });
    $(".select2-sembuh").select2({
        disabled : true,
        dropdownParent: $("#modal_sembuh")
    });
    $('#modal_rawat_inap').on('hidden.bs.modal', function () {
        $('#formSakit').trigger("reset");
        $(".sembuh_area").hide();
    });
    $('.dataTable').dataTable();

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
        // get data
        $.get(''.concat(baseUrl).concat('rawat-inap/').concat(id, '/edit'), function (data) {
        let date = '';
        Object.keys(data).forEach(key => {
            console.log(data[key]);

            if(key == 'id'){
            $('#id_sakit')
                .val(data[key])
                .trigger('change');
            }else if(key == 'santri_no_induk'){
            $('#santri_id')
                .val(data[key])
                .trigger('change');
            }else if(key == 'tanggal_masuk'){
            tanggal = parseInt(data[key]) * 1000;
            date = new Date(tanggal).toLocaleString("sv-SE", {timeZone: "Asia/Jakarta"}).slice(0, 10);
            $('#' + key)
                .val(date)
                .trigger('change');
            }else if(key == 'tanggal_keluar'){
            if(parseInt(data[key]) > 0){
                tanggal = parseInt(data[key]) * 1000;
                date = new Date(tanggal).toLocaleString("sv-SE", {timeZone: "Asia/Jakarta"}).slice(0, 10);
                $('#' + key)
                    .val(date)
                    .trigger('change');
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
            url: ''.concat(baseUrl).concat('rawat-inap/').concat(id),
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

    function reload_table(bulan, tahun){
    showBlock();
    $.ajax({
        data: {'bulan' : bulan, "tahun" : tahun},
        url: ''.concat(baseUrl).concat('rawat-inap/reload'),
        type: 'POST',
        success: function success(data) {
            $("#table_kesehatan").html(data);
            showUnblock();
        },
    });
    }

    function resetFormSakit()
    {
        $("#id_sakit").val("");
        $("#santri_id").val(0).trigger('change');
        $("#sakit").val("");
        $("#kelas_id").val("");
        $("#murroby_id").val("");
        $("#tanggal_masuk").val("");
        $("#tanggal_keluar").val("");
    }
</script>