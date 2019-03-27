<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\DeviceType::class)->create([
            'id' => \App\DeviceType::TERMINAL,
            'name' => 'Terminal'
        ]);
        factory(\App\DeviceType::class)->create([
            'id' => \App\DeviceType::TAP,
            'name' => 'Tap'
        ]);
        factory(\App\Device::class,10)->create();
    }
}
