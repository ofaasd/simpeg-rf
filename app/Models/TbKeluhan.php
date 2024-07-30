<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TbKeluhan
 * 
 * @property int $id
 * @property string $nama_pelapor
 * @property string|null $email
 * @property string|null $no_hp
 * @property int $id_santri
 * @property string $nama_wali_santri
 * @property int $id_kategori
 * @property string $masukan
 * @property string $saran
 * @property string|null $gambar
 * @property int $status
 * @property int $is_hapus
 * @property int $rating
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $jenis
 *
 * @package App\Models
 */
class TbKeluhan extends Model
{
	protected $table = 'tb_keluhan';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int',
		'id_santri' => 'int',
		'id_kategori' => 'int',
		'status' => 'int',
		'is_hapus' => 'int',
		'rating' => 'int'
	];

	protected $fillable = [
		'nama_pelapor',
		'email',
		'no_hp',
		'id_santri',
		'nama_wali_santri',
		'id_kategori',
		'masukan',
		'saran',
		'gambar',
		'status',
		'is_hapus',
		'rating',
		'jenis'
	];
}
