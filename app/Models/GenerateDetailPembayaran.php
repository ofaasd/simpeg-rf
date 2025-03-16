<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GenerateDetailPembayaran
 * 
 * @property int $id
 * @property int $id_generate_pembayaran
 * @property int $id_jenis
 * @property int $jumlah
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class GenerateDetailPembayaran extends Model
{
	use SoftDeletes;
	protected $table = 'generate_detail_pembayaran';

	protected $casts = [
		'id_generate_pembayaran' => 'int',
		'id_jenis' => 'int',
		'jumlah' => 'int'
	];

	protected $fillable = [
		'id_generate_pembayaran',
		'id_jenis',
		'jumlah'
	];
}
