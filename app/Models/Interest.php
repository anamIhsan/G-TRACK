<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasUuids;

    protected $model = "interests";

    protected $fillable = [
        'nama',
        'zone_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'interest_id', 'id');
    }

    public function subInterests()
    {
        return $this->hasMany(SubInterest::class, 'interest_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
