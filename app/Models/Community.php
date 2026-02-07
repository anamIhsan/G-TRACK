<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasUuids;

    protected $model = "communities";

    protected $fillable = [
        'nama',
        'zone_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
