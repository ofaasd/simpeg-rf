<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Province
 * 
 * @property int $prov_id
 * @property string|null $prov_name
 * @property int|null $locationid
 * @property int|null $status
 *
 * @package App\Models
 */
class Province extends Model
{
	protected $table = 'provinces';
	protected $primaryKey = 'prov_id';
	public $timestamps = false;

	protected $casts = [
		'locationid' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'prov_name',
		'locationid',
		'status'
	];
}
