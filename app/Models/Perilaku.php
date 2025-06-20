<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perilaku extends Model
{
  use HasFactory;

  protected $table = 'perilaku';
  protected $fillable = [
    'no_induk',
    'tanggal',
    'ketertiban',
    'kebersihan',
    'kedisiplinan',
    'kerapian',
    'kesopanan',
    'kepekaan_lingkungan',
    'ketaatan_peraturan',
  ];
}
