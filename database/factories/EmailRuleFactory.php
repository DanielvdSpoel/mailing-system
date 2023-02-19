<?php

namespace Database\Factories;

use App\Supports\EmailRuleSupport\EmailRuleHandler;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailRuleFactory extends Factory
{
    public function definition(): array
    {
        $field = fake()->randomElement(array_keys(EmailRuleHandler::$availableAttributes));
        $action = fake()->randomElement(array_keys(EmailRuleHandler::$availableActions));

        return [
            'label' => fake()->word(),
            'conditions' => [
                [
                    'field' => $field,
                    'operation' => fake()->randomElement(EmailRuleHandler::getAvailableOperations($field))->name,
                    'value' => fake()->word(),
                    'reversed' => fake()->boolean(),
                ],
            ],
            'actions' => [
                [
                    'type' => $action,
                    'data' => EmailRuleHandler::$availableActions[$action]::getFakeData(),
                ],
            ],
        ];
    }
}
