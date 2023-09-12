<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * 
 * @property int $city_id
 * @property string|null $city_name
 * @property int|null $prov_id
 *
 * @package App\Models
 */
class City extends Model
{
	protected $table = 'cities';
	protected $primaryKey = 'city_id';
	public $timestamps = false;

	protected $casts = [
		'prov_id' => 'int'
	];

	protected $fillable = [
		'city_name',
		'prov_id'
	];
}
