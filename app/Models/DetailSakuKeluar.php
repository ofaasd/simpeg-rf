<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbDetailSakuKeluar
 *
 * @property int $id
 * @property int $id_saku_keluar
 * @property string $note
 * @property int $jumlah
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @property TbSakuKeluar $tb_saku_keluar
 *
 * @package App\Models
 */
class DetailSakuKeluar extends Model
{
  use SoftDeletes;
  protected $table = 'tb_detail_saku_keluar';

  protected $casts = [
    'id_saku_keluar' => 'int',
    'jumlah' => 'int',
  ];

  protected $fillable = ['id_saku_keluar', 'note', 'jumlah'];

  public function tb_saku_keluar()
  {
    return $this->belongsTo(TbSakuKeluar::class, 'id_saku_keluar');
  }
}
