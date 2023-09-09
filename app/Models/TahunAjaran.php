<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
  use HasFactory;
  protected $table = 'ref_tahun_ajaran';

  protected $fillable = ['id_tahun', 'awal', 'akhir', 'jenis', 'is_aktif'];

  protected $dateFormat = 'U';
}
