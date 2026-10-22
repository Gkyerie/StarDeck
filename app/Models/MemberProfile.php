<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberProfile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'phone',
        'rank_or_position',
        'station_module',
        'address',
        'photo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
