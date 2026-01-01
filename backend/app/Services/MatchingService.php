<?php

namespace App\Services;

use App\Models\User;
use App\Models\Asset;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class MatchingService
{
    const FEE_RATE = 0.015;

    public function match(Order $order)
    {
        DB::transaction(function () use ($order) {

            $counter = Order::where('symbol', $order->symbol)
                ->where('status', Order::OPEN)
                ->where('side', $order->side === 'buy' ? 'sell' : 'buy')
                ->where(function ($q) use ($order) {
                    if ($order->side === 'buy') {
                        $q->where('price', '<=', $order->price);
                    } else {
                        $q->where('price', '>=', $order->price);
                    }
                })
                ->orderBy('created_at')
                ->lockForUpdate()
                ->first();

            if (!$counter) return;

            $this->executeTrade($order, $counter);
        });
    }

    private function executeTrade(Order $order, Order $counter)
    {
        $buy  = $order->side === 'buy' ? $order : $counter;
        $sell = $order->side === 'sell' ? $order : $counter;

        $volume = bcmul($buy->price, $buy->amount, 8);
        $fee    = bcmul($volume, self::FEE_RATE, 8);

        // Buyer receives asset
        Asset::updateOrCreate(
            ['user_id' => $buy->user_id, 'symbol' => $buy->symbol],
            ['amount' => DB::raw("amount + {$buy->amount}")]
        );

        // Seller receives USD minus fee
        User::where('id', $sell->user_id)
            ->increment('balance', bcsub($volume, $fee, 8));

        // Unlock seller asset
        Asset::where('user_id', $sell->user_id)
            ->where('symbol', $sell->symbol)
            ->decrement('locked_amount', $sell->amount);

        $buy->update(['status' => Order::FILLED]);
        $sell->update(['status' => Order::FILLED]);
    }
}
