<?php

namespace App\Livewire\Tenant\Order;

use App\Livewire\Forms\OrderStatusForm;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public OrderStatusForm $form;

    public $statusFilter = 'all';
    public $search = '';

    protected $queryString = ['statusFilter', 'search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        $this->dispatch('open-order-modal', orderId: $orderId);
    }

    public function updateStatus($orderId, $newStatus)
    {
        $this->form->updateStatus($orderId, $newStatus);
    }

    #[On('order-updated')]
    public function refreshList()
    {
        // Just to refresh the component
    }

    public function render()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        $query = Order::where('restaurant_id', $restaurant->id ?? 0)
            ->with('items');

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        $orders = $query->latest()->paginate(15);

        // Stats for tabs
        $allCount = Order::where('restaurant_id', $restaurant->id ?? 0)->count();
        $pendingCount = Order::where('restaurant_id', $restaurant->id ?? 0)->where('status', 'pending')->count();
        $confirmedCount = Order::where('restaurant_id', $restaurant->id ?? 0)->where('status', 'confirmed')->count();
        $completedCount = Order::where('restaurant_id', $restaurant->id ?? 0)->where('status', 'completed')->count();

        return view('livewire.tenant.order.order-list', [
            'orders' => $orders,
            'allCount' => $allCount,
            'pendingCount' => $pendingCount,
            'confirmedCount' => $confirmedCount,
            'completedCount' => $completedCount,
        ]);
    }
}
