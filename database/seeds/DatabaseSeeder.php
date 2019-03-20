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
        factory(\App\DeviceType::class, App\DeviceType::TERMINAL)->create([
            'name' => 'Terminal'
        ]);
        factory(\App\DeviceType::class, App\DeviceType::TAB)->create([
            'name' => 'Tab'
        ]);
        factory(\App\Device::class,50)->create();
    }
}
