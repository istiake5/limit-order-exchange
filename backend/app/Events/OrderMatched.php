<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OrderMatched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $buyerId;
    public $sellerId;
    public $orderData;

    public function __construct($buyerId, $sellerId, $orderData)
    {
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->orderData = $orderData;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->buyerId),
            new PrivateChannel('user.' . $this->sellerId),
        ];
    }

    public function broadcastAs()
    {
        return 'OrderMatched';
    }
}
