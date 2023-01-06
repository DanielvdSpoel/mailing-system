<?php

namespace App\Filament\Resources\EmailAddressResource\Pages;

use App\Filament\Resources\EmailAddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmailAddress extends ViewRecord
{
    protected static string $resource = EmailAddressResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
