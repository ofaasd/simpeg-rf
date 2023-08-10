<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{
  use HasFactory;
  protected $table = 'academic_positions';

  protected $fillable = ['name', 'description'];

  protected $dateFormat = 'U';
}
