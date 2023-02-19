<?php

namespace App\Supports\EmailRuleSupport\Enumns;

enum RuleOperation
{
    case Contains;
    case Equals;
    case Starts_with;
    case Ends_with;
    case Empty;

    public function execute(mixed $a, mixed $b): bool
    {
        $a = strtolower($a);
        $b = strtolower($b);

        return match ($this->name) {
            'Contains' => str_contains($a, $b),
            'Equals' => $a == $b,
            'Starts_with' => str_starts_with($a, $b),
            'Ends_with' => str_ends_with($a, $b),
            'Empty' => empty($a),
            default => false,
        };
    }

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum ".self::class);
    }

    public static function tryFromName(string $name): self|null
    {
        try {
            return self::fromName($name);
        } catch (\ValueError $error) {
            return null;
        }
    }
}
