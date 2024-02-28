<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class HoursPerDayChart extends ChartWidget
{
    protected static ?string $heading = 'Hours Per Day';

    protected function getData(): array
    {
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
