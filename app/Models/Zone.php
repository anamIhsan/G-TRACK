<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasUuids;

    protected $model = "zones";

    protected $fillable = [
        'nama',
        'user_id'
    ];


    public function admin()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    public function metafieldUser()
    {
        return $this->hasOne(MetafieldUser::class);
    }
}
