<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenilaianKemadrasahan extends Model
{
    use HasFactory;

    protected $table = 'detail_penilaian_kemadrasahan';
    protected $guarded = ['id'];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(LaporanBulananKemadrasahan::class, 'id_laporan');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(RefMapel::class, 'id_mapel');
    }

    public function pengampu(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengampu');
    }
}
