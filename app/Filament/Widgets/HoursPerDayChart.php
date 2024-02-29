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
            ->where('date', '<=', now()->format('Y-m-d'))
            ->where('date', '>', now()->subDays(6)->format('Y-m-d'))
            ->where('approved', true)
            ->get();

        $weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $dataPerDay = [];
        $widgetData = [];

        foreach ($punches as $punch) {
            $date = new \DateTime($punch->date);
            $day = $date->format('D');
            $dataPerDay[$day][] = $punch->time;
        }

        foreach ($dataPerDay as $day => $punches) {
            $totalHours = 0.0;
            $punchesCount = count($punches);

            for ($i = 0; $i < $punchesCount; $i += 2) {
                if (!isset($punches[$i + 1])) {
                    break;
                }

                $start = new \DateTime($punches[$i]);
                $end = new \DateTime($punches[$i + 1]);

                $totalHours += round(($start->diff($end)->h * 60 + $start->diff($end)->i) / 60, 2);
            }

            $widgetData[$day] = $totalHours;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Hours',
                    'data' => $widgetData,
                ],
            ],
            'labels' => $weekDays,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
