<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefSiswa extends Model
{
  use HasFactory;
  protected $dateFormat = 'U';
  protected $table = 'ref_siswa';

  protected $fillable = ['kode', 'kode_murroby', 'nama', 'no_induk', 'password', 'status'];
}
