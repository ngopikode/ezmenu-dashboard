<?php

namespace App\Livewire\Tenant\Order;

use Livewire\Attributes\Layout;
use Livewire\Component;

class OrderManager extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.tenant.order.order-manager');
    }
}
