<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TbSakuMasuk
 *
 * @property int $id
 * @property int $dari
 * @property int $jumlah
 * @property Carbon $tanggal
 * @property string $no_induk
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class SakuMasuk extends Model
{
  use SoftDeletes;
  protected $table = 'tb_saku_masuk';
  protected $dateFormat = 'U';
  protected $casts = [
    'dari' => 'int',
    'jumlah' => 'int',
  ];

  protected $fillable = ['dari', 'jumlah', 'tanggal', 'no_induk', 'id_pembayaran'];

  public function santri(): BelongsTo
  {
    return $this->belongsTo(Santri::class, 'no_induk', 'no_induk');
  }
}
