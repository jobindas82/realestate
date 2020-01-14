<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('App\Tenants');
        $x =0;
        for($i = 1 ; $i <= 100 ; $i++){
        	DB::table('tenants')->insert([
        	'name' => $faker->name,
        	'emirates_id' => '111-'. rand('1111', '9999') .'-'. rand('1111111', '9999999') .'-1',
            'land_phone' => $faker->phoneNumber,
            'mobile' => $faker->phoneNumber,
            'email' => $faker->email,
            'passport_number' => $faker->randomNumber,
        	'created_at' => \Carbon\Carbon::now(),
        	'Updated_at' => \Carbon\Carbon::now(),
        ]);
        }
    }
}
