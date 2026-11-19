<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Khotimin extends Model
{
    use HasFactory;

    protected $table = 'tbl_khotimin';

    protected $fillable = [
        'kode_santri',
        'santri_id',
        'id_tahun_ajaran',
        'tanggal_khatam',
        'kelas',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_khatam' => 'date',
        'santri_id' => 'integer',
        'id_tahun_ajaran' => 'integer',
    ];

    /**
     * Relasi ke Santri (santri_detail) melalui santri_id
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'santri_id', 'id');
    }

    /**
     * Relasi ke Santri (santri_detail) melalui kode_santri (no_induk)
     */
    public function santriByKode(): BelongsTo
    {
        return $this->belongsTo(Santri::class, 'kode_santri', 'no_induk');
    }

    /**
     * Relasi ke Tahun Ajaran (ref_tahun_ajaran)
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id');
    }

    /**
     * Relasi ke Kelas (ref_kelas)
     */
    public function kelasRef(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas', 'code');
    }
}
