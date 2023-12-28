<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProvinsiTbl
 *
 * @property int $id_provinsi
 * @property string $nama_provinsi
 *
 * @package App\Models
 */
class ProvinsiTbl extends Model
{
  protected $table = 'provinsi_tbl';
  protected $primaryKey = 'id_provinsi';
  public $incrementing = false;
  public $timestamps = false;
  protected $dateFormat = 'U';

  protected $casts = [
    'id_provinsi' => 'int',
  ];

  protected $fillable = ['nama_provinsi'];
}
