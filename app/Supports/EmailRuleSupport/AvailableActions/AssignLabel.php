<?php

namespace App\Supports\EmailRuleSupport\AvailableActions;

use App\Models\Email;
use App\Models\Label;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;

class AssignLabel implements AvailableActionsInterface
{

    static function executeAction(Email $email, array $settings)
    {
        // TODO: Implement executeAction() method.
    }

    static function getSettingsSchema(): array
    {
        return [
            Select::make('setting')
                ->options(Label::all()->pluck('name', 'id'))
                ->searchable()
        ];
    }

    static function getBlock(): Block
    {
        return Block::make('AssignLabel')
            ->icon('heroicon-o-tag')
            ->schema([
                Select::make('label')
                    ->options(Label::all()->pluck('name', 'id'))
                    ->searchable()
            ]);
    }
}
