<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

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


        Model::unguard();
    }
}
