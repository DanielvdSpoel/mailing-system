<?php

namespace App\Supports\EmailRuleSupport\AvailableActions;

use App\Models\Email;
use Filament\Forms\Components\Builder\Block;

interface AvailableActionsInterface
{
    static function getBlock(): Block;

    static function executeAction(Email $email, array $settings);
}
