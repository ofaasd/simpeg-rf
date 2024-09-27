<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elektronik extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_elektronik';

    protected $fillable = ['id_gedung', 'id_lantai', 'id_jenis_ruang', 'nama', 'status', 'kapasitas', 'catatan'];
}
