<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Attribute::create([
            'name' => 'Color',
        ]);

        // Thêm các bản ghi thực tế khác nếu cần
        Attribute::create([
            'name' => 'Size',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Attribute::factory()->count(5)->create();
    }
}
