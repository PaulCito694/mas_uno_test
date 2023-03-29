<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Celulares', 'slug' => 'celulares'],
            ['name' => 'Laptops', 'slug' => 'laptops'],
            ['name' => 'Mochilas', 'slug' => 'mochilas'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
