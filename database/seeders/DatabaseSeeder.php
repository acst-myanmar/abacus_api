<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        !User::count() && \App\Models\User::create([
            'name' => 'chitsanthu',
            'email' => 'chitsanthu208@gmail.com',
            'password'=>Hash::make('password')
        ]);
        foreach(config('dummy.categories') as $category)
        Category::create([
            'name'=>$category,
            'slug'=>Str::slug($category)
        ]);

    }
}
