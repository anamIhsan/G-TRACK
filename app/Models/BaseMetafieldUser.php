<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BaseMetafieldUser extends Model
{
    use HasUuids;

    protected $model = "base_metafield_users";

    protected $fillable = [
        'value',
        'user_id',
        'metafield_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function metafieldUser()
    {
        return $this->belongsTo(MetafieldUser::class);
    }
}
