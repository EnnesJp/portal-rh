<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PunchResource;
use Filament\Widgets\ChartWidget;

class HoursPerDayChart extends ChartWidget
{
    protected static ?string $heading = 'Hours Per Day';

    protected function getData(): array
    {
        $punches = PunchResource::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->where('date', '<', now()->format('Y-m-d'))
            ->where('date', '>=', now()->subDays(6)->format('Y-m-d'))
            ->where('approved', true)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Hours',
                    'data' => [0, 9, 7.5, 6.9, 7.9, 8, 0],
                ],
            ],
            'labels' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
