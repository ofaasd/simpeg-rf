<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SantriTahfidz
 *
 * @property int $id
 * @property int $santri_id
 * @property int $tahfidz_id
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $deleted_at
 * @property int $tahun_ajaran_id
 * @property int $status
 *
 * @property Santri $santri
 * @property RefTahfidz $ref_tahfidz
 * @property RefTahunAjaran $ref_tahun_ajaran
 *
 * @package App\Models
 */
class SantriTahfidz extends Model
{
  use SoftDeletes;
  protected $table = 'santri_tahfidz';
  protected $dateFormat = 'U';
  protected $casts = [
    'santri_id' => 'int',
    'tahfidz_id' => 'int',
    'tahun_ajaran_id' => 'int',
    'status' => 'int',
  ];

  protected $fillable = ['santri_id', 'tahfidz_id', 'tahun_ajaran_id', 'status'];

  public function santri()
  {
    return $this->belongsTo(Santri::class);
  }

  public function ref_tahfidz()
  {
    return $this->belongsTo(Tahfidz::class, 'tahfidz_id');
  }

  public function ref_tahun_ajaran()
  {
    return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
  }
}
