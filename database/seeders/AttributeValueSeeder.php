<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttributeValue;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        AttributeValue::insert([
            // Giá trị cho Màu sắc (attribute_id = 1)
            [
                'attribute_id' => 1,
                'value' => 'Đỏ',
                'color_code' => '#FF0000',
            ],
            [
                'attribute_id' => 1,
                'value' => 'Xanh dương',
                'color_code' => '#0000FF',
            ],

            // Giá trị cho RAM (attribute_id = 2)
            [
                'attribute_id' => 2,
                'value' => '8GB',
                'color_code' => null,
            ],
            [
                'attribute_id' => 2,
                'value' => '16GB',
                'color_code' => null,
            ],
        ]);
    }
}
