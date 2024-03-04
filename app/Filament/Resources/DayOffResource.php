<?php

namespace App\Filament\Resources;

use App\Constants\DayOffsConstants;
use App\Filament\Resources\DayOffResource\Pages;
use App\Filament\Resources\DayOffResource\RelationManagers;
use App\Models\DayOff;
use App\Models\User;
use App\Tables\Filters\DateRangeFilter;
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

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        DayOffsConstants::VACATION => 'Vacation',
                        DayOffsConstants::SICK => 'Sick',
                        DayOffsConstants::PERSONAL => 'Personal',
                        DayOffsConstants::HOLIDAY => 'Holiday',
                        DayOffsConstants::OTHER => 'Other',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('reason')
                    ->maxLength(255),
                Forms\Components\Select::make('users')
                    ->multiple()
                    ->label('User')
                    ->options(function (Builder $query) {
                        return User::query()
                            ->where('company_id', auth()->user()->company_id)
                            ->pluck('name', 'id');
                    })
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
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->color(function (DayOff $record) {
                        return match ($record->type) {
                            DayOffsConstants::VACATION => 'success',
                            DayOffsConstants::SICK => 'danger',
                            DayOffsConstants::PERSONAL => 'warning',
                            DayOffsConstants::HOLIDAY => 'primary',
                            DayOffsConstants::OTHER => 'info',
                        };
                    })
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Reason')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->userSelect(),
                DateRangeFilter::make('date')
                    ->maxDate(now()),
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

    public static function getNavigationBadge(): ?string
    {
        return 'NEW';
    }
}
