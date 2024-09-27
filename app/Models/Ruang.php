<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_ruang';

    protected $fillable = ['id_gedung', 'id_lantai', 'kode_ruang', 'id_jenis_ruang', 'last_checking', 'nama', 'status', 'kapasitas', 'catatan'];
}
