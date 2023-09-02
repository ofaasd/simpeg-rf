<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
  use HasFactory;

  protected $table = 'presences';

  protected $fillable = [
    'is_remote',
    'user_id',
    'day',
    'working_absent',
    'working_dispensation',
    'working_dispensation_reason',
    'start_dispensation',
    'start',
    'lat_start',
    'long_start',
    'ip_start',
    'browser_start',
    'isp_start',
    'image_start',
    'start_late_five_mins',
    'start_late_more_fiveteen_mins',
    'end',
    'lat_end',
    'long_end',
    'ip_end',
    'browser_end',
    'isp_end',
    'image_end',
    'start_marked_by_admin',
    'end_marked_by_system',
    'overtime',
    'remark',
  ];

  protected $dateFormat = 'U';

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
