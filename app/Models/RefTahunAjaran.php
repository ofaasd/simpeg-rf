<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RefTahunAjaran
 * 
 * @property int $id
 * @property int $id_tahun
 * @property string $awal
 * @property string $akhir
 * @property int $jenis
 * @property string|null $is_delete
 * @property int $is_aktif
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|SantriKamar[] $santri_kamars
 * @property Collection|SantriKela[] $santri_kelas
 * @property Collection|SantriTahfidz[] $santri_tahfidzs
 *
 * @package App\Models
 */
class RefTahunAjaran extends Model
{
	use SoftDeletes;
	protected $table = 'ref_tahun_ajaran';

	protected $casts = [
		'id_tahun' => 'int',
		'jenis' => 'int',
		'is_aktif' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'id_tahun',
		'awal',
		'akhir',
		'jenis',
		'is_delete',
		'is_aktif',
		'status'
	];

	public function santri_kamars()
	{
		return $this->hasMany(SantriKamar::class, 'tahun_ajaran_id');
	}

	public function santri_kelas()
	{
		return $this->hasMany(SantriKela::class, 'tahun_ajaran_id');
	}

	public function santri_tahfidzs()
	{
		return $this->hasMany(SantriTahfidz::class, 'tahun_ajaran_id');
	}
}
