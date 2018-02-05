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
        Bouncer::allow('admin')->to('list-routes');
        Bouncer::allow('admin')->to('run-terminal');
    }
}
