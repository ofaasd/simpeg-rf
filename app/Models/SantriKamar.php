<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SantriKamar
 *
 * @property int $id
 * @property int $santri_id
 * @property int $kamar_id
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $deleted_at
 * @property int $tahun_ajaran_id
 * @property int $status
 *
 * @property RefKamar $ref_kamar
 * @property Santri $santri
 * @property RefTahunAjaran $ref_tahun_ajaran
 *
 * @package App\Models
 */
class SantriKamar extends Model
{
  use SoftDeletes;
  protected $table = 'santri_kamar';

  protected $casts = [
    'santri_id' => 'int',
    'kamar_id' => 'int',
    'tahun_ajaran_id' => 'int',
    'status' => 'int',
  ];

  protected $fillable = ['santri_id', 'kamar_id', 'tahun_ajaran_id', 'status'];

  public function ref_kamar()
  {
    return $this->belongsTo(RefKamar::class, 'kamar_id');
  }

  public function santri()
  {
    return $this->belongsTo(Santri::class);
  }

  public function ref_tahun_ajaran()
  {
    return $this->belongsTo(RefTahunAjaran::class, 'tahun_ajaran_id');
  }
}
