<?php

namespace App\Supports\EmailRuleSupport\AvailableActions;

use App\Models\Email;
use Filament\Forms\Components\Builder\Block;

class TrashEmail implements AvailableActionsInterface
{
    public static function executeAction(Email $email, array $settings)
    {
        // TODO: Implement executeAction() method.
    }

    public static function getBlock(): Block
    {
        return Block::make('TrashEmail')
            ->icon('heroicon-o-trash')
            ->schema([]);
    }

    public static function getFakeData(): array
    {
        return [];
    }
}
