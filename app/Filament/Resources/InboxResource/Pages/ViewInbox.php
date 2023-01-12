<?php

namespace App\Filament\Resources\InboxResource\Pages;

use App\Filament\Resources\InboxResource;
use App\Jobs\ProcessIncomingEmail;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInbox extends ViewRecord
{
    protected static string $resource = InboxResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('Synchronize inbox')->action('synchronize')

        ];
    }

    public function synchronize()
    {
        ProcessIncomingEmail::dispatchSync($this->record);
        Notification::make()
            ->title('Synchronization successful')
            ->success()
            ->send();
    }
}
