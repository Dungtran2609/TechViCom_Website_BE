<?php


namespace Database\Seeders;


use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;


class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        Attribute::create([
            'id' => 1,
            'name' => 'Màu sắc',
            'type' => 'color',
            'slug' => Str::slug('Màu sắc'),
        ]);


        Attribute::create([
            'id' => 2,
            'name' => 'RAM',
            'type' => 'text',
            'slug' => Str::slug('RAM'),
        ]);
    }
}


