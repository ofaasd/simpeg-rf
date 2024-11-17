@extends('layouts/layoutMaster')

@section('title', $title . ' Management - Crud App')

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css')}}" />
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/autosize/autosize.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-profile.js')}}"></script>
<script src="{{asset('assets/js/forms-selects.js')}}"></script>
<script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
@endsection

@section('content')

@include('ustadz/tahfidz/header')
<!-- Navbar pills -->
@include('ustadz/tahfidz/nav')
<!--/ Navbar pills -->
<div class="row">
  <div class="col-xl-12">
  <div class="card mb-4" id="card-block">
      <div class="card-header">
        <h4>Grafik Perkembangan Santri</h4>
        @if(Session::get('tahfidz_id') != 0)
        <div class="card-action-element ms-auto py-0">
          <select name="santri" id="santri" class="form-control">
            @foreach($var['list_santri'] as $row)
            <option value="{{$row->id}}">{{$row->nama}}</option>
            @endforeach
          </select>
        </div>
        @endif
      </div>
      <div class="card-body">
        @if(Session::get('tahfidz_id') == 0)
          <div class="alert alert-danger">Maaf anda belum terdaftar sebagai guru tahfidz. harap daftarkan terlebih dahulu melalui menu master data tahfdiz</div>
        @else
          <canvas id="barChart2" class="chartjs" ></canvas>
        @endif
      </div>
    </div>
  </div>
</div>


@endsection
<script>
document.addEventListener("DOMContentLoaded", function(event) {
  $('.dataTable').dataTable();
  get_jumlah_psb();
});
const get_jumlah_psb = () => {
    $.ajax({
      url: ''.concat(baseUrl).concat('get_jumlah_psb'),
      method: 'POST',
      data: { tahun: $('#tahun_psb').val() },
      success: function (data) {
        const chartStatus = Chart.getChart("barChart2");
        if (chartStatus != undefined) {
          chartStatus.destroy();
        }
        const barChart = document.getElementById('barChart2');
        const barChartVar = new Chart(barChart, {
            type: 'bar',
            data: {
              labels: data[0],
              datasets: [
                {
                  data: data[1],
                  backgroundColor: orangeLightColor,
                  borderColor: 'transparent',
                  maxBarThickness: 15,
                  borderRadius: {
                    topRight: 15,
                    topLeft: 15
                  }
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              animation: {
                duration: 500
              },
              plugins: {
                tooltip: {
                  rtl: isRtl,
                  backgroundColor: cardColor,
                  titleColor: headingColor,
                  bodyColor: legendColor,
                  borderWidth: 1,
                  borderColor: borderColor
                },
                legend: {
                  display: false
                }
              },
              scales: {
                x: {
                  grid: {
                    color: borderColor,
                    drawBorder: false,
                    borderColor: borderColor
                  },
                  ticks: {
                    color: labelColor
                  }
                },
                y: {
                  min: 0,
                  grid: {
                    color: borderColor,
                    drawBorder: false,
                    borderColor: borderColor
                  },
                  ticks: {
                    stepSize: 100,
                    color: labelColor
                  }
                }
              }
            }
          });
      }
    });
  }
</script>
