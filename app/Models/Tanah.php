<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanah extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'aset_tanah';

    protected $fillable = ['kode', 'alamat', 'luas', 'tanggal_perolehan', 'nama', 'no_sertifikat', 'status_tanah', 'keterangan', 'bukti_fisik'];
}
