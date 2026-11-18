<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefMapel extends Model
{
    use HasFactory;

    protected $table = 'ref_mapel';
    protected $guarded = ['id'];

    public function detailPenilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaianKemadrasahan::class, 'id_mapel');
    }
}
