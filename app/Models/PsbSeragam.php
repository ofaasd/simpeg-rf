<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PsbSeragam
 *
 * @property int $id
 * @property int $psb_peserta_id
 * @property float|null $berat_badan
 * @property float|null $tinggi_badan
 * @property float|null $lingkar_dada
 * @property float|null $lingkar_pinggul
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class PsbSeragam extends Model
{
  use SoftDeletes;
  protected $table = 'psb_seragam';
  protected $dateFormat = 'U';

  protected $casts = [
    'psb_peserta_id' => 'int',
    'berat_badan' => 'float',
    'tinggi_badan' => 'float',
    'lingkar_dada' => 'float',
    'lingkar_pinggul' => 'float',
  ];

  protected $fillable = ['psb_peserta_id', 'berat_badan', 'tinggi_badan', 'lingkar_dada', 'lingkar_pinggul'];
}
