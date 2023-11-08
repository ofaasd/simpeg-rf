<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PsbSekolahAsal
 *
 * @property int $id
 * @property string|null $jenjang
 * @property string|null $nama_sekolah
 * @property string|null $nss
 * @property string|null $npsn
 * @property string|null $nisn
 * @property string|null $kelas
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 * @property int $psb_peserta_id
 *
 * @package App\Models
 */
class PsbSekolahAsal extends Model
{
  use SoftDeletes;
  protected $table = 'psb_sekolah_asal';
  protected $dateFormat = 'U';

  protected $casts = [
    'psb_peserta_id' => 'int',
  ];

  protected $fillable = ['jenjang', 'nama_sekolah', 'nss', 'npsn', 'nisn', 'kelas', 'psb_peserta_id'];
}
