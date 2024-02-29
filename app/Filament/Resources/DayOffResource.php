<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DayOffResource\Pages;
use App\Filament\Resources\DayOffResource\RelationManagers;
use App\Models\DayOff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DayOffResource extends Resource
{
    protected static ?string $model = DayOff::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'vacation' => 'Vacation',
                        'sick' => 'Sick',
                        'personal' => 'Personal',
                        'holiday' => 'Holiday',
                        'other' => 'Other',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'vacation' => 'Vacation',
                        'sick' => 'Sick',
                        'personal' => 'Personal',
                        'holiday' => 'Holiday',
                        'other' => 'Other',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDayOffs::route('/'),
        ];
    }
}
