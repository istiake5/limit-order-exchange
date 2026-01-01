<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return Order::where('symbol', $request->symbol)
            ->where('status', Order::OPEN)
            ->orderBy('price')
            ->get();
    }

    public function store(StoreOrderRequest $request, OrderService $service)
    {
        return $service->createOrder(
            $request->validated(),
            $request->user()
        );
    }

    public function cancel(Order $order, OrderService $service)
    {
        return $service->cancelOrder($order, auth()->user());
    }
}
