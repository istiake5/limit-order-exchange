<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::truncate();

        $users = User::all();

        foreach ($users as $user) {
            // Dummy buy orders
            Order::create([
                'user_id' => $user->id,
                'symbol' => 'BTC',
                'side' => 'buy',
                'price' => rand(20000, 50000),
                'amount' => rand(1, 3),
                'status' => 1, // open
            ]);

            // Dummy sell orders
            Order::create([
                'user_id' => $user->id,
                'symbol' => 'ETH',
                'side' => 'sell',
                'price' => rand(1000, 3000),
                'amount' => rand(2, 5),
                'status' => 1, // open
            ]);
        }
    }
}
