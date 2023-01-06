<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\EmailRelationManager;
use App\Filament\Resources\InboxResource\Pages;
use App\Filament\Resources\InboxResource\RelationManagers;
use App\Models\Inbox;
use Closure;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class InboxResource extends Resource
{
    protected static ?string $model = Inbox::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                Fieldset::make('IMAP settings')
                    ->schema([
                        TextInput::make('imap_host')
                            ->maxLength(255)
                            ->label('Host')
                            ->required(),
                        TextInput::make('imap_port')
                            ->maxLength(255)
                            ->label('Port')
                            ->minValue(1)
                            ->required()
                            ->numeric(),
                        TextInput::make('imap_username')
                            ->disableAutocomplete()
                            ->maxLength(255)
                            ->label('Username')
                            ->required(),
                        TextInput::make('imap_password')
                            ->disableAutocomplete()
                            ->maxLength(255)
                            ->label('Password')
                            ->password()
                            ->required(),
                        Select::make('imap_encryption')
                            ->label('Encryption')
                            ->required()
                            ->options([
                                'false' => 'No encryption',
                                'ssl' => 'SSL',
                                'tls' => 'TLS',
                            ]),
                        Toggle::make('same_credentials')
                            ->label('Use the same username and password for SMTP')
                            ->inline(false)
                            ->reactive(),

                    ]),
                Fieldset::make('SMTP settings')
                    ->schema([
                        TextInput::make('smtp_host')
                            ->maxLength(255)
                            ->label('Host')
                            ->required(),
                        TextInput::make('smtp_port')
                            ->maxLength(255)
                            ->label('Port')
                            ->minValue(1)
                            ->required()
                            ->numeric(),
                        TextInput::make('smtp_username')
                            ->hidden(fn(Closure $get) => $get('same_credentials') !== false)
                            ->label('Username')
                            ->disableAutocomplete()
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('smtp_password')
                            ->hidden(fn(Closure $get) => $get('same_credentials') !== false)
                            ->disableAutocomplete()
                            ->label('Password')
                            ->maxLength(255)
                            ->password()
                            ->required(),
                        Select::make('smtp_encryption')
                            ->label('Encryption')
                            ->required()
                            ->options([
                                'false' => 'No encryption',
                                'ssl' => 'SSL',
                                'tls' => 'TLS',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EmailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInboxes::route('/'),
            'create' => Pages\CreateInbox::route('/create'),
            'view' => Pages\ViewInbox::route('/{record}'),
            'edit' => Pages\EditInbox::route('/{record}/edit'),
        ];
    }
}
