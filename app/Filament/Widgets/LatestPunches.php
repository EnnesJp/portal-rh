<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PunchResource;
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
            ->columns([
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y'),
                Tables\Columns\CheckboxColumn::make('approved')
                    ->disabled(),
            ]);
    }
}
