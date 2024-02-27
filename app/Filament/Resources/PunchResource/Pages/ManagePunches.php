<?php

namespace App\Filament\Resources\PunchResource\Pages;

use App\Filament\Resources\PunchResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePunches extends ManageRecords
{
    protected static string $resource = PunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
