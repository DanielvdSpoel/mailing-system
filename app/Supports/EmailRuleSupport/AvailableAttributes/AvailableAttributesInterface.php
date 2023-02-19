<?php

namespace App\Supports\EmailRuleSupport\AvailableAttributes;

use App\Models\Email;

interface AvailableAttributesInterface
{
    public static function getValueSchema(): array;

    public static function getAvailableOperations(): array;

    public static function getAttributeValue(Email $email);
}
