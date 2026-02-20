<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dakwah extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dakwah';

    protected $fillable = [
        'slug',
        'judul',
        'foto',
        'link',
        'slug',
        'isi_dakwah',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
