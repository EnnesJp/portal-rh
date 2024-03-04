<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class Section extends Component
{
    protected string $view = 'forms.components.section';

    public function __construct(
        protected string | \Closure $heading,
    )
    {

    }

    public static function make(string | \Closure $heading): static
    {
        return app(static::class, [
            'heading' => $heading,
        ]);
    }

    public function getHeading()
    {
        return $this->evaluate($this->heading);
    }
}
