@php
$configData = Helper::appClasses();
@endphp
<html lang="{{ session()->get('locale') ?? app()->getLocale() }}" class="{{ $configData['style'] }}-style {{ $navbarFixed ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $menuFlipped ?? '' }} {{ $menuOffcanvas ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}" dir="{{ $configData['textDirection'] }}" data-theme="{{ $configData['theme'] }}" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['style'] }}">

<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />


<head>
    <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>PPATQ RF | Statistik</title>
  <meta name="description" content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
  <meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Canonical SEO -->
  <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Include Styles -->
  @include('layouts/sections/styles')

  <!-- Include Scripts for customizer, helper, analytics, config -->
  @include('layouts/sections/scriptsIncludes')
</head>

<div class="container">
<div class="wrapper mt-3 rounded shadow p-4 mb-2">
<div class="row">
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-full bg-primary">
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
    <div class="card card-border-shadow-primary h-full bg-success">
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
  <div class="col-sm-6 col-lg-3 mb-4 h-fit">
    <div class="card card-border-shadow-primary h-full bg-secondary">
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
    <div class="card card-border-shadow-primary h-full bg-danger">
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
  <div class="col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-full bg-secondary">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <a href="{{URL::to('/pegawai')}}">
              <span class="avatar-initial rounded bg-label-secondary"><i class="mdi mdi-account-hard-hat-outline mdi-20px"></i></span>
            </a>
          </div>
        </div>
        @foreach ($hasil as $pegawai)
        <p class="mb-0 card-title text-white">{{ $pegawai["jabatan"] }} : {{ $pegawai["jumlah"] }}</p>
        @endforeach
      </div>
    </div>
  </div>
  <div class="col-sm-5 col-lg-4 mb-4">
    <div class="card card-border-shadow-primary h-full bg-warning">
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
  <div class="col-sm-6 col-lg-5 mb-4">
    <div class="card card-border-shadow-primary h-full bg-info">
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
    <div class="card shadow">
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
    <div class="card shadow" id="card-block">
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
  <!-- Kota -->
  <div class="col-lg-12 col-12 mb-4 mt-5">
    <div class="card shadow">
      <div class="card-header header-elements">
        <h5 class="card-title mb-2">Asal Kota Santri</h5>
      </div>
      <div class="card-body">
      <canvas id="barKota" class="chartjs" ></canvas>
      </div>
    </div>
  </div>
  <!-- /Kota Charts -->
  
  <!-- Kamar Charts -->
  <div class="col-lg-12 col-12 mb-4 mt-5">
    <div class="card shadow">
      <div class="card-header header-elements">
        <h5 class="card-title mb-2">Jumlah Santri Setiap Kamar</h5>
      </div>
      <div class="card-body">
        <canvas id="barKamar" class="chartjs" ></canvas>
      </div>
    </div>
  </div>
  <!-- /Kamar Charts -->
  <!-- Pembayaran Charts -->
  <div class="col-lg-12 col-12 mb-4 mt-5">
    <div class="card shadow">
      <div class="card-header header-elements">
        <h5 class="card-title mb-2">Jumlah transaksi bulan {{$list_bulan[(int)date('m')]}} {{ $tahun }}</h5>
      </div>
      <div class="card-body">
        <canvas id="barJumlah" class="chartjs" ></canvas>
      </div>
    </div>
  </div>
</div>
<!-- /Pembayaran Charts -->
<div class="col-lg-8 col-12 mb-4">
  <div class="card shadow">
    <div class="card-header header-elements">
      <h5 class="card-title mb-0">Statistik Pembayaran Per Tahun</h5>
      <div class="card-action-element ms-auto py-0">
        <select name="tahun_transaksi" id="tahun_transaksi" class="form-control">
          @for($i=date('Y');$i>=date('Y')-5; $i--)
          <option value="{{$i}}">{{$i}}</option>
          @endfor
        </select>
      </div>
    </div>
    <div class="card-body">
      <canvas id="barTransaksi" class="chartjs" ></canvas>
    </div>
  </div>
</div>
</div>
@include('layouts/sections/footer/footer')

@include('layouts/sections/scripts')

<script src="{{asset('assets/vendor/libs/chartjs/chartjs.js')}}"></script>
<script src="{{asset('assets/vendor/libs/block-ui/block-ui.js')}}"></script>

<script src="{{asset('assets/js/charts-chartjs.js')}}"></script>

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
    greenLightColor = '#A1DD70';
  let cardColor, headingColor, labelColor, borderColor, legendColor;

  document.addEventListener("DOMContentLoaded", function(event) {
    getJumlahPsb();
    getTarget();
    getTransaksiPembayaran();
    $("#tahun_psb").change(function(){
      getJumlahPsb();
    });

    $('#bulan_target').change(function(){
      getTarget();
    });
    $('#tahun_target').change(function(){
      getTarget();
    });
    $('#kelas_target').change(function(){
      getTarget();
    });
    $('#tahun_transaksi').change(function(){
      getTransaksiPembayaran();
    });
  });
  
  const getJumlahPsb = () => {
    $.ajax({
      url: ''.concat(baseUrl).concat('getJumlahPsb'),
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
                    stepSize: 25,
                    color: labelColor
                  }
                }
              }
            }
          });
      }
    });
  }

  // Asal Kota Santri
  $.ajax({
    url: ''.concat(baseUrl).concat('get_kota_santri'),
    method: 'GET',
    success: function (data) {
        const chartStatus = Chart.getChart("barKota");
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        const barChart = document.getElementById('barKota');
        const barChartVar = new Chart(barChart, {
            type: 'bar',
            data: {
                labels: data.map(item => item.Kota),
                datasets: [
                    {
                        data: data.map(item => item.Jumlah),
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
                            stepSize: 25,
                            color: labelColor
                        }
                    }
                }
            }
        });
    }
});

// Jumlah Santri Setiap Kamar
  $.ajax({
    url: ''.concat(baseUrl).concat('get_isi_kamar_santri'),
    method: 'GET',
    success: function (data) {
        const chartStatus = Chart.getChart("barKamar");
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        const barChart = document.getElementById('barKamar');
        const barChartVar = new Chart(barChart, {
            type: 'bar',
            data: {
                labels: data.map(item => item.Kamar),
                datasets: [
                    {
                        data: data.map(item => item.Jumlah),
                        backgroundColor: greenLightColor,
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
                            stepSize: 25,
                            color: labelColor
                        }
                    }
                }
            }
        });
    }
  });

  // Jumlah Transaksi Setiap Bulan
  $.ajax({
    url: ''.concat(baseUrl).concat('pembayaranBulanIni'),
    method: 'GET',
    success: function (data) {
        const chartStatus = Chart.getChart("barJumlah");
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        const barChart = document.getElementById('barJumlah');
        const barChartVar = new Chart(barChart, {
            type: 'bar',
            data: {
                labels:  data.map(item => item.tanggal + " " + item.nama_bulan),
                datasets: [
                    {
                        data: data.map(item => item.jumlah_transaksi),
                        backgroundColor: '#028391',
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
                            stepSize: 25,
                            color: labelColor
                        }
                    }
                }
            }
        });
    }
  });

  const getTarget = () => {
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
      url: ''.concat(baseUrl).concat('getTarget'),
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

  const getTransaksiPembayaran = () => {
    $.ajax({
      url: ''.concat(baseUrl).concat('getPembayaran'),
      method: 'POST',
      data: { tahun: $('#tahun_transaksi').val()},
      success: function (data) {
        console.log(data)
        const chartStatus = Chart.getChart("barTransaksi");
        if (chartStatus != undefined) {
          chartStatus.destroy();
        }
        const barChart = document.getElementById('barTransaksi');
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
                    stepSize: 25,
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

</html>

