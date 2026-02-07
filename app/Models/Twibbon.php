<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Twibbon extends Model
{
    use HasUuids;

    protected $model = "twibbons";

    protected $fillable = [
        'zone_id',
        'twibbon',
    ];
}
