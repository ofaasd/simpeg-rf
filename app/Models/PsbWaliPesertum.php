<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PsbWaliPesertum
 * 
 * @property int $id
 * @property string|null $nama_ayah
 * @property string|null $pendidikan_ayah
 * @property string|null $pekerjaan_ayah
 * @property string|null $alamat_ayah
 * @property string|null $no_hp
 * @property string|null $nama_ibu
 * @property string|null $pendidikan_ibu
 * @property string|null $pekerjaan_ibu
 * @property string|null $alamat_ibu
 * @property string|null $no_telp
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 * @property int $psb_peserta_id
 *
 * @package App\Models
 */
class PsbWaliPesertum extends Model
{
	use SoftDeletes;
	protected $table = 'psb_wali_peserta';

	protected $casts = [
		'psb_peserta_id' => 'int'
	];

	protected $fillable = [
		'nama_ayah',
		'pendidikan_ayah',
		'pekerjaan_ayah',
		'alamat_ayah',
		'no_hp',
		'nama_ibu',
		'pendidikan_ibu',
		'pekerjaan_ibu',
		'alamat_ibu',
		'no_telp',
		'psb_peserta_id'
	];
}
