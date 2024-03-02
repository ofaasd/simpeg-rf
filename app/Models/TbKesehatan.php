<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbKesehatan
 *
 * @property int $id
 * @property int $santri_id
 * @property string $sakit
 * @property int $tanggal_sakit
 * @property int $tanggal_sembuh
 * @property string $keterangan
 * @property int $created_at
 * @property int $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class TbKesehatan extends Model
{
  use SoftDeletes;
  protected $table = 'tb_kesehatan';
  protected $dateFormat = 'U';

  protected $casts = [
    'santri_id' => 'int',
    'tanggal_sakit' => 'int',
  ];

  protected $fillable = [
    'santri_id',
    'sakit',
    'tanggal_sakit',
    'tanggal_sembuh',
    'keterangan_sakit',
    'keterangan_sembuh',
    'tindakan',
  ];
}
