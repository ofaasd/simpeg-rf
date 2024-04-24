<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
  use HasFactory;
  protected $table = 'tb_pembayaran';

  protected $fillable = [
		'nama_santri',
    'jumlah',
    'tanggal_bayar',
    'periode',
    'tahun' ,
    'bank_pengirim',
    'atas_nama',
    'catatan',
    'no_wa',
    'validasi',
    'note_validasi',
    'tipe',
    'input_by',
	];
}
