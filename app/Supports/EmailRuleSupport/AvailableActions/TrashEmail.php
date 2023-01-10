<?php

namespace App\Supports\EmailRuleSupport\AvailableActions;

use App\Models\Email;
use Filament\Forms\Components\Builder\Block;

class TrashEmail implements AvailableActionsInterface
{
    static function executeAction(Email $email, array $settings)
    {
        // TODO: Implement executeAction() method.
    }


    static function getBlock(): Block
    {
        return Block::make('TrashEmail')
            ->icon('heroicon-o-trash')
            ->schema([]);
    }
}
