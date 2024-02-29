<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PunchResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class NotApprovedsPunch extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                PunchResource::getEloquentQuery()
                    ->join('users', 'punches.user_id', '=', 'users.id')
                    ->where('users.company_id', auth()->user()->company_id)
                    ->where('approved', false)
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Time')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\CheckboxColumn::make('approved')
                    ->label('Approved')
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
