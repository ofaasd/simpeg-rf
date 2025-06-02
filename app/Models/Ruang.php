<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_ruang';

    protected $fillable = [
        'kode_gedung', 
        'id_lantai', 
        'kode', 
        'kode_jenis_ruang', 
        'last_checking', 
        'nama', 
        'status', 
        'kapasitas', 
        'catatan'
    ];
}
