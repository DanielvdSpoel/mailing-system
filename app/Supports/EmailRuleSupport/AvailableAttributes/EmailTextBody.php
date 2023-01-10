<?php

namespace App\Supports\EmailRuleSupport\AvailableAttributes;

use App\Models\Email;
use App\Supports\EmailRuleSupport\Enumns\RuleOperation;
use Filament\Forms\Components\TextInput;

class EmailTextBody implements AvailableAttributesInterface
{

    static function getValueSchema(): array
    {
        return [
            TextInput::make('value')
                ->required()
                ->maxLength(255),
        ];
    }

    static function getAvailableOperations(): array
    {
        return [
            RuleOperation::Contains,
            RuleOperation::Equals,
            RuleOperation::Starts_with,
            RuleOperation::Ends_with,
        ];
    }

    static function getAttributeValue(Email $email)
    {
        return $email->text_body;
    }
}
