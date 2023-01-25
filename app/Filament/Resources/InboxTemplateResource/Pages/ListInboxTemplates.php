<?php

namespace App\Filament\Resources\InboxTemplateResource\Pages;

use App\Filament\Resources\InboxTemplateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInboxTemplates extends ListRecords
{
    protected static string $resource = InboxTemplateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
