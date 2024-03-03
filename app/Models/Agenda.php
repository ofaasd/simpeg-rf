<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agenda
 *
 * @property int $id
 * @property string $gambar
 * @property string $judul
 * @property string $isi
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @property int $kategori
 *
 * @package App\Models
 */
class Agenda extends Model
{
  use SoftDeletes;
  protected $table = 'agenda';

  protected $casts = [
    'kategori' => 'int',
    'tanggal_mulai' => 'int',
    'tanggal_selesai' => 'int',
    'tanggal_mulai' => 'datetime:Y-m-d H:i',
    'tanggal_selesai' => 'datetime:Y-m-d H:i',
  ];

  protected $fillable = ['gambar', 'judul', 'isi', 'kategori', 'tanggal_mulai', 'tanggal_selesai'];
}
