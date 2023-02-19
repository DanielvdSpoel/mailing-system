<?php

namespace App\Filament\Resources\InboxTemplateResource\RelationManagers;

use App\Filament\Resources\InboxResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;

class InboxesRelationManager extends RelationManager
{
    protected static ?string $title = 'This template is used in these inboxes';

    protected static string $relationship = 'inboxes';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn ($record) => InboxResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
            ]);
    }
}
