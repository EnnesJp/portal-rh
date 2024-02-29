<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PunchResource;
use Filament\Widgets\ChartWidget;

class HoursPerDayChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Hours Per Day - Week View';

    protected function getData(): array
    {
        $punches = PunchResource::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->where('date', '<=', now()->format('Y-m-d'))
            ->where('date', '>', now()->subDays(6)->format('Y-m-d'))
            ->where('approved', true)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        $weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $dataPerDay = $this->getDayPunches($punches);
        $widgetData = $this->getHoursPerDay($dataPerDay);

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

    private function getDayPunches($punches): array
    {
        $dayPunches = [];

        foreach ($punches as $punch) {
            $date = new \DateTime($punch->date);
            $day = $date->format('D');
            $dayPunches[$day][] = $punch->time;
        }

        return $dayPunches;
    }

    private function getHoursPerDay($dataPerDay): array
    {
        $hoursPerDay = [];

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

            $hoursPerDay[$day] = $totalHours;
        }

        return $hoursPerDay;
    }
}
