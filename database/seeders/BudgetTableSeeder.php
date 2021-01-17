<?php

namespace Database\Seeders;

use App\Models\Budget;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BudgetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Budget::truncate();

        $faker = Factory::create();

        for($i = 0;$i<10;$i++){
            Budget::create([
                'name'=>$faker->sentence
            ]);
        }
    }
}
