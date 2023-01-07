<?php

namespace App\Filament\Resources\EmailResource\Pages;

use App\Filament\Resources\EmailResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmail extends ViewRecord
{
    protected static string $resource = EmailResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }

    protected function getFooterWidgetsColumns(): int|array
    {
        return 1;
    }

    protected function getFooterWidgets(): array
    {
        return [
            EmailResource\Widgets\EmailContent::class,
        ];
    }
}
