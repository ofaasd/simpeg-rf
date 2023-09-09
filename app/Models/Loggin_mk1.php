<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loggin_mk1 extends Model
{
  use HasFactory;
  protected $table = 'log_ref_1';

  protected $fillable = ['code', 'name', 'employee_id', 'tahun_ajaran_id', 'status', 'jenis'];

  protected $dateFormat = 'U';
}
