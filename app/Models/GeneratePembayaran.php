<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GeneratePembayaran
 *
 * @property int $id
 * @property string $no_induk
 * @property int $total_bayar
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class GeneratePembayaran extends Model
{
	use SoftDeletes;
	protected $table = 'generate_pembayaran';

	protected $casts = [
		'total_bayar' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'no_induk',
		'total_bayar',
		'status',
    'bulan',
    'tahun'
	];
}
