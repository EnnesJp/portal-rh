<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PunchResource;
use App\Models\Punch;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Date;

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
                Tables\Actions\Action::make('Include Punch')
                    ->icon('heroicon-o-plus-circle')
                    ->outlined()
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->default(now()->format('Y-m-d'))
                            ->required(),
                        Forms\Components\TimePicker::make('time')
                            ->default(now()->format('H:i'))
                            ->required(),
                        Forms\Components\TextInput::make('reference')
                            ->required(),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['approved'] = false;
                        $data['is_manual'] = true;
                        return $data;
                    })
                    ->action(function (array $data) {
                        Punch::create($data);
                        Notification::make()
                            ->title('Punch included successfully!')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('Register Punch')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->readOnly()
                            ->default(now()->format('Y-m-d')),
                        Forms\Components\TimePicker::make('time')
                            ->readOnly()
                            ->default(now()->format('H:i')),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['reference'] = '1';
                        return $data;
                    })
                    ->action(function (array $data) {
                        Punch::create($data);
                        Notification::make()
                            ->title('Punch registered successfully!')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->success()
                            ->send();
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y'),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approved')
                    ->options([
                        'true' => 'Approved',
                        'false' => 'Not Approved',
                    ]),
                Tables\Filters\SelectFilter::make('date')
                    ->options(fn () => Punch::query()->pluck('date')->unique()->mapWithKeys(fn ($date) => [$date => date('d/m/Y', strtotime($date))]))
                    ->default(now()->format('Y-m-d')),
            ]);
    }
}
