<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbPemeriksaan
 *
 * @property int $id
 * @property string $no_induk
 * @property int $tanggal_pemeriksaan
 * @property int|null $tinggi_badan
 * @property int|null $berat_badan
 * @property int|null $lingkar_pinggul
 * @property int|null $lingkar_dada
 * @property string|null $kondisi_gigi
 * @property string|null $tubuh
 * @property string|null $kulit
 * @property string|null $rambut
 * @property string|null $kuku
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TbPemeriksaan extends Model
{
  use SoftDeletes;
  protected $table = 'tb_pemeriksaan';
  protected $dateFormat = 'U';

  protected $casts = [
    'tanggal_pemeriksaan' => 'int',
    'tinggi_badan' => 'int',
    'berat_badan' => 'int',
    'lingkar_pinggul' => 'int',
    'lingkar_dada' => 'int',
    'tubuh' => 'string',
    'kulit' => 'string',
    'rambut' => 'string',
    'kuku' => 'string',
  ];

  protected $fillable = [
    'no_induk',
    'tanggal_pemeriksaan',
    'tinggi_badan',
    'berat_badan',
    'lingkar_pinggul',
    'lingkar_dada',
    'kondisi_gigi',
    'tubuh',
    'kulit',
    'rambut',
    'kuku',
  ];
}
