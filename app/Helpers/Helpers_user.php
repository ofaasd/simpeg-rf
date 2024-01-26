<?php

namespace App\Helpers;

use Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use App\Models\Tahfidz;
use App\Models\ProvinsiTbl;
use App\Models\KotaKabTbl;
use App\Models\KecamatanTbl;
use App\Models\KelurahanTbl;

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
  public static function getProvinsi($id_provinsi)
  {
    $provinsi = ProvinsiTbl::where('id_provinsi', $id_provinsi)->first();
    if ($provinsi) {
      return $provinsi->nama_provinsi;
    } else {
      return '';
    }
  }
  public static function getKota($id_provinsi, $id_kota_kab)
  {
    $kota = KotaKabTbl::where('id_provinsi', $id_provinsi)
      ->where('id_kota_kab', $id_kota_kab)
      ->first();
    if ($kota) {
      return $kota->nama_kota_kab;
    } else {
      return '';
    }
  }
  public static function getKecamatan($id_provinsi, $id_kota_kab, $id_kecamatan)
  {
    $kecamatan = KecamatanTbl::where('id_provinsi', $id_provinsi)
      ->where('id_kota_kab', $id_kota_kab)
      ->where('id_kecamatan', $id_kecamatan)
      ->first();
    if ($kecamatan) {
      return $kecamatan->nama_kecamatan;
    } else {
      return '';
    }
  }
  public static function getKelurahan($id_provinsi, $id_kota_kab, $id_kecamatan, $id_kelurahan)
  {
    $kelurahan = KelurahanTbl::where('id_provinsi', $id_provinsi)
      ->where('id_kota_kab', $id_kota_kab)
      ->where('id_kecamatan', $id_kecamatan)
      ->where('id_kelurahan', $id_kelurahan)
      ->first();
    if ($kelurahan) {
      return $kelurahan->nama_kelurahan;
    } else {
      return '';
    }
  }
}
