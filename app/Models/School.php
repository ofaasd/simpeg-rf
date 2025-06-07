<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
  use HasFactory;
  protected $table = 'school_identities';

  protected $fillable = ['name', 'grade', 'address', 'phone', 'fax', 'email', 'latitude', 'website', 'employee_id'];

  protected $dateFormat = 'U';
}
