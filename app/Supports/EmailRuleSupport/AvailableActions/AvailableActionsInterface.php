<?php

namespace App\Supports\EmailRuleSupport\AvailableActions;

use App\Models\Email;
use Filament\Forms\Components\Builder\Block;

interface AvailableActionsInterface
{
    public static function getBlock(): Block;

    public static function getFakeData(): array;

    public static function executeAction(Email $email, array $settings);
}
