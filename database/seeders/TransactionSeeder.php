<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Chèn dữ liệu thực tế vào
        Transaction::create([
            'order_id' => 1,
            'payment_gateway' => 'PayPal',
            'transaction_code' => 'TXN001',
            'amount' => 100.00,
            'status' => 'success',
            'transaction_type' => 'credit',
            'paid_at' => now(),
        ]);

        Transaction::create([
            'order_id' => 2,
            'payment_gateway' => 'Stripe',
            'transaction_code' => 'TXN002',
            'amount' => 150.00,
            'status' => 'pending',
            'transaction_type' => 'debit',
            'paid_at' => now(),
        ]);

        // Sử dụng factory để tạo 5 bản ghi ngẫu nhiên
        Transaction::factory()->count(5)->create();
    }
}
