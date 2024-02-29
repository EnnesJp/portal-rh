<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PunchResource;
use App\Models\Punch;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Collection;

class NotApprovedsPunch extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No punches to approve')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->query(
                PunchResource::getEloquentQuery()
                    ->where('approved', false)
                    ->latest()
                    ->limit(5)
            )
            ->actions([
                Tables\Actions\Action::make('Approve Punch')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Punch $punch) {
                        $punch->update(['approved' => true]);
                    }),
            ])

            ->bulkActions([
                Tables\Actions\BulkAction::make('Approve Punches')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->outlined()
                    ->action(function (Collection $records) {
                        $records->each->update(['approved' => true]);
                    })
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation()
                    ->modalHeading('Approve Punches')
                    ->modalSubheading('Are you sure you want to approve the selected punches?')
                    ->modalButton('Approve Punches')
                    ->modalIcon('heroicon-o-check'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Time')
                    ->searchable()
                    ->sortable(),
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()->isManager()
            || auth()->user()->isAdmin();
    }
}
