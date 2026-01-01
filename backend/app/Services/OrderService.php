<?php

namespace App\Services;

use App\Events\OrderCancelled;
use App\Services\MatchingService;
use Illuminate\Support\Facades\DB;
use App\Models\{Order, Asset, User};
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function createOrder(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {

            if ($data['side'] === 'buy') {
                $this->lockUsd($user, $data);
            } else {
                $this->lockAsset($user, $data);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'symbol'  => $data['symbol'],
                'side'    => $data['side'],
                'price'   => $data['price'],
                'amount'  => $data['amount'],
                'status'  => Order::OPEN,
            ]);

            app(MatchingService::class)->match($order);

            return $order;
        });
    }

    private function lockUsd(User $user, array $data)
    {
        $cost = bcmul($data['price'], $data['amount'], 8);

        if ($user->balance < $cost) {
            throw ValidationException::withMessages([
                'balance' => 'Insufficient USD balance'
            ]);
        }

        $user->decrement('balance', $cost);
    }

    private function lockAsset(User $user, array $data)
    {
        $asset = Asset::where('user_id', $user->id)
            ->where('symbol', $data['symbol'])
            ->lockForUpdate()
            ->first();

        if (!$asset || $asset->amount < $data['amount']) {
            throw ValidationException::withMessages([
                'asset' => 'Insufficient asset balance'
            ]);
        }

        $asset->decrement('amount', $data['amount']);
        $asset->increment('locked_amount', $data['amount']);
    }

    public function cancelOrder(Order $order, $user)
    {
        DB::transaction(function () use ($order, $user) {
            if ($order->side === 'buy') {
                $user->balance += $order->amount * $order->price;
                $user->save();
            } else {
                $user->assets()
                    ->where('symbol', $order->symbol)
                    ->increment('amount', $order->amount)
                    ->decrement('locked_amount', $order->amount);
            }

            $order->status = 3;
            $order->save();

            // Broadcast cancelled event
            OrderCancelled::dispatch($user->id, $order->id);
        });

        return response()->json([
            'message' => 'Order cancelled successfully',
            'order' => $order->fresh()
        ]);
    }
}
