<?php

namespace App\Supports\EmailRuleSupport;

use App\Supports\EmailRuleSupport\AvailableActions\AssignLabel;
use App\Supports\EmailRuleSupport\AvailableActions\TrashEmail;
use App\Supports\EmailRuleSupport\AvailableAttributes\EmailHtmlBody;
use App\Supports\EmailRuleSupport\AvailableAttributes\EmailSubject;
use App\Supports\EmailRuleSupport\AvailableAttributes\EmailTextBody;

class EmailRuleHandler
{
    public static array $availableActions = [
        'TrashEmail' => TrashEmail::class,
        'AssignLabel' => AssignLabel::class,
    ];

    public static array $availableAttributes = [
        'EmailSubject' => EmailSubject::class,
        'EmailTextBody' => EmailTextBody::class,
        'EmailHtmlBody' => EmailHtmlBody::class,
    ];

    public static function getAvailableOperations(?string $attribute): array
    {
        if ($attribute) {
            $attribute = self::$availableAttributes[$attribute];

            return $attribute::getAvailableOperations();
        }

        return [];
    }

    public static function getValueSchema(?string $attribute): array
    {
        if ($attribute) {
            $attribute = self::$availableAttributes[$attribute];

            return $attribute::getValueSchema();
        }

        return [];
    }

    public static function getActionSettingSchema(?string $attribute): array
    {
        if ($attribute) {
            $attribute = self::$availableActions[$attribute];

            return $attribute::getSettingsSchema();
        }

        return [];
    }

    public static function getActionBlocks()
    {
        $blocks = [];
        foreach (self::$availableActions as $action) {
            $blocks[] = $action::getBlock();
        }

        return $blocks;
    }
}
