<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Label::firstOrCreate([
            'name' => 'spam',
            'color' => 'red',
        ]);
        Label::factory(10)->create();
    }
}
