<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\EmailRelationManager;
use App\Filament\Resources\InboxResource\Pages;
use App\Filament\Resources\InboxResource\RelationManagers;
use App\Models\Inbox;
use App\Models\InboxTemplate;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use SebastianBergmann\Template\Template;

class InboxResource extends Resource
{
    protected static ?string $model = Inbox::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('template_id')
                    ->nullable()
                    ->relationship('template', 'name')
                    ->afterStateUpdated(function (Closure $set, $state) {
                        $template = InboxTemplate::find($state);
                        if ($template) {
                            $set('imap_host', $template->imap_host);
                            $set('imap_port', $template->imap_port);
                            $set('imap_encryption', $template->imap_encryption);
                            $set('smtp_host', $template->smtp_host);
                            $set('smtp_port', $template->smtp_port);
                            $set('smtp_encryption', $template->smtp_encryption);
                        }
                    })
                    ->reactive(),
                Fieldset::make('IMAP settings')
                    ->schema([
                        TextInput::make('imap_host')
                            ->maxLength(255)
                            ->disabled(fn(Closure $get) => $get('template_id') !== '')
                            ->label('Host')
                            ->required(),
                        TextInput::make('imap_port')
                            ->disabled(fn(Closure $get) => $get('template_id') !== '')
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
                            ->disabled(fn(Closure $get) => $get('template_id') !== '')
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
                        TableRepeater::make('folder_to_flags_mapping')
                            ->columnWidths([
                                'deleted' => '10%',
                                'seen' => '10%',
                                'draft' => '10%',
                                'send' => '10%',
                                'spam' => '10%',
                            ])
                            ->schema([
                                TextInput::make('folder')
                                    ->disableLabel(),
                                Checkbox::make('deleted')
                                    ->disableLabel(),
                                Checkbox::make('draft')
                                    ->disableLabel(),
                                Checkbox::make('send')
                                    ->disableLabel(),
                                Checkbox::make('spam')
                                    ->disableLabel(),
                            ])
                            ->columnSpan('full')
                            ->disableItemMovement()
                            ->disableItemDeletion()
                            ->disableItemCreation()
                            ->hiddenOn('create')
                    ]),
                Fieldset::make('SMTP settings')
                    ->schema([
                        TextInput::make('smtp_host')
                            ->disabled(fn(Closure $get) => $get('template_id') !== '')
                            ->maxLength(255)
                            ->label('Host')
                            ->required(),
                        TextInput::make('smtp_port')
                            ->disabled(fn(Closure $get) => $get('template_id') !== '')
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
                            ->disabled(fn(Closure $get) => $get('template_id') !== '')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
