<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;
    protected $dateFormat = 'U';
    protected $table = 'prestasi';

    protected $fillable = ['no_induk','deskripsi', 'jenis', 'prestasi', 'tingkat', 'foto'];
}
