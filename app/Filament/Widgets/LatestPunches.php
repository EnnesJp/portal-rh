<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PunchResource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPunches extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                PunchResource::getEloquentQuery()
                    ->where('user_id', auth()->user()->id)
                    ->latest()
                    ->limit(5)
            )
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y'),
                Tables\Columns\CheckboxColumn::make('approved')
                    ->disabled(),
            ]);
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('reference')
                ->label('Reference')
                ->readOnly()
                ->default(function (): string {
                    return auth()->user()->punches->last()->reference ?? '1';
                })
                ->columnSpan(2),
            Forms\Components\DatePicker::make('date')
                ->label('Date')
                ->default(now()->format('Y-m-d'))
                ->columnSpan(2),
            Forms\Components\TimePicker::make('time')
                ->label('Time')
                ->default(now()->format('H:i'))
                ->columnSpan(2),
        ];
    }
}
