<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttributeValue;

class AttributeValueSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        AttributeValue::create([
            'attribute_id' => 1,  // Color
            'name' => 'Red',
        ]);

        AttributeValue::create([
            'attribute_id' => 1,  // Color
            'name' => 'Blue',
        ]);

        AttributeValue::create([
            'attribute_id' => 2,  // Size
            'name' => 'L',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        AttributeValue::factory()->count(5)->create();
    }
}
