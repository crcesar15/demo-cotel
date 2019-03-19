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
        factory(\App\DeviceType::class, 1)->create([
            'name' => 'Terminal'
        ]);
        factory(\App\DeviceType::class, 1)->create([
            'name' => 'Tab'
        ]);
        factory(\App\Device::class,20)->create();
    }
}
