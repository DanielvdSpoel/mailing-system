<?php

namespace App\Filament\Resources\EmailAddressResource\Pages;

use App\Filament\Resources\EmailAddressResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailAddress extends EditRecord
{
    protected static string $resource = EmailAddressResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
