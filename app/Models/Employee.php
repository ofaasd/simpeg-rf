<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Employee
 * 
 * @property int $id
 * @property Carbon|null $date_start
 * @property string $name
 * @property string $nip
 * @property string|null $system_id
 * @property Carbon|null $tmt_date
 * @property string $nik
 * @property string $no_kk
 * @property string $nuptk
 * @property int|null $sex
 * @property int|null $marital_status
 * @property int|null $religion
 * @property string|null $pob
 * @property Carbon|null $dob
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $whatsapp
 * @property string|null $email
 * @property string|null $image_file
 * @property string|null $image_type
 * @property string|null $kk_file
 * @property string|null $kk_type
 * @property string|null $ktp_file
 * @property string|null $ktp_type
 * @property int|null $employee_status_detail_id
 * @property int|null $structural_position_id
 * @property string|null $golru_id
 * @property int|null $academic_position_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Employee extends Model
{
	use SoftDeletes;
	protected $table = 'employees';

	protected $casts = [
		'date_start' => 'datetime',
		'tmt_date' => 'datetime',
		'sex' => 'int',
		'marital_status' => 'int',
		'religion' => 'int',
		'dob' => 'datetime',
		'employee_status_detail_id' => 'int',
		'structural_position_id' => 'int',
		'academic_position_id' => 'int'
	];

	protected $fillable = [
		'date_start',
		'name',
		'nip',
		'system_id',
		'tmt_date',
		'nik',
		'no_kk',
		'nuptk',
		'sex',
		'marital_status',
		'religion',
		'pob',
		'dob',
		'address',
		'phone',
		'whatsapp',
		'email',
		'image_file',
		'image_type',
		'kk_file',
		'kk_type',
		'ktp_file',
		'ktp_type',
		'employee_status_detail_id',
		'structural_position_id',
		'golru_id',
		'academic_position_id'
	];
}
