<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MetafieldLevel extends Model
{
    use HasUuids;

    protected $model = "metafield_levels";

    protected $fillable = [
        'field_name',
        'halaman',
        'zone_id',
    ];

    public function level()
    {
        return $this->hasMany(Level::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
