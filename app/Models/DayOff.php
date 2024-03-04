<?php

namespace App\Models;

use App\Constants\DayOffsConstants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Forms;

class DayOff extends Model
{
    use HasFactory;

    protected $table = 'day_offs';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'date',
        'type',
        'reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getForm(): array
    {
        return [
            Forms\Components\DatePicker::make('date')
                ->required(),
            Forms\Components\Select::make('type')
                ->options([
                    DayOffsConstants::VACATION => 'Vacation',
                    DayOffsConstants::SICK => 'Sick',
                    DayOffsConstants::PERSONAL => 'Personal',
                    DayOffsConstants::HOLIDAY => 'Holiday',
                    DayOffsConstants::OTHER => 'Other',
                ])
                ->required(),
            Forms\Components\TextInput::make('reason')
                ->maxLength(255),
            Forms\Components\Select::make('users')
                ->multiple()
                ->label('User')
                ->options(function (Builder $query) {
                    return User::query()
                        ->where('company_id', auth()->user()->company_id)
                        ->pluck('name', 'id');
                })
        ];
    }
}
