<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbSakuKeluar
 *
 * @property int $id
 * @property string $no_induk
 * @property int $pegawai_id
 * @property int $jumlah
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string $deleted_at
 *
 * @property Collection|TbDetailSakuKeluar[] $tb_detail_saku_keluars
 *
 * @package App\Models
 */
class SakuKeluar extends Model
{
  use SoftDeletes;
  protected $table = 'tb_saku_keluar';

  protected $casts = [
    'pegawai_id' => 'int',
    'jumlah' => 'int',
  ];

  protected $fillable = ['no_induk', 'pegawai_id', 'jumlah'];

  public function tb_detail_saku_keluars()
  {
    return $this->hasMany(TbDetailSakuKeluar::class, 'id_saku_keluar');
  }
}
