<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OperationalCostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\OperationalCostCategory::factory(10)->create();
    }
}
