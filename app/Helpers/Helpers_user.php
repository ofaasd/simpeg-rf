<?php

namespace App\Helpers;

use Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use App\Models\Tahfidz;

class Helpers_user
{
  public static function appClasses()
  {
    $pegawai_id = Auth::user()->pegawai_id;
    $is_murroby = Kamar::where('employee_id', $pegawai_id)->count();
    $is_tahfidz = Tahfidz::where('employee_id', $pegawai_id)->count();
    $data = [
      'pegawai_id' => $pegawai_id,
      'is_murroby' => $is_murroby,
      'is_tahfidz' => $is_tahfidz,
    ];
    return $data;
  }
}
