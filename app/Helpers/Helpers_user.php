<?php

namespace App\Helpers;

use Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;

class Helpers_user
{
  public static function appClasses()
  {
    $pegawai_id = Auth::user()->pegawai_id;
    $is_murroby = Kamar::where('employee_id', $pegawai_id)->count();
    $data = [
      'pegawai_id' => $pegawai_id,
      'is_murroby' => $is_murroby,
    ];
    return $data;
  }
}
