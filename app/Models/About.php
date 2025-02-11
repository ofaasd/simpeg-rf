<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $dateFormat = 'U';
    protected $table = 'about';

    protected $fillable = [
        'tentang', 
        'visi', 
        'misi', 
        'alamat', 
        'sekolah', 
        'nsm', 
        'npsn', 
        'nara_hubung',
    ];
}
