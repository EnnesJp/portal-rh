<?php

namespace App\Filament\Resources\PunchResource\Pages;

use App\Filament\Resources\PunchResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManagePunches extends ManageRecords
{
    protected static string $resource = PunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Include Punch')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    return $data;
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All Punches' => Tab::make(),
            'Approved' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('approved', true)),
            'Not Approved' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('approved', false)),
        ];
    }
}
