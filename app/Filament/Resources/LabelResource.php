<?php

namespace App\Filament\Resources;

use App\Filament\RelationManagers\EmailRelationManager;
use App\Filament\Resources\LabelResource\Pages;
use App\Filament\Resources\LabelResource\RelationManagers;
use App\Models\Label;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class LabelResource extends Resource
{
    protected static ?string $model = Label::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('color')
                    ->allowHtml()
                    ->searchable()
                    ->options([
                        'black' => self::getHTMLString('black', 'bg-black'),
                        'slate' => self::getHTMLString('Slate', 'bg-slate-600'),
                        'gray' => self::getHTMLString('Gray', 'bg-gray-600'),
                        'red' => self::getHTMLString('Red', 'bg-red-600'),
                        'orange' => self::getHTMLString('Orange', 'bg-orange-600'),
                        'amber' => self::getHTMLString('Amber', 'bg-amber-600'),
                        'yellow' => self::getHTMLString('Yellow', 'bg-yellow-600'),
                        'lime' => self::getHTMLString('Lime', 'bg-lime-600'),
                        'green' => self::getHTMLString('Green', 'bg-green-600'),
                        'emerald' => self::getHTMLString('Emerald', 'bg-emerald-600'),
                        'teal' => self::getHTMLString('Teal', 'bg-teal-600'),
                        'cyan' => self::getHTMLString('Cyan', 'bg-cyan-600'),
                        'sky' => self::getHTMLString('Sky', 'bg-sky-600'),
                        'blue' => self::getHTMLString('Blue', 'bg-blue-600'),
                        'indigo' => self::getHTMLString('Indigo', 'bg-indigo-600'),
                        'violet' => self::getHTMLString('Violet', 'bg-violet-600'),
                        'purple' => self::getHTMLString('Purple', 'bg-purple-600'),
                        'fuchsia' => self::getHTMLString('Fuchsia', 'bg-fuchsia-600'),
                        'pink' => self::getHTMLString('Pink', 'bg-pink-600'),
                        'rose' => self::getHTMLString('Rose', 'bg-rose-600'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

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
            EmailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLabels::route('/'),
            'create' => Pages\CreateLabel::route('/create'),
            'edit' => Pages\EditLabel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    private static function getHTMLString($name, $class): string
    {
        return '<div class="flex space-x-2"><div class="' . $class . ' w-6 h-6 rounded-full"></div><p> ' . $name . '</p></div>';
    }
}
