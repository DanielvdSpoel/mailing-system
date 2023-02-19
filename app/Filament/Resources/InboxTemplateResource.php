<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InboxTemplateResource\Pages;
use App\Filament\Resources\InboxTemplateResource\RelationManagers;
use App\Models\InboxTemplate;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InboxTemplateResource extends Resource
{
    protected static ?string $model = InboxTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-template';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
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
                        Select::make('imap_encryption')
                            ->label('Encryption')
                            ->required()
                            ->options([
                                'false' => 'No encryption',
                                'ssl' => 'SSL',
                                'tls' => 'TLS',
                            ]),

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
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
            RelationManagers\InboxesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInboxTemplates::route('/'),
            'create' => Pages\CreateInboxTemplate::route('/create'),
            'edit' => Pages\EditInboxTemplate::route('/{record}/edit'),
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
