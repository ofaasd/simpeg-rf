<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpGolrus extends Model
{
  use HasFactory;
  protected $table = 'employee_golrus';

  protected $fillable = ['employee_id', 'golru_id', 'date_start', 'date_end', 'remark', 'keterangan'];

  protected $dateFormat = 'U';
}
