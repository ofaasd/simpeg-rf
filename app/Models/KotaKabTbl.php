<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KotaKabTbl
 *
 * @property int $id
 * @property int $id_provinsi
 * @property int $id_kota_kab
 * @property string $nama_kota_kab
 *
 * @package App\Models
 */
class KotaKabTbl extends Model
{
  protected $table = 'kota_kab_tbl';
  public $timestamps = false;
  protected $dateFormat = 'U';

  protected $casts = [
    'id_provinsi' => 'int',
    'id_kota_kab' => 'int',
  ];

  protected $fillable = ['id_provinsi', 'id_kota_kab', 'nama_kota_kab'];
}
