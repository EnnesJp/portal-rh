<?php

namespace App\Filament\Resources\DayOffResource\Pages;

use App\Constants\DayOffsConstants;
use App\Filament\Resources\DayOffResource;
use App\Models\DayOff;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

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

    public function getTabs(): array
    {
        return [
            'All Days Off' => Tab::make(),
            'Vacation' => Tab::make()
                ->badge($this->getResource()::getEloquentQuery()->where('type', DayOffsConstants::VACATION)->count())
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('type', DayOffsConstants::VACATION)),
            'Sick' => Tab::make()
                ->badge($this->getResource()::getEloquentQuery()->where('type', DayOffsConstants::SICK)->count())
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('type', DayOffsConstants::SICK)),
            'Personal' => Tab::make()
                ->badge($this->getResource()::getEloquentQuery()->where('type', DayOffsConstants::PERSONAL)->count())
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('type', DayOffsConstants::PERSONAL)),
            'Holiday' => Tab::make()
                ->badge($this->getResource()::getEloquentQuery()->where('type', DayOffsConstants::HOLIDAY)->count())
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('type', DayOffsConstants::HOLIDAY)),
            'Other' => Tab::make()
                ->badge($this->getResource()::getEloquentQuery()->where('type', DayOffsConstants::OTHER)->count())
                ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('type', DayOffsConstants::OTHER)),
        ];
    }
}
