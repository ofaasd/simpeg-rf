<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SantriDetail
 *
 * @property int $id
 * @property int $no_induk
 * @property string $nama
 * @property string|null $nisn
 * @property string|null $nik
 * @property int|null $anak_ke
 * @property string|null $tempat_lahir
 * @property Carbon|null $tanggal_lahir
 * @property float|null $usia
 * @property string|null $jenis_kelamin
 * @property string|null $alamat
 * @property string|null $kelurahan
 * @property string|null $kecamatan
 * @property int|null $kabkota
 * @property int|null $provinsi
 * @property string|null $kode_pos
 * @property string|null $nik_kk
 * @property string|null $nama_lengkap_ayah
 * @property string|null $pendidikan_ayah
 * @property string|null $pekerjaan_ayah
 * @property string|null $nama_lengkap_ibu
 * @property string|null $pendidikan_ibu
 * @property string|null $pekerjaan_ibu
 * @property string|null $no_hp
 * @property Carbon|null $created_at
 * @property Carbon $updated_at
 * @property int|null $no_tes
 * @property string|null $kelas
 * @property int|null $kamar_id
 * @property int|null $tahfidz_id
 * @property string|null $photo
 *
 * @package App\Models
 */
class Santri extends Model
{
  protected $table = 'santri_detail';
  protected $casts = [
    'no_induk' => 'int',
    'anak_ke' => 'int',
    'tanggal_lahir' => 'datetime',
    'usia' => 'float',
    'kabkota' => 'int',
    'provinsi' => 'int',
    'no_tes' => 'int',
    'kamar_id' => 'int',
    'tahfidz_id' => 'int',
  ];

  protected $fillable = [
    'no_induk',
    'nama',
    'nisn',
    'nik',
    'anak_ke',
    'tempat_lahir',
    'tanggal_lahir',
    'usia',
    'jenis_kelamin',
    'alamat',
    'kelurahan',
    'kecamatan',
    'kabkota',
    'provinsi',
    'kode_pos',
    'nik_kk',
    'nama_lengkap_ayah',
    'pendidikan_ayah',
    'pekerjaan_ayah',
    'nama_lengkap_ibu',
    'pendidikan_ibu',
    'pekerjaan_ibu',
    'no_hp',
    'no_tes',
    'kelas',
    'kamar_id',
    'tahfidz_id',
    'photo',
  ];
}
