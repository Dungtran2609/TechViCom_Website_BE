<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('news_categories')->insert([
            ['name' => 'Tin tức công nghệ', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sự kiện', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Khuyến mãi', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
