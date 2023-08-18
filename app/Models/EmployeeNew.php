<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeNew extends Model
{
  use HasFactory;

  protected $table = 'employee_new';

  protected $fillable = [
    'nik',
    'nama',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'jabatan_new',
    'alamat',
    'pendidikan',
    'pendidikan',
    'pengangkatan',
    'lembaga_induk',
    'lembaga_diikuti',
  ];

  protected $dateFormat = 'U';
}
