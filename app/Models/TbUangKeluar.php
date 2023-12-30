<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbUangKeluar
 *
 * @property int $id
 * @property int $bulan
 * @property int $tahun
 * @property string $keterangan
 * @property int $jumlah
 * @property int $created_at
 * @property int $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class TbUangKeluar extends Model
{
  use SoftDeletes;
  protected $table = 'tb_uang_keluar';
  protected $dateFormat = 'U';

  protected $casts = [
    'bulan' => 'int',
    'tahun' => 'int',
    'jumlah' => 'int',
  ];

  protected $fillable = ['bulan', 'tahun', 'tanggal_transaksi', 'keterangan', 'jumlah'];
}
