<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PsbPesertaOnline
 *
 * @property int $id
 * @property string|null $nik
 * @property string $nama
 * @property string|null $nama_panggilan
 * @property string $jenis_kelamin
 * @property string $tempat_lahir
 * @property Carbon $tanggal_lahir
 * @property int|null $usia_bulan
 * @property int|null $usia_tahun
 * @property int|null $jumlah_saudara
 * @property int|null $anak_ke
 * @property string|null $alamat
 * @property int|null $prov_id
 * @property int|null $kota_id
 * @property string|null $kecamatan
 * @property string|null $kelurahan
 * @property string|null $rt
 * @property string|null $rw
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 * @property int $user_id
 * @property int $status
 * @property string $no_pendaftaran
 * @property int $gelombang_id
 *
 * @package App\Models
 */
class PsbPesertaOnline extends Model
{
  use SoftDeletes;
  protected $table = 'psb_peserta_online';
  protected $dateFormat = 'U';

  protected $casts = [
    'tanggal_lahir' => 'int',
    'usia_bulan' => 'int',
    'usia_tahun' => 'int',
    'jumlah_saudara' => 'int',
    'anak_ke' => 'int',
    'prov_id' => 'int',
    'kota_id' => 'int',
    'user_id' => 'int',
    'status' => 'int',
    'gelombang_id' => 'int',
  ];

  protected $fillable = [
    'nik',
    'nama',
    'nama_panggilan',
    'jenis_kelamin',
    'tempat_lahir',
    'tanggal_lahir',
    'usia_bulan',
    'usia_tahun',
    'jumlah_saudara',
    'anak_ke',
    'alamat',
    'prov_id',
    'kota_id',
    'kecamatan',
    'kelurahan',
    'rt',
    'rw',
    'user_id',
    'status',
    'no_pendaftaran',
    'gelombang_id',
    'kode_pos',
    'input_by',
  ];
}
