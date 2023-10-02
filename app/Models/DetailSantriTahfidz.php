<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DetailSantriTahfidz
 *
 * @property int $id
 * @property int $id_tahfidz
 * @property int $no_induk
 * @property int $bulan
 * @property int $tahun
 * @property int $id_tahun_ajaran
 * @property int $kode_juz_surah
 * @property int|null $created-at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class DetailSantriTahfidz extends Model
{
  use SoftDeletes;
  protected $table = 'detail_santri_tahfidz';
  public $timestamps = false;
  protected $dateFormat = 'U';

  protected $casts = [
    'id_tahfidz' => 'int',
    'no_induk' => 'int',
    'bulan' => 'int',
    'tahun' => 'int',
    'id_tahun_ajaran' => 'int',
    'kode_juz_surah' => 'int',
    'created-at' => 'int',
  ];

  protected $fillable = ['id_tahfidz', 'no_induk', 'bulan', 'tahun', 'id_tahun_ajaran', 'kode_juz_surah', 'created-at'];
}
