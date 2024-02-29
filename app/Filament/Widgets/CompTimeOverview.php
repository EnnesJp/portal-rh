<?php

namespace App\Filament\Widgets;

use App\Models\CompTime;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CompTimeOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $hours = auth()->user()->comp_minutes/60;
        $minutes = auth()->user()->comp_minutes%60;
        $compTime = floor($hours) . 'h ' . ($minutes ? $minutes . 'm' : '');

        $workedHours = CompTime::where('user_id', auth()->user()->id)
            ->sum('total_minutes');

        $workedHours = floor($workedHours/60) . 'h ' . ($workedHours%60 ? $workedHours%60 . 'm' : '');

        return [
            Stat::make('Total comp. time', $compTime)
                ->icon('heroicon-o-clock'),
            Stat::make('Week worked hours', $workedHours),
            Stat::make('Expected week worked hours', date("w")*8 . 'h'),
        ];
    }
}
