<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawatInap extends Model
{
    use HasFactory, SoftDeletes;

  protected $table = 'rawat_inap';
  protected $dateFormat = 'U';

  protected $casts = [
    'santri_no_induk' => 'int',
    'murroby_id' => 'int',
  ];

  protected $fillable = [
    'tanggal_masuk',
    'tanggal_keluar',
    'santri_no_induk',
    'murroby_id',
    'kelas_id',
    'keluhan',
    'terapi',
    'created_at',
    'updated_at',
  ];
}
