<?php

namespace App\Filament\Resources\InboxResource\Pages;

use App\Filament\Resources\InboxResource;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Webklex\PHPIMAP\ClientManager;

class EditInbox extends EditRecord
{
    protected static string $resource = InboxResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\Action::make('Test connection')->action('testConnection'),
        ];
    }

    protected function afterFill(): void
    {
        foreach ($this->record->getFolders() as $folder) {
            $found = false;
            foreach ($this->data['folder_to_flags_mapping'] as $mapping) {
                if ($folder == $mapping['folder']) {
                    $found = true;
                }
            }
            if (! $found) {
                $this->data['folder_to_flags_mapping'][] = [
                    'folder' => $folder,
                ];
            }
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['same_credentials']) {
            $data['smtp_username'] = $data['imap_username'];
            $data['smtp_password'] = $data['imap_password'];
        }

        return $data;
    }

    public function testConnection()
    {
        //todo use actually inputted data
        $clientManager = new ClientManager();
        $client = $clientManager->make([
            'host' => $this->record->imap_host,
            'port' => $this->record->imap_port,
            'encryption' => $this->record->imap_encryption,
            'validate_cert' => false,
            'username' => $this->record->imap_username,
            'password' => $this->record->imap_password,
        ]);
        try {
            $client->connect();
            Notification::make()
                ->title('Connection successful')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Connection failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
