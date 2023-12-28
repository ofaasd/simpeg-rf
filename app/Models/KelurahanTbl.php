<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KelurahanTbl
 *
 * @property int $id
 * @property int $id_provinsi
 * @property int $id_kota_kab
 * @property int $id_kecamatan
 * @property int $id_kelurahan
 * @property string $nama_kelurahan
 *
 * @package App\Models
 */
class KelurahanTbl extends Model
{
  protected $table = 'kelurahan_tbl';
  public $timestamps = false;
  protected $dateFormat = 'U';

  protected $casts = [
    'id_provinsi' => 'int',
    'id_kota_kab' => 'int',
    'id_kecamatan' => 'int',
    'id_kelurahan' => 'int',
  ];

  protected $fillable = ['id_provinsi', 'id_kota_kab', 'id_kecamatan', 'id_kelurahan', 'nama_kelurahan'];
}
