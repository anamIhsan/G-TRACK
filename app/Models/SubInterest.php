<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SubInterest extends Model
{
    use HasUuids;

    protected $model = "sub_interests";

    protected $fillable = [
        'nama',
        'interest_id',
        'zone_id',
    ];

    public function interest()
    {
        return $this->belongsTo(Village::class, 'interest_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
