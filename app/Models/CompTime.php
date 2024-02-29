<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompTime extends Model
{
    use HasFactory;

    protected $table = 'comp_time';

    protected $fillable = [
        'user_id',
        'date',
        'total_minutes',
        'comp_minutes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
