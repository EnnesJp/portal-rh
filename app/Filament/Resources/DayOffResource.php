<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DayOffResource\Pages;
use App\Filament\Resources\DayOffResource\RelationManagers;
use App\Models\DayOff;
use App\Models\User;
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
                        'vacation' => 'Vacation',
                        'sick' => 'Sick',
                        'personal' => 'Personal',
                        'holiday' => 'Holiday',
                        'other' => 'Other',
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
                            'vacation' => 'success',
                            'sick' => 'danger',
                            'personal' => 'warning',
                            'holiday' => 'primary',
                            'other' => 'info',
                        };
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Reason')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name', function (Builder $query) {
                        return $query->where('company_id', auth()->user()->company_id);
                    }),
                Tables\Filters\Filter::make('date')
                    ->label('Date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('From')
                            ->displayFormat('d/m/Y'),
                        Forms\Components\DatePicker::make('date_until')
                            ->label('Until')
                            ->displayFormat('d/m/Y'),
                    ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['date_from'],
                            fn (Builder $query, $date_from): Builder => $query
                                ->whereDate('date', '>=', $date_from),
                        )
                        ->when(
                          $data['date_until'],
                          fn (Builder $query, $date_until): Builder => $query
                              ->whereDate('date', '<=', $date_until),
                        );
                })
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
