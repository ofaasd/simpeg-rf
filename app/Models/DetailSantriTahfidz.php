<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

  protected $fillable = ['id_tahfidz', 'no_induk', 'bulan', 'tahun', 'id_tahun_ajaran', 'kode_juz_surah', 'tanggal'];
  public function tahfidz(): BelongsTo
  {
    return $this->belongsTo(Tahfidz::class, 'id_tahfidz', 'id');
  }

  public function santri(): BelongsTo
  {
    return $this->belongsTo(Santri::class, 'no_induk', 'no_induk');
  }

  public function kode_juz(): BelongsTo
  {
    return $this->belongsTo(KodeJuz::class, 'kode_juz_surah', 'id');
  }
}
