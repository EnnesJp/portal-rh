<?php

namespace App\Filament\Resources\DayOffResource\Pages;

use App\Filament\Resources\DayOffResource;
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
                }),
        ];
    }
}
