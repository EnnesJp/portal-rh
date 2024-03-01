<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PunchResource\Pages;
use App\Filament\Resources\PunchResource\RelationManagers;
use App\Models\Punch;
use App\Models\User;
use Filament\Resources\Components\Tab;
use Filament\Tables\Actions\CreateAction;
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

    protected static ?string $label = 'Company Punch';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TimePicker::make('time')
                    ->readOnly()
                    ->default(now()->format('H:i')),
                Forms\Components\DatePicker::make('date')
                    ->readOnly()
                    ->default(now()->format('Y-m-d')),
                Forms\Components\TextInput::make('reference')
                    ->readOnly()
                    ->default(function (): string {
                        return auth()->user()->punches->last()->reference ?? '1';
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y'),
                Tables\Columns\CheckboxColumn::make('approved')
                    ->disabled(!auth()->user()->isManager()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('date')
                    ->options(fn () => Punch::query()->pluck('date')->unique()->mapWithKeys(fn ($date) => [$date => date('d/m/Y', strtotime($date))]))
                    ->default(now()->format('Y-m-d')),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name', function (Builder $query) {
                        return $query->where('company_id', auth()->user()->company_id);
                    })
                    ->label('User'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
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

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->isAdmin()) {
            return parent::getEloquentQuery();
        } else if (auth()->user()->isManager()) {
            return parent::getEloquentQuery()
                ->join('users', 'users.id', '=', 'punches.user_id')
                ->where('users.company_id', auth()->user()->company_id);
        }

        return parent::getEloquentQuery()->where('user_id', auth()->user()->id);
    }
}
