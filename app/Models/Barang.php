<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_barang';

    protected $fillable = ['id_jenis_barang', 'nama', 'kondisi_penerimaan', 'tanggal_perolehan', 'catatan'];
}
