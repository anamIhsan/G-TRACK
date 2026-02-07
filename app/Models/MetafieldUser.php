<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MetafieldUser extends Model
{
    use HasUuids;

    protected $model = "metafield_users";

    protected $fillable = [
        'field',
        'type',
        'enum_values',
        'zone_id',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
