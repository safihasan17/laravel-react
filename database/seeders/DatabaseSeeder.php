<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(30)->create();
        Brand::factory(5)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Category::factory()->createMany([
            ['name' => 'cloths'],
            ['name' => 'watches'],
            ['name' => 'Glasses'],
            ['name' => 'shoes'],
            
        ]);

        Product::factory(30)->create();


        Role::factory()->createMany([
            ['name' => 'Admin'],
            ['name' => 'Sales Person'],
            ['name' => 'Editor'],
            ['name' => 'Vendor'],
        ]);


    }
}
