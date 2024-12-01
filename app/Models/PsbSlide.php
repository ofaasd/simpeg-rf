<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PsbSlide
 * 
 * @property int $id
 * @property string $gambar
 * @property string $caption
 * @property string $link
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class PsbSlide extends Model
{
	protected $table = 'psb_slide';
	public $timestamps = false;

	protected $fillable = [
		'gambar',
		'caption',
		'link'
	];
}
