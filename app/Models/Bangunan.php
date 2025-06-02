<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bangunan extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_bangunan';

    protected $fillable = [
        'nama',
        'kode_gedung', 
        'id_lantai', 
        'kode_tanah', 
        'luas', 
        'status', 
        'kondisi', 
        'tanggal_pembangunan'
    ];
}
