<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbBerkasPendukung
 * 
 * @property int $id
 * @property int $no_induk
 * @property string|null $file_kk
 * @property string|null $file_akta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TbBerkasPendukung extends Model
{
	use SoftDeletes;
	protected $table = 'tb_berkas_pendukung';

	protected $casts = [
		'no_induk' => 'int'
	];

	protected $fillable = [
		'no_induk',
		'file_kk',
		'file_akta'
	];
}
