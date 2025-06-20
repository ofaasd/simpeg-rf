<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelengkapan extends Model
{
    use HasFactory;

    protected $table = 'kelengkapan';
    protected $fillable = [
        'no_induk',
        'tanggal',
        'perlengkapan_mandi',
        'catatan_mandi',
        'peralatan_sekolah',
        'catatan_sekolah',
        'perlengkapan_diri',
        'catatan_diri',
    ];
}
