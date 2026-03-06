<?php

namespace App\Livewire\Tenant\Order;

use App\Livewire\Forms\OrderStatusForm;
use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderModal extends Component
{
    public OrderStatusForm $form;

    public $showDetailModal = false;
    public $selectedOrder = null;

    #[On('open-order-modal')]
    public function openModal($orderId)
    {
        $this->selectedOrder = Order::with('items.product')->find($orderId);
        $this->showDetailModal = true;
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order = $this->form->updateStatus($orderId, $newStatus);
        if ($order) {
            $this->selectedOrder = Order::with('items.product')->find($orderId);
            $this->dispatch('order-updated');
        }
    }

    public function render()
    {
        return view('livewire.tenant.order.order-modal');
    }
}
