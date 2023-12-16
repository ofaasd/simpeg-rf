<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RefJenisPembayaran
 * 
 * @property int $id
 * @property string $jenis
 * @property int $urutan
 * @property int $harga
 *
 * @package App\Models
 */
class RefJenisPembayaran extends Model
{
	protected $table = 'ref_jenis_pembayaran';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'urutan' => 'int',
		'harga' => 'int'
	];

	protected $fillable = [
		'jenis',
		'urutan',
		'harga'
	];
}
