<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class CortexConsoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abilities = [
            ['name' => 'list-routes', 'title' => 'List Routes'],
            ['name' => 'run-terminal', 'title' => 'Run Terminal'],
        ];

        collect($abilities)->each(function (array $ability) {
            app('cortex.auth.ability')->firstOrCreate([
                'name' => $ability['name'],
            ], $ability);
        });
    }
}
