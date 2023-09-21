<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbUangSaku
 *
 * @property int $id
 * @property string $no_induk
 * @property int $jumlah
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class UangSaku extends Model
{
  use SoftDeletes;
  protected $table = 'tb_uang_saku';

  protected $casts = [
    'jumlah' => 'int',
  ];

  protected $fillable = ['no_induk', 'jumlah'];
}
