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
            Actions\DeleteAction::make(),
            Actions\Action::make('Test connection')->action('testConnection')
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

    public function testConnection()
    {;
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
            dd($e);
            Notification::make()
                ->title('Connection failed')
                ->danger()
                ->send();
        }
    }
}
