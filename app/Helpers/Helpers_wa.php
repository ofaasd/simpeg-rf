<?php

namespace App\Helpers;

use Config;

class Helpers_wa
{
  public $number_key = '3EYdFkP7uhk5RX6D';
  public $wa_api = 'X2Y7UZOZT0WVQVTG';

  public static function send_wa($data)
  {
    $number_key = '3EYdFkP7uhk5RX6D';
    $wa_api = 'X2Y7UZOZT0WVQVTG';

    $curl = curl_init();

    $dataSending = [];
    $dataSending['api_key'] = $wa_api;
    $dataSending['number_key'] = $number_key;

    $prefix = substr($data['no_wa'], 0, 2);
    if ($prefix == '62') {
      $new_prefix = 0;
      $no_wa = $new_prefix . substr($data['no_wa'], 2);
    } else {
      $no_wa = $data['no_wa'];
    }
    $dataSending['phone_no'] = $no_wa;
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
  public static function send_wa_file($data)
  {
    $number_key = '3EYdFkP7uhk5RX6D';
    $wa_api = 'X2Y7UZOZT0WVQVTG';

    $curl = curl_init();

    $dataSending = [];
    $dataSending['api_key'] = $wa_api;
    $dataSending['number_key'] = $number_key;

    $prefix = substr($data['no_wa'], 0, 2);
    if ($prefix == '62') {
      $new_prefix = 0;
      $no_wa = $new_prefix . substr($data['no_wa'], 2);
    } else {
      $no_wa = $data['no_wa'];
    }

    $dataSending['phone_no'] = $no_wa;
    $dataSending['url'] = $data['file'];

    curl_setopt_array($curl, [
      CURLOPT_URL => 'https://api.watzap.id/v1/send_file_url',
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
