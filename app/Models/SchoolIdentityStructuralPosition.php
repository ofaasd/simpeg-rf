<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolIdentityStructuralPosition extends Model
{
  use HasFactory;
  protected $table = 'school_identity_structural_position';
  protected $fillable = ['structural_position_id', 'school_identity_id'];
  protected $dateFormat = 'U';
}
