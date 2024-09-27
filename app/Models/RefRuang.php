<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefRuang extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'ref_ruang';

    protected $fillable = ['id_gedung', 'id_lantai', 'id_jenis_ruang', 'nama', 'kode_ruang', 'kapasitas', 'catatan'];
}
