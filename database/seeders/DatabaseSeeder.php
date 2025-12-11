<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@motorshop.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create categories
        $categories = ['Motor Parts', 'Oils & Lubricants', 'Tires', 'Accessories', 'Tools'];
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
};