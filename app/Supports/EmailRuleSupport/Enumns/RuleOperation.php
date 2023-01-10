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
        return match ($this->name) {
            'Contains' => str_contains($a, $b),
            'Equals' => $a == $b,
            'Starts_with' => str_starts_with($a, $b),
            'Ends_with' => str_ends_with($a, $b),
            'Empty' => empty($a),
            default => false,
        };

    }

}
