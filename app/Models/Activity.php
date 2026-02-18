<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasUuids;

    protected $model = "activities";

    protected $fillable = [
        'nama',
        'type',
        'tanggal',
        'hari',
        'jam',
        'materi',
        'tempat',
        'no_pj',
        'for_status_kawin',
        'zone_id',
    ];

    protected $appends = ['nama_hari'];

    public function getNamaHariAttribute()
    {
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return $hari[$this->hari - 1];
    }

    /**
     * Get the age categories that the activity belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ageCategories()
    {
        return $this->belongsToMany(AgeCategory::class, 'age_category_activity')->using(AgeCategoryActivity::class)
            ->withPivot('id');
    }

    /**
     * Get the zone that owns the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}
