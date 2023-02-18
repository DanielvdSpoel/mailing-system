<?php

namespace Database\Factories;

use App\Supports\EmailRuleSupport\EmailRuleHandler;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmailRuleFactory extends Factory
{
    public function definition(): array
    {
        $field = $this->faker->randomElement(array_keys(EmailRuleHandler::$availableAttributes));
        $action = $this->faker->randomElement(array_keys(EmailRuleHandler::$availableActions));

        return [
            'label' => $this->faker->word(),
            'conditions' => [
                [
                    'field' => $field,
                    'operation' => $this->faker->randomElement(EmailRuleHandler::getAvailableOperations($field))->name,
                    'value' => $this->faker->word(),
                    'reversed' => $this->faker->boolean(),
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
