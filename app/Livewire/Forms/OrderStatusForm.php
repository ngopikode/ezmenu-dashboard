<?php

namespace App\Livewire\Forms;

use App\Models\Order;
use Livewire\Attributes\Rule;
use Livewire\Form;

class OrderStatusForm extends Form
{
    #[Rule('required|exists:orders,id')]
    public $orderId;

    #[Rule('required|in:pending,confirmed,completed,cancelled')]
    public $status;

    public function updateStatus($orderId, $newStatus)
    {
        $this->orderId = $orderId;
        $this->status = $newStatus;

        $this->validate();

        $order = Order::find($this->orderId);
        if ($order) {
            $order->status = $this->status;
            $order->save();
            return $order;
        }

        return null;
    }
}
