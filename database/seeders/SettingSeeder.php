<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Setting::create([
            'config_key' => 'site_name',
            'config_value' => 'Techvicom',
        ]);

        Setting::create([
            'config_key' => 'site_url',
            'config_value' => 'https://techvicom.com',
        ]);

        Setting::create([
            'config_key' => 'contact_email',
            'config_value' => 'contact@techvicom.com',
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Setting::factory()->count(5)->create();
    }
}
