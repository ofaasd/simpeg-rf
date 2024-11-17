<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PsbGelombangDetail
 *
 * @property int $id
 * @property string $hari
 * @property string $jam
 * @property string $syarat
 * @property string $prosedur_online
 * @property string $prosedur_offline
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class PsbGelombangDetail extends Model
{
	use SoftDeletes;
	protected $table = 'psb_gelombang_detail';

	protected $fillable = [
    'id_gelombang',
		'hari',
		'jam',
		'syarat',
		'prosedur_online',
		'prosedur_offline'
	];
}
