<?php

namespace App\Filament\Resources\EmailResource\Widgets;

use App\Models\Email;
use Filament\Widgets\Widget;

class EmailContent extends Widget
{
    public ?Email $record = null;

    protected static string $view = 'filament.resources.email-resource.widgets.email-content';
}
