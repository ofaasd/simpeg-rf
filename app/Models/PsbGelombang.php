<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PsbGelombang
 *
 * @property int $id
 * @property int $no_gel
 * @property string|null $nama_gel
 * @property string|null $nama_gel_long
 * @property Carbon|null $tgl_mulai
 * @property Carbon|null $tgl_akhir
 * @property Carbon|null $ujian
 * @property string|null $jam_ujian
 * @property string|null $hari_ujian
 * @property Carbon|null $pengumuman
 * @property Carbon|null $reg_mulai
 * @property Carbon|null $reg_akhir
 * @property string|null $tahun
 * @property bool|null $semester
 * @property bool $jenis
 * @property int $pmb_online
 *
 * @package App\Models
 */
class PsbGelombang extends Model
{
  protected $table = 'psb_gelombang';
  public $timestamps = false;

  protected $casts = [
    'no_gel' => 'int',
    'tgl_mulai' => 'date:Y-m-d',
    'tgl_akhir' => 'date:Y-m-d',
    'jenis' => 'int',
    'pmb_online' => 'int',
  ];

  protected $fillable = [
    'no_gel',
    'nama_gel',
    'nama_gel_long',
    'tgl_mulai',
    'tgl_akhir',
    'tahun',
    'jenis',
    'pmb_online',
  ];
}
