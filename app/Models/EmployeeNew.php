<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    'photo',
  ];

  protected $dateFormat = 'U';
  public function pen(): BelongsTo
  {
    return $this->belongsTo(Grades::class, 'pendidikan', 'id');
  }
  public function jab(): BelongsTo
  {
    return $this->belongsTo(StructuralPosition::class, 'jabatan_new', 'id');
  }
}
