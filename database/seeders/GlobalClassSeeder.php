<?php

namespace Database\Seeders;

use App\Models\GlobalClass;
use Illuminate\Database\Seeder;

class GlobalClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['name' => 'Nursery', 'sort_order' => 1],
            ['name' => 'LKG', 'sort_order' => 2],
            ['name' => 'UKG', 'sort_order' => 3],
            ['name' => 'Grade 1', 'sort_order' => 4],
            ['name' => 'Grade 2', 'sort_order' => 5],
            ['name' => 'Grade 3', 'sort_order' => 6],
            ['name' => 'Grade 4', 'sort_order' => 7],
            ['name' => 'Grade 5', 'sort_order' => 8],
            ['name' => 'Grade 6', 'sort_order' => 9],
            ['name' => 'Grade 7', 'sort_order' => 10],
            ['name' => 'Grade 8', 'sort_order' => 11],
            ['name' => 'Grade 9', 'sort_order' => 12],
            ['name' => 'Grade 10', 'sort_order' => 13],
            ['name' => 'Grade 11', 'sort_order' => 14],
            ['name' => 'Grade 12', 'sort_order' => 15],
            ['name' => 'Higher Secondary', 'sort_order' => 16],
        ];

        foreach ($classes as $class) {
            GlobalClass::firstOrCreate(['name' => $class['name']], $class);
        }
    }
}
