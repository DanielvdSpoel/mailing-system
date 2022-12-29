<?php

namespace App\Filament\Resources\InboxResource\Pages;

use App\Filament\Resources\InboxResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInboxes extends ListRecords
{
    protected static string $resource = InboxResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
