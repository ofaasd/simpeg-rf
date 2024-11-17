<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elektronik extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_elektronik';

    protected $fillable = [
        'id_ruang', 
        'nama', 
        'kondisi_penerimaan', 'tanggal_perolehan', 
        'garansi', 
        'spesifikasi', 
        'serial_number', 
        'last_checking', 
        'catatan', 
        'status'
    ];
}
