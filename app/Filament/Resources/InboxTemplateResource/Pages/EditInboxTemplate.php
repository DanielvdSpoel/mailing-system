<?php

namespace App\Filament\Resources\InboxTemplateResource\Pages;

use App\Filament\Resources\InboxTemplateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInboxTemplate extends EditRecord
{
    protected static string $resource = InboxTemplateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->inboxes()->each(function ($inbox) {
            $inbox->imap_host = $this->record->imap_host;
            $inbox->imap_port = $this->record->imap_port;
            $inbox->imap_encryption = $this->record->imap_encryption;

            $inbox->smtp_host = $this->record->smtp_host;
            $inbox->smtp_port = $this->record->smtp_port;
            $inbox->smtp_encryption = $this->record->smtp_encryption;
            $inbox->save();
        });
    }
}
