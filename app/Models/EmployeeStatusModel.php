<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatusModel extends Model
{
  use HasFactory;
  protected $table = 'employee_statuses';

  protected $fillable = ['name'];

  protected $dateFormat = 'U';

  public function status_detail(): HasMany
  {
    return $this->hasMany(EmployeeStatusDetailModel::class, 'id', 'employee_status_id');
  }
}
