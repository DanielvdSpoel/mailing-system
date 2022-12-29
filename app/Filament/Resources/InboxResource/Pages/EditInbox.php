<?php

namespace App\Filament\Resources\InboxResource\Pages;

use App\Filament\Resources\InboxResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInbox extends EditRecord
{
    protected static string $resource = InboxResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['same_credentials']) {
            $data['smtp_username'] = $data['imap_username'];
            $data['smtp_password'] = $data['imap_password'];
        }
        return $data;
    }
}
