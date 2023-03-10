<?php

namespace App\Supports\EmailRuleSupport\AvailableAttributes;

use App\Models\Email;
use App\Supports\EmailRuleSupport\Enumns\RuleOperation;
use Filament\Forms\Components\TextInput;

class EmailSubject implements AvailableAttributesInterface
{
    public static function getValueSchema(): array
    {
        return [
            TextInput::make('value')
                ->required()
                ->maxLength(255),
        ];
    }

    public static function getAvailableOperations(): array
    {
        return [
            RuleOperation::Contains,
            RuleOperation::Equals,
            RuleOperation::Starts_with,
            RuleOperation::Ends_with,
        ];
    }

    public static function getAttributeValue(Email $email)
    {
        return $email->subject;
    }
}
