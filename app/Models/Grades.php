<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
  use HasFactory;

  protected $table = 'grades';

  protected $fillable = ['name', 'description'];

  protected $dateFormat = 'U';

  public function pegawai(): HasMany
  {
    return $this->HasMany(EmployeeNew::class, 'id', 'pendidikan');
  }
}
