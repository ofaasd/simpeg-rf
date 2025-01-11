<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $dateFormat = 'U';
    protected $table = 'fasilitas';

    protected $fillable = ['nama', 'deskripsi', 'published', 'foto'];
}
