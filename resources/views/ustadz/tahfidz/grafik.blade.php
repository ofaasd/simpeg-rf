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
<script src="{{asset('assets/vendor/libs/chartjs/chartjs.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-profile.js')}}"></script>
<script src="{{asset('assets/js/forms-selects.js')}}"></script>
<script src="{{asset('assets/js/forms-extras-custom.js')}}"></script>
<script src="{{asset('assets/js/charts-chartjs.js')}}"></script>
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
        @if(!empty($tahfidz))
        <div class="card-action-element ms-auto py-0">
          <select name="santri" id="santri" class="form-control">
            <option value="0">--Pilih Santri--</option>
            @foreach($var['list_santri'] as $row)
            <option value="{{$row->no_induk}}">{{$row->nama}}</option>
            @endforeach
          </select>
        </div>
        @endif
      </div>
      <div class="card-body">
        @if(empty($tahfidz))
          <div class="alert alert-danger">Maaf anda belum terdaftar sebagai guru tahfidz. harap daftarkan terlebih dahulu melalui menu master data tahfdiz</div>
        @else
          <div style="margin: 0.5em;">
            <canvas id="barChart2" class="chartjs" ></canvas>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h4>Legend</h4>
              <table class="table table-stripped dataTable">
                <thead>
                  <tr>
                    <th style="width:10px">id</th>
                    <th>Juz/Surat</th>
                    <th>Nilai</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($var['kode_juz'] as $kode_juz)
                  <tr>
                    <td>{{$kode_juz->id}}</td>
                    <td>{{$kode_juz->nama}}</td>
                    <td>{{$kode_juz->id}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>


@endsection
<script>
const purpleColor = '#836AF9',
  yellowColor = '#ffe800',
  cyanColor = '#28dac6',
  orangeColor = '#FF8132',
  orangeLightColor = '#ffcf5c',
  oceanBlueColor = '#299AFF',
  greyColor = '#4F5D70',
  greyLightColor = '#EDF1F4',
  blueColor = '#2B9AFF',
  blueLightColor = '#84D0FF';
let cardColor, headingColor, labelColor, borderColor, legendColor;
document.addEventListener("DOMContentLoaded", function(event) {
  $('.dataTable').dataTable();
  //get_jumlah_psb();
  $("#santri").on("change",function(){
    get_jumlah_psb();
  });
});

const get_jumlah_psb = () => {
    $.ajax({
      //url: ''.concat(baseUrl).concat('get_jumlah_psb'),
      url: ''.concat(baseUrl).concat('ustadz/get_grafik'),
      method: 'POST',
      data: { no_induk: $('#santri').val() },
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
