<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TemplatePesan
 *
 * @property int $id
 * @property string|null $pesan
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $deleted_at
 * @property int $status
 *
 * @package App\Models
 */
class TemplatePesan extends Model
{
  use SoftDeletes;
  protected $table = 'template_pesan';
  protected $dateFormat = 'U';

  protected $casts = [
    'status' => 'int',
  ];

  protected $fillable = ['pesan', 'status'];
}
