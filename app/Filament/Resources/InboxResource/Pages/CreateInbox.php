<?php

namespace App\Filament\Resources\InboxResource\Pages;

use App\Filament\Resources\InboxResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInbox extends CreateRecord
{
    protected static string $resource = InboxResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['same_credentials']) {
            $data['smtp_username'] = $data['imap_username'];
            $data['smtp_password'] = $data['imap_password'];
        }

        return $data;
    }
}
