<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PointChangedLog extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typeText(): string
    {
        return match ($this->type) {
            'c' => '충전',
            'e' => '적립',
            'u' => '사용',
            'r' => '환불',
            'x' => '만기',
            default => '',
        };
    }

    public function formattedChangedAmount(): string
    {
        return number_format($this->changed_amount, 0);
    }

    public function pointDetailChangedLogs(): HasMany
    {
        return $this->hasMany(PointDetailChangedLog::class);
    }
}
