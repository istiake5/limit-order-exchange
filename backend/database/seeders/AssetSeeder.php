<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Asset::truncate();

        $users = User::all();

        foreach ($users as $user) {
            Asset::create([
                'user_id' => $user->id,
                'symbol' => 'BTC',
                'amount' => rand(1, 5),
                'locked_amount' => 0,
            ]);

            Asset::create([
                'user_id' => $user->id,
                'symbol' => 'ETH',
                'amount' => rand(5, 20),
                'locked_amount' => 0,
            ]);
        }
    }
}
