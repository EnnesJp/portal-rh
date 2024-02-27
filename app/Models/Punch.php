<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Punch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'time',
        'date',
        'reference',
        'is_manual',
        'approved'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
