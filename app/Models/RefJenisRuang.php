<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefJenisRuang extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'ref_jenis_ruang';

    protected $fillable = ['kode', 'nama'];
}
