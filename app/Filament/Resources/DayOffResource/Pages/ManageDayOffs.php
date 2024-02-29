<?php

namespace App\Filament\Resources\DayOffResource\Pages;

use App\Filament\Resources\DayOffResource;
use App\Models\DayOff;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDayOffs extends ManageRecords
{
    protected static string $resource = DayOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['company_id'] = auth()->user()->company_id;
                    return $data;
                })
                ->action(function (array $data) {
                    foreach ($data['users'] as $user_id) {
                        DayOff::create([
                            'user_id' => $user_id,
                            'date' => $data['date'],
                            'type' => $data['type'],
                            'reason' => $data['reason'],
                            'company_id' => $data['company_id'],
                        ]);
                    }
                }),
        ];
    }
}
