<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbBukatutup
 * 
 * @property int $id
 * @property int $status
 * @property Carbon $tanggal_buat
 *
 * @package App\Models
 */
class TbBukatutup extends Model
{
	protected $table = 'tb_bukatutup';
	public $timestamps = false;

	protected $casts = [
		'status' => 'int',
		'tanggal_buat' => 'datetime'
	];

	protected $fillable = [
		'status',
		'tanggal_buat'
	];
}
