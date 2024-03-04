<?php

namespace App\Forms\Components;

use App\Concerns\CanBeSection;
use Filament\Forms\Components\Component;

class Section extends Component
{
    use CanBeSection;

    protected string $view = 'section';
}
