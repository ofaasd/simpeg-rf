<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RefBank
 * 
 * @property int $id
 * @property string $nama
 * @property string $kode
 *
 * @package App\Models
 */
class RefBank extends Model
{
	protected $table = 'ref_bank';
	public $timestamps = false;

	protected $fillable = [
		'nama',
		'kode'
	];
}
