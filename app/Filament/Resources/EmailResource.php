<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailResource\Pages;
use App\Filament\Resources\EmailResource\Widgets\EmailContent;
use App\Models\Email;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class EmailResource extends Resource
{
    protected static ?string $model = Email::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableSchema())
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('has_no_body')
                    ->query(fn (Builder $query): Builder => $query->whereNull('text_body')->whereNull('html_body')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmails::route('/'),
            'view' => Pages\ViewEmail::route('/{record}'),
        ];
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('subject')
                ->required(),
            Forms\Components\DateTimePicker::make('received_at')
                ->required(),
            Forms\Components\Select::make('sender_address_id')
                ->label('From')
                ->relationship('senderAddress', 'email'),
            Forms\Components\Select::make('inbox_id')
                ->label('Inbox')
                ->relationship('inbox', 'name')
                ->required(),

        ];
    }

    public static function getTableSchema(): array
    {
        return [
            Tables\Columns\TextColumn::make('subject'),
            Tables\Columns\TextColumn::make('senderAddress.email')->label('From'),
            Tables\Columns\TextColumn::make('inbox.name')->label('Inbox'),
            Tables\Columns\TextColumn::make('received_at')
                ->dateTime(),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            EmailContent::class,
        ];
    }
}
