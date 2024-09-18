<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefLantai extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    protected $table = 'ref_lantai';

    protected $fillable = ['nama'];
}
