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
        /*factory(\App\DeviceType::class)->create([
            'id' => \App\DeviceType::TERMINAL,
            'name' => 'Terminal'
        ]);
        factory(\App\DeviceType::class)->create([
            'id' => \App\DeviceType::TAP,
            'name' => 'Tap'
        ]);
        factory(\App\Device::class,200)->create();*/
        $faker = Faker\Factory::create();
        for ($i = 1; $i <= 206; $i++){
            $id = DB::table('ea_catv_tap')->select('nrocompred')->limit($i)->get()->last();
            DB::table('ea_catv_tap')
                ->where('nrocompred',$id->nrocompred)
                ->update([
                'lat' => $faker->latitude(-16.462043,-16.525331),
                'lng' => $faker->longitude(-68.100625,-68.167401)
            ]);
        }
    }
}
