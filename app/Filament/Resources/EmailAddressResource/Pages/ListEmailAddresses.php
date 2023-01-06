<?php

namespace App\Filament\Resources\EmailAddressResource\Pages;

use App\Filament\Resources\EmailAddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailAddresses extends ListRecords
{
    protected static string $resource = EmailAddressResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
