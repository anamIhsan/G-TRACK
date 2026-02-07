<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasUuids;

    protected $model = "groups";

    protected $fillable = [
        'nama',
        'village_id',
        'user_id',
        'zone_id',
    ];

    public function admin()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'group_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
