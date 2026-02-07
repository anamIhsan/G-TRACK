<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasUuids;

    protected $model = "levels";

    protected $fillable = [
        'value',
        'halaman',
        'status',
        'user_id',
        'metafield_level_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function metafieldLevels()
    {
        return $this->belongsTo(MetafieldLevel::class, 'metafield_level_id', 'id');
    }
}
