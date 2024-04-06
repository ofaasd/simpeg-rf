@php
$configData = Helper::appClasses();
@endphp

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/chartjs/chartjs.js')}}"></script>
<script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/charts-chartjs.js')}}"></script>
@endsection

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
<div class="row">
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-primary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <a href="{{URL::to('/psb')}}">
              <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-bus-school mdi-20px"></i></span>
            </a>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">{{$jumlah_psb}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Pendaftar</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" >{{$jumlah_psb_baru}}</span>
          <small class="text-white fw-bold">Siswa Baru Pada Bulan ini</small>
        </p>
        <div class="row" style="border-top:1px solid #fff; margin-top:20px; padding-top:10px;">
          <div class="col-md-6">
            <span class="me-1 text-white fw-bold" >L : {{$jumlah_psb_laki}}</span>
          </div>
          <div class="col-md-6">
            <span class="me-1 text-white fw-bold" >P : {{$jumlah_psb_perempuan}}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-success">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <a href="{{URL::to('/santri')}}">
              <span class="avatar-initial rounded bg-label-success"><i class="mdi mdi-account-school mdi-20px"></i></span>
            </a>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">{{$jumlah_siswa}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Santri</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
        <div class="row" style="border-top:1px solid #fff; margin-top:20px; padding-top:10px;">
          <div class="col-md-6">
            <span class="me-1 text-white fw-bold" >L : {{$jumlah_siswa_l}}</span>
          </div>
          <div class="col-md-6">
            <span class="me-1 text-white fw-bold" >P : {{$jumlah_siswa_p}}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-secondary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <a href="{{URL::to('/pegawai')}}">
              <span class="avatar-initial rounded bg-label-secondary"><i class="mdi mdi-account-group mdi-20px"></i></span>
            </a>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">{{$jumlah_pegawai}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Pegawai</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
        <div class="row" style="border-top:1px solid #fff; margin-top:20px; padding-top:10px;">
          <div class="col-md-6">
            <span class="me-1 text-white fw-bold" >L : {{$jumlah_pegawai_l}}</span>
          </div>
          <div class="col-md-6">
            <span class="me-1 text-white fw-bold" >P : {{$jumlah_pegawai_p}}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-danger">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger"><i class="mdi mdi-account-off mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">{{$jumlah_siswa_belum_lapor}}</h4>
        </div>
        <p class="mb-0 card-title text-white">{{number_format($jumlah_siswa_belum_lapor/$jumlah_siswa*100, 2, ".",".")}} % siswa belum melaporkan pembayaran bulan {{$list_bulan[(int)date('m')]}}</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-6 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-warning">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning"><i class="mdi mdi-cash mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">Rp . {{number_format($jumlah_pembayaran,0,",",".")}} / Rp . {{number_format($tot_bayar,0,",",".")}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Pembayaran Valid Bulan {{$list_bulan[(int)date('m')]}} / Total Pembayaran Bulan {{$list_bulan[(int)date('m')]}}</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-6 mb-4">
    <div class="card card-border-shadow-primary h-100 bg-info">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-cash mdi-20px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6 text-white">Rp. {{number_format($jumlah_pembayaran_lalu,0,",",".")}}</h4>
        </div>
        <p class="mb-0 card-title text-white">Jumlah Pembayaran Bulan Lalu</p>
        <p class="mb-0">
          <span class="me-1 text-white fw-bold" ></span>
          <small class="text-white fw-bold"></small>
        </p>
      </div>
    </div>
  </div>
  <!-- Bar Charts -->
  <div class="col-lg-8 col-12 mb-4">
    <div class="card">
      <div class="card-header header-elements">
        <h5 class="card-title mb-0">Statistik Pendaftar PSB</h5>
        <div class="card-action-element ms-auto py-0">
          <select name="tahun_psb" id="tahun_psb" class="form-control">
            @for($i=date('Y');$i>=date('Y')-5; $i--)
            <option value="{{$i}}">{{$i}}</option>
            @endfor
          </select>
        </div>
      </div>
      <div class="card-body">
        <canvas id="barChart2" class="chartjs" ></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-12 mb-4">
    <div class="card" id="card-block">
      <div class="card-header header-elements">
        <h5 class="card-title mb-0">Statistik Target Pembayaran</h5>
          <div class="row g-3 align-items-center" style="margin-top:10px">
            <div class="col-auto">
              <select name="bulan_target" id="bulan_target" class="form-control">
                @foreach($list_bulan as $key=>$bulan)
                <option value="{{$key}}" {{ ($key == (int)date('m')) ? "selected" : ""}}>{{$bulan}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-auto">
              <select name="tahun_target" id="tahun_target" class="form-control">
                @for($i=date('Y');$i>=date('Y')-5; $i--)
                <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            <div class="col-auto">
              <select name="kelas_target" id="kelas_target" class="form-control">
                @foreach($kelas as $row)
                <option value="{{$row->kelas}}">{{$row->kelas}}</option>
                @endforeach
              </select>
            </div>
      </div>
      <div class="card-body">
        <canvas id="doughnutCharts" class="chartjs mb-4" style="width:100%!important"></canvas>
      </div>
    </div>
  </div>
  <!-- /Bar Charts -->
</div>
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
    get_jumlah_psb();
    get_target();
    $("#tahun_psb").change(function(){
      get_jumlah_psb();

    });
    $('#bulan_target').change(function(){
      get_target();
    });
    $('#tahun_target').change(function(){
      get_target();
    });
    $('#kelas_target').change(function(){
      get_target();
    });
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
  const get_target = () => {
    $('#card-block').block({
      message:
        '<div class="d-flex justify-content-center"><p class="mb-0">Tunggu Sebentar</p> <div class="sk-wave m-0"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div> </div>',
      css: {
        backgroundColor: 'transparent',
        color: '#fff',
        border: '0'
      },
      overlayCSS: {
        opacity: 0.5
      }
    });
    $.ajax({
      url: ''.concat(baseUrl).concat('get_target'),
      method: 'POST',
      data: { bulan: $('#bulan_target').val(),tahun: $('#tahun_target').val(),kelas:$('#kelas_target').val() },
      success: function (data) {
        const chartStatus = Chart.getChart("doughnutCharts");
        if (chartStatus != undefined) {
          chartStatus.destroy();
        }
        const doughnutChart = document.getElementById('doughnutCharts');
        const doughnutChartVar = new Chart(doughnutChart, {
          type: 'doughnut',
          data: {
            labels: data[0],
            datasets: [
              {
                data: data[1],
                backgroundColor: [cyanColor, orangeLightColor, config.colors.primary],
                borderWidth: 0,
                pointStyle: 'rectRounded'
              }
            ]
          },
          options: {
            responsive: true,
            animation: {
              duration: 500
            },
            cutout: '68%',
            plugins: {
              legend: {
                display: true
              },
              tooltip: {

                // Updated default tooltip UI
                rtl: isRtl,
                backgroundColor: cardColor,
                titleColor: headingColor,
                bodyColor: legendColor,
                borderWidth: 1,
                borderColor: borderColor
              }
            }
          }
        });
        $('#card-block').unblock();
      }
    });
  }

</script>
@endsection
