<?php

namespace App\Tables\Filters;

use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;

class DateRangeFilter extends BaseFilter
{
    protected string | \DateTimeInterface | \Closure | null $maxDate = null;
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->form(fn (): array => [
                Forms\Components\Fieldset::make($this->getLabel())
                    ->schema([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('From')
                            ->maxDate($this->getMaxDate())
                        ->dateformat('Y-m-d'),
                        Forms\Components\DatePicker::make('date_to')
                            ->label('To')
                            ->maxDate($this->getMaxDate()),
                    ])
                    ->columns(1),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['date_from'],
                        fn (Builder $query, $date_from): Builder => $query
                            ->whereDate($this->getName(), '>=', $date_from),
                    )
                    ->when(
                        $data['date_to'],
                        fn (Builder $query, $date_until): Builder => $query
                            ->whereDate($this->getName(), '<=', $date_until),
                    );
            });
    }

    public function maxDate(string | \DateTimeInterface | \Closure | null $date): static
    {
        $this->maxDate = $date;

        return $this;
    }

    public function getMaxDate(): string | \DateTimeInterface | null
    {
        return $this->evaluate($this->maxDate);
    }
}
