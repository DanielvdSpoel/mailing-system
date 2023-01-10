<?php

namespace App\Filament\Resources\EmailRuleResource\Pages;

use App\Filament\Resources\EmailRuleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailRules extends ListRecords
{
    protected static string $resource = EmailRuleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
