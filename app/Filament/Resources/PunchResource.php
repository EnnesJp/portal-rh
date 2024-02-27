<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PunchResource\Pages;
use App\Filament\Resources\PunchResource\RelationManagers;
use App\Models\Punch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PunchResource extends Resource
{
    protected static ?string $model = Punch::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('punch'),
                Tables\Columns\TextColumn::make('date'),
                Tables\Columns\TextColumn::make('reference'),
                Tables\Columns\CheckboxColumn::make('approved'),
            ])
            ->filters([
                //
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
            'index' => Pages\ManagePunches::route('/'),
        ];
    }
}
