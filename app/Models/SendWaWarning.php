<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendWaWarning extends Model
{
  use HasFactory;
  protected $table = 'tb_send_wa_warning';

  protected $fillable = ['no_wa', 'pesan', 'id_santri', 'periode', 'tahun'];

}
