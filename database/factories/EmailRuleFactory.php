<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmailRuleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'label' => $this->faker->word(),
            'conditions' => "{}",
            'actions' => "{}",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
