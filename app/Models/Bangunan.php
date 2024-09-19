<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bangunan extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_bangunan';

    protected $fillable = ['nama','id_gedung', 'id_lantai', 'id_tanah', 'luas', 'status', 'kondisi', 'tanggal_pembangunan'];
}
