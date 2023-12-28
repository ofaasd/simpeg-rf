<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KecamatanTbl
 *
 * @property int $id
 * @property int $id_provinsi
 * @property int $id_kota_kab
 * @property int $id_kecamatan
 * @property string $nama_kecamatan
 *
 * @package App\Models
 */
class KecamatanTbl extends Model
{
  protected $table = 'kecamatan_tbl';
  public $timestamps = false;
  protected $dateFormat = 'U';

  protected $casts = [
    'id_provinsi' => 'int',
    'id_kota_kab' => 'int',
    'id_kecamatan' => 'int',
  ];

  protected $fillable = ['id_provinsi', 'id_kota_kab', 'id_kecamatan', 'nama_kecamatan'];
}
