<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurban extends Model
{
    use HasFactory;
    protected $table = 'kurban';

    protected $fillable = [
        'id_santri',
        'jumlah',
        'jenis',
        'atas_nama',
        'foto',
        'tanggal',
        'tahun_hijriah'
    ];

    public $timestamps = true;
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id');
    }
}
