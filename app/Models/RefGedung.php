<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefGedung extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'ref_gedung';

    protected $fillable = ['kode', 'nama'];
}
