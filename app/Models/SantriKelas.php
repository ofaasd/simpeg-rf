<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SantriKela
 *
 * @property int $id
 * @property int $santri_id
 * @property int $kelas_id
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $deleted_at
 * @property int $tahun_ajaran_id
 * @property int $status
 *
 * @property RefKela $ref_kela
 * @property Santri $santri
 * @property RefTahunAjaran $ref_tahun_ajaran
 *
 * @package App\Models
 */
class SantriKelas extends Model
{
  use SoftDeletes;
  protected $table = 'santri_kelas';
  protected $dateFormat = 'U';
  protected $casts = [
    'santri_id' => 'int',
    'kelas_id' => 'int',
    'tahun_ajaran_id' => 'int',
    'status' => 'int',
  ];

  protected $fillable = ['santri_id', 'kelas_id', 'tahun_ajaran_id', 'status'];

  public function ref_kelas()
  {
    return $this->belongsTo(RefKela::class, 'kelas_id');
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
