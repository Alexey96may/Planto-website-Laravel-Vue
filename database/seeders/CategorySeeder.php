<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cat1 = Category::create(['title' => 'Houseplants', 'slug' => 'houseplants']);
        $cat2 = Category::create(['title' => 'Fabaceae', 'slug' => 'fabaceae']);
        $cat3 = Category::create(['title' => 'Crassulaceae', 'slug' => 'crassulaceae']);
        $cat4 = Category::create(['title' => 'Acanthaceae', 'slug' => 'acanthaceae']);
        $cat5 = Category::create(['title' => 'Cactaceae', 'slug' => 'cactaceae']);
        $cat6 = Category::create(['title' => 'Myrtaceae', 'slug' => 'myrtaceae']);
        $cat7 = Category::create(['title' => 'Plantaginaceae', 'slug' => 'plantaginaceae']);
        $cat8 = Category::create(['title' => 'Moraceae', 'slug' => 'moraceae']);
        $cat9 = Category::create(['title' => 'Apocynaceae', 'slug' => 'apocynaceae']);

        Product::whereIn('id', [1, 2])->update(['category_id' => $cat1->id]);
        Product::whereIn('id', [4, 7])->update(['category_id' => $cat7->id]);
        Product::whereIn('id', [6, 3, 8])->update(['category_id' => $cat3->id]);
        
        Product::where('category_id', null)->update(['category_id' => $cat5->id]);
    }
}
