<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasUuids;

    protected $model = "works";

    protected $fillable = [
        'nama',
        'zone_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'work_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
