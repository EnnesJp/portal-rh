<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DatePicker::configureUsing(function (DatePicker $datePicker) {
            $datePicker->displayFormat('d/m/Y');
        });

        SelectFilter::macro('userSelect', function () {
            $this
                ->label('User')
                ->relationship('user', 'name', function (Builder $query) {
                    return $query->where('company_id', auth()->user()->company_id);
                });

            return $this;
        });

        Filter::macro('dateRange', function () {
            $this
                ->form([
                Forms\Components\DatePicker::make('date_from')
                    ->label('From'),
                Forms\Components\DatePicker::make('date_until')
                    ->label('Until'),
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
                });

            return $this;
        });

        Model::unguard();
    }
}
