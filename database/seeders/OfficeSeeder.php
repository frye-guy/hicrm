<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        // Deterministic example offices
        Office::updateOrCreate(
            ['name' => 'Indianapolis'],
            ['phone' => '317-555-0100', 'address' => '123 Main St, Indianapolis, IN 46204', 'lat' => 39.7684, 'lng' => -86.1581]
        );

        Office::updateOrCreate(
            ['name' => 'Bloomington'],
            ['phone' => '812-555-0100', 'address' => '42 College Ave, Bloomington, IN 47408', 'lat' => 39.1653, 'lng' => -86.5264]
        );

        // A few random demo offices if you want more
        // Office::factory()->count(3)->create();
    }
}
