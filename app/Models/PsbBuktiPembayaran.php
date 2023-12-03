<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PsbBuktiPembayaran
 *
 * @property int $id
 * @property int $psb_peserta_id
 * @property string $bank
 * @property string $atas_nama
 * @property string $no_rekening
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 * @property string $bukti
 * @property int $status
 *
 * @package App\Models
 */
class PsbBuktiPembayaran extends Model
{
  use SoftDeletes;
  protected $table = 'psb_bukti_pembayaran';
  protected $dateFormat = 'U';

  protected $casts = [
    'psb_peserta_id' => 'int',
    'status' => 'int',
  ];

  protected $fillable = ['psb_peserta_id', 'bank', 'atas_nama', 'no_rekening', 'bukti', 'status'];
}
