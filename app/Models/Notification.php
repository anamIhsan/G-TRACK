<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasUuids;

    protected $model = "notifications";

    protected $fillable = [
        'judul',
        'message',
        'jenis_notification',
        'jenis_pengiriman',
        'tanggal',
        'jam',
        'activity_id',
        'zone_id',
        'sent',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
