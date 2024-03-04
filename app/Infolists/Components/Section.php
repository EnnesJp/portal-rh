<?php

namespace App\Infolists\Components;

use Filament\Infolists\Components\Component;

class Section extends Component
{
    protected string $view = 'infolists.components.section';

    protected string | \Closure | null $description = null;
    protected string | \Closure | null $icon = null;

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

    public function description(string | \Closure | null $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function icon(string | \Closure | null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getHeading(): string
    {
        return $this->evaluate($this->heading);
    }

    public function getDescription(): ?string
    {
        return $this->evaluate($this->description);
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }
}
