<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Agenda
 *
 * @property int $id
 * @property string $nama_kategori
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */

class Kategori extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $fillable = ['nama_kategori'];

  protected $table = 'kategori_berita';

  public function berita()
  {
    return $this->hasMany(Berita::class);
  }
}
