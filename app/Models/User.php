<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasUuids, HasFactory, Notifiable, SoftDeletes, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['kelamin_format'];

    public function getKelaminFormatAttribute()
    {
        $kelamin = $this->kelamin;

        if ($kelamin === 'PR') {
            return "Perempuan";
        } else {
            return "Laki-Laki";
        }
    }

    public function whatsappGateway()
    {
        return $this->hasOne(WhatsappGateway::class, 'user_id', 'id');
    }

    public function ageCategory()
    {
        return $this->belongsTo(AgeCategory::class, 'age_category_id');
    }

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function interest()
    {
        return $this->belongsTo(Interest::class, 'interest_id');
    }

    public function subInterest()
    {
        return $this->belongsTo(SubInterest::class, 'sub_interest_id');
    }

    public function level()
    {
        return $this->hasMany(Level::class, 'user_id');
    }

    public function baseMetafieldUsers()
    {
        return $this->hasMany(BaseMetafieldUser::class, 'user_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }

    public function zoneAdmin()
    {
        return $this->hasOne(Zone::class, 'user_id', 'id');
    }

    public function presence()
    {
        return $this->belongsTo(Presence::class, 'id', 'user_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function presenceToday()
    {
        return $this->hasOne(Presence::class)
            ->whereDate('created_at', today());
    }
}
