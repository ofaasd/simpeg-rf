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
 * @property int $kategori_id
 * @property string $judul
 * @property string $slug
 * @property string $thumbnail
 * @property string $gambar_dalam
 * @property string $isi_berita
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property int $kategori
 *
 * @package App\Models
 */

class Berita extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $table = 'berita';

  protected $guarded = ['id'];
  public function kategori()
  {
    return $this->belongsTo(Kategori::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
