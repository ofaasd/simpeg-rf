<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_ruang';

    protected $fillable = ['id_gedung', 'id_lantai', 'id_jenis_ruang', 'nama', 'kapasitas', 'catatan'];
}
