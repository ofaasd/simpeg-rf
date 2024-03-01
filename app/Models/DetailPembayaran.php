<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
  use HasFactory;
  protected $table = 'tb_detail_pembayaran';
  protected $dateFormat = 'U';

  public function tb_saku_keluar()
  {
    return $this->belongsTo(Pembayaran::class, 'id_pembayaran');
  }
}
