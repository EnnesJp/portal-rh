<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Forms\Components\ColorPicker;
use App\Forms\Components\Section;
use App\Infolists\Components\ColorEntry;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 10;

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'email',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Company' => $record->company->name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        // Used for improve performance
        return parent::getGlobalSearchEloquentQuery()
            ->with(['company']);
    }

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
                Section::make('Color Section')
                    ->description('Pick a color')
                    ->icon('heroicon-o-eye-dropper')
                    ->schema([
                        ColorPicker::make('color-1')
                            ->width(200),
                        ColorPicker::make('color-2')
                            ->default('00ff00')
                            ->width(200),
                        ColorPicker::make('color-3')
                            ->default('ff0000')
                            ->width(200),
                        ColorPicker::make('color-4')
                            ->default('0000ff')
                            ->width(200)
                    ])
                    ->columns(4)
                    ->columnSpan(2)
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
            ])
            ->recordUrl(
                fn (User $record): string => Pages\ViewUser::getUrl([$record->id]),
            );
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('company.name'),
                \App\Infolists\Components\Section::make('Color Section')
                    ->description('Pick a color')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Infolists\Components\ColorEntry::make('primary')
                            ->state('rgb(138,43,226)'),
                        Infolists\Components\ColorEntry::make('secondary')
                            ->state('rgb(255,255,255)'),
                        Infolists\Components\ColorEntry::make('success')
                            ->state('rgb(40,167,69)'),
                        Infolists\Components\ColorEntry::make('danger')
                            ->state('rgb(220,53,69)'),
                        Infolists\Components\ColorEntry::make('warning')
                            ->state('rgb(255,193,7)'),
                        Infolists\Components\ColorEntry::make('info')
                            ->state('rgb(23,162,184)'),
                    ])
                    ->columns(3)
                    ->columnSpan(2),
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
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->isManager()
            ? parent::getEloquentQuery()->where('company_id', auth()->user()->company_id)
            : parent::getEloquentQuery();
    }
}
