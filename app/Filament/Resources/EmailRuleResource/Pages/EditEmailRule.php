<?php

namespace App\Filament\Resources\EmailRuleResource\Pages;

use App\Filament\Resources\EmailRuleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailRule extends EditRecord
{
    protected static string $resource = EmailRuleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
