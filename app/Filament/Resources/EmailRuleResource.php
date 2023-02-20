<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailRuleResource\Pages;
use App\Models\EmailRule;
use App\Supports\EmailRuleSupport\EmailRuleHandler;
use Closure;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use function Filament\Support\get_model_label;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class EmailRuleResource extends Resource
{
    protected static ?string $model = EmailRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-variable';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Repeater::make('conditions')
                    ->columns(3)
                    ->label('If')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('field')
                                    ->options(function () {
                                        $options = [];
                                        foreach (EmailRuleHandler::$availableAttributes as $value) {
                                            $options[array_search($value, EmailRuleHandler::$availableAttributes)] = get_model_label($value);
                                        }

                                        return $options;
                                    })
                                    ->reactive()
                                    ->required(),
                                Select::make('operation')
                                    ->options(function (Closure $get) {
                                        $availableOperations = EmailRuleHandler::getAvailableOperations($get('field'));
                                        $options = [];
                                        foreach (array_column($availableOperations, 'name') as $value) {
                                            $name = str_replace('_', ' ', $value);
                                            $name = ucfirst($name);
                                            $options[$value] = $name;
                                        }

                                        return $options;
                                    })
                                    ->hidden(function (Closure $get) {
                                        return ! EmailRuleHandler::getAvailableOperations($get('field'));
                                    })
                                    ->required(),
                                Grid::make(1)
                                    ->schema(function (Closure $get) {
                                        $field = $get('field');

                                        return EmailRuleHandler::getValueSchema($field);
                                    })->columnSpan(1),
                            ]),
                        Checkbox::make('reversed')
                            ->default(false),
                    ])
                    ->createItemButtonLabel('Add extra condition'),
                Builder::make('actions')
                    ->label('do')
                    ->createItemButtonLabel('Add extra action')
                    ->schema(function () {
                        return EmailRuleHandler::getActionBlocks();
                    })->minItems(1),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('conditions_count')
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return count($record->conditions);
                    }),
                Tables\Columns\TextColumn::make('actions_count')
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return count($record->actions);
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEmailRules::route('/'),
            'create' => Pages\CreateEmailRule::route('/create'),
            'edit' => Pages\EditEmailRule::route('/{record}/edit'),
        ];
    }
}
