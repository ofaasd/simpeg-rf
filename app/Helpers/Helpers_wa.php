<?php

namespace App\Helpers;

use Config;

class Helpers_wa
{
  public $number_key = '9qrE9KWANsXXHCA9';
  public $wa_api = 'X2Y7UZOZT0WVQVTG';

  public static function send_wa($data)
  {
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
    return $response;
  }
}
