<?php

namespace App\Supports\EmailRuleSupport\AvailableAttributes;

use App\Models\Email;

interface AvailableAttributesInterface
{
    static function getValueSchema(): array;

    static function getAvailableOperations(): array;

    static function getAttributeValue(Email $email);
}
