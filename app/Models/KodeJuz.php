<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KodeJuz
 *
 * @property int $id
 * @property int $kode
 * @property string $nama
 *
 * @package App\Models
 */
class KodeJuz extends Model
{
  protected $table = 'kode_juz';
  protected $dateFormat = 'U';

  protected $casts = [
    'kode' => 'int',
  ];

  protected $fillable = ['kode', 'nama'];
}
