<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golrus extends Model
{
  use HasFactory;

  protected $table = 'golrus';

  protected $fillable = ['name', 'room', 'index', 'level', 'salary'];

  protected $dateFormat = 'U';
}
