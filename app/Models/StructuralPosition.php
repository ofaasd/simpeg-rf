<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class StructuralPosition extends Model
{
  use HasFactory;
  protected $table = 'structural_positions';
  protected $fillable = ['type', 'structural_position_id', 'name', 'description'];
  protected $dateFormat = 'U';

  protected $appends = ['school_array'];

  public function getSchoolArrayAttribute()
  {
    $schools = $this->schools
      ->map(function ($school) {
        return $school->name;
      })
      ->toArray();
    return empty($schools) ? ['Yayasan'] : $schools;
  }

  public function schools()
  {
    return $this->belongsToMany(
      School::class,
      'school_identity_structural_position',
      'structural_position_id',
      'school_identity_id'
    );
  }

  public function superordinate()
  {
    return $this->belongsTo(StructuralPosition::class, 'structural_position_id', 'id');
  }
  public function pegawai()
  {
    return $this->HasMany(EmployeeNew::class, 'id', 'jabatan_new');
  }

  public function subordinates()
  {
    return $this->hasMany(StructuralPosition::class, 'structural_position_id', 'id')->with('subordinates');
  }

  public function salary()
  {
    return $this->hasOne(StructuralPositionSalary::class, 'structural_position_id', 'id');
  }

  public function workhour()
  {
    return $this->hasOne(StartHour::class, 'structural_position_id', 'id');
  }

  public function getAllSubordinates()
  {
    $sections = new Collection();

    foreach ($this->subordinates as $section) {
      $sections->push($section);
      $sections = $sections->merge($section->getAllSubordinates());
    }

    return $sections;
  }
}
