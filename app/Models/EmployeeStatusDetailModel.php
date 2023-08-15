<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeStatusDetailModel extends Model
{
  use HasFactory;
  protected $table = 'employee_status_details';

  protected $fillable = ['employee_status_id', 'name', 'can_leave'];

  protected $dateFormat = 'U';

  public function status(): BelongsTo
  {
    return $this->belongsTo(EmployeeStatusModel::class, 'employee_status_id', 'id');
  }
}
