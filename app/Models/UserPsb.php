<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserPsb
 *
 * @property int $id
 * @property string|null $nik
 * @property string $nama
 * @property int $tanggal_lahir
 * @property string $alamat
 * @property int $prov_id
 * @property int $city_id
 * @property string $kecamatan
 * @property string $kelurahan
 * @property string $kode_pos
 * @property string $no_hp
 * @property string|null $username
 * @property string|null $password
 * @property Carbon|null $last_login_at
 * @property string|null $last_ip_login
 * @property Carbon|null $last_reset_password
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class UserPsb extends Model
{
  use SoftDeletes;
  protected $table = 'user_psb';
  protected $dateFormat = 'U';

  protected $casts = [
    'tanggal_lahir' => 'int',
    'prov_id' => 'int',
    'city_id' => 'int',
    'last_login_at' => 'datetime',
    'last_reset_password' => 'datetime',
  ];

  protected $hidden = ['password', 'last_reset_password'];

  protected $fillable = [
    'nik',
    'nama',
    'tanggal_lahir',
    'alamat',
    'prov_id',
    'city_id',
    'kecamatan',
    'kelurahan',
    'kode_pos',
    'no_hp',
    'username',
    'password',
    'last_login_at',
    'last_ip_login',
    'last_reset_password',
  ];
}
