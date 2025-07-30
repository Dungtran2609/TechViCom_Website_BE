<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders   = DB::table('orders')->pluck('id');
        $variants = DB::table('product_variants')->select('id', 'product_id')->get();

        foreach ($orders as $orderId) {
            // Lấy random variant
            $variant = $variants->random();

            DB::table('order_items')->insert([
                'order_id'   => $orderId,
                'variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'quantity'   => rand(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
