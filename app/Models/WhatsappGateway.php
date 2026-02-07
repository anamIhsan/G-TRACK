<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class WhatsappGateway extends Model
{
    use HasUuids;

    protected $model = "whatsapp_gateways";

    protected $fillable = [
        'type',
        'config',
        'user_id',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
