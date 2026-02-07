<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AgeCategory extends Model
{
    use HasUuids;

    protected $model = "age_categories";

    protected $fillable = [
        'nama',
        'range_one',
        'range_two',
    ];

    protected $appends = ['slug'];

    public function getSlugAttribute()
    {
        return Str::slug($this->nama, '_');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }
}
