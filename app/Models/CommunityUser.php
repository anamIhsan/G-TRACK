<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CommunityUser extends Model
{
    use HasUuids;

    protected $model = "community_user";

    protected $fillable = [
        'user_id',
        'community_id',
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
