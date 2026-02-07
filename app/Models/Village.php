<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasUuids;

    protected $model = "villages";

    protected $fillable = [
        'nama',
        'user_id',
        'zone_id',
    ];

    /**
     * Get the admin user associated with the village.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function admin()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the users that belong to the village.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'village_id', 'id');
    }

    /**
     * Get the groups that belong to the village.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
