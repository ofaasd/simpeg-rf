<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanBulananKemadrasahan extends Model
{
    use HasFactory;

    protected $table = 'laporan_bulanan_kemadrasahan';
    protected $guarded = ['id'];

    public function details(): HasMany
    {
        return $this->hasMany(DetailPenilaianKemadrasahan::class, 'id_laporan');
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'no_induk', 'no_induk');
    }
}
