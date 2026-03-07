<?php

namespace App\Livewire\Tenant\Order;

use App\Livewire\Forms\OrderStatusForm;
use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderModal extends Component
{
    public OrderStatusForm $form;

    public $selectedOrder = null;

    #[On('openModal')]
    public function openModal($orderId)
    {
        $this->selectedOrder = Order::with('items')->find($orderId);
        $this->dispatch('show-bootstrap-modal');
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order = $this->form->updateStatus($orderId, $newStatus);

        if ($order) {
            $this->selectedOrder = Order::with('items')->find($orderId);
            $this->dispatch('order-updated');

            // Jika status selesai atau dibatalkan, tutup modal
            if (in_array($newStatus, ['completed', 'cancelled'])) {
                $this->closeModal();
            }
        }
    }

    public function closeModal()
    {
        $this->dispatch('hide-bootstrap-modal');
        $this->selectedOrder = null;
    }

    public function render()
    {
        return view('livewire.tenant.order.order-modal');
    }
}
