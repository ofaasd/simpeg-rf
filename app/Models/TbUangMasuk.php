<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbUangMasuk
 *
 * @property int $id
 * @property int $bulan
 * @property int $tahun
 * @property string $sumber
 * @property int $jumlah
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TbUangMasuk extends Model
{
  use SoftDeletes;
  protected $table = 'tb_uang_masuk';
  protected $dateFormat = 'U';

  protected $casts = [
    'bulan' => 'int',
    'tahun' => 'int',
    'jumlah' => 'int',
  ];

  protected $fillable = ['bulan', 'tahun', 'sumber', 'jumlah'];
}
