<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required()
                    ->searchable()
                    ->label('Company'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->hidden(fn(string $context): bool => $context !== 'create')
                    ->maxLength(255),
                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->hidden(fn(string $context): bool => $context !== 'create')
                    ->maxLength(255)
                    ->dehydrated(false)
                    ->same('password'),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'manager' => 'Manager',
                        'user' => 'User',
                    ])
                    ->default('user'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('company.name')
                    ->hidden(auth()->user()->isManager()),
            ])
            ->filters([
                //
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

    public static function getNavigationBadge(): ?string
    {
        return User::query()->where('company_id', auth()->user()->company_id)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'settings' => Pages\Settings::route('/settings'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->isManager()
            ? parent::getEloquentQuery()->where('company_id', auth()->user()->company_id)
            : parent::getEloquentQuery();
    }
}
