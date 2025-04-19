<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_barang';

    protected $fillable = [
        'kode_jenis_barang', 
        'kode_ruang', 'nama', 
        'kondisi_penerimaan', 
        'tanggal_perolehan', 
        'status', 
        'catatan'
    ];
}
