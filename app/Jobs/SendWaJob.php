<?php

namespace App\Jobs;
use App\Models\PsbPesertaOnline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWaJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   */
  public function __construct()
  {
    //
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    //
    $tanggal = date('Y-m-d');
    $get_peserta = PsbPesertaOnline::whereRaw('FROM_UNIXTIME(created_at) = ' . $tanggal);
    $jumlah = $get_peserta->count();
    $data_pesan =
      "*Laporan dari Sistem PSB PPATQ RF*

    Jumlah Pendaftar Hari ini " .
      $jumlah .
      ' ';

    $data['no_wa'] = '082326248982';
    $data['pesan'] = $data_pesan;

    $number_key = '9qrE9KWANsXXHCA9';
    $wa_api = 'X2Y7UZOZT0WVQVTG';

    $curl = curl_init();

    $dataSending = [];
    $dataSending['api_key'] = $wa_api;
    $dataSending['number_key'] = $number_key;
    $dataSending['phone_no'] = $data['no_wa'];
    $dataSending['message'] = $data['pesan'];

    curl_setopt_array($curl, [
      CURLOPT_URL => 'https://api.watzap.id/v1/send_message',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($dataSending),
      CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
  }
}
