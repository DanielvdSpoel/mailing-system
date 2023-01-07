<x-filament::widget>
    <x-filament::card>
        <iframe src="{{ route('emails.body', $record->id) }}" sandbox="true" class="w-full h-screen"></iframe>
    </x-filament::card>
</x-filament::widget>
