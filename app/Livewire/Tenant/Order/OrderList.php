<?php

namespace App\Livewire\Tenant\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $perPage = 10;

    public function updatedSearch()
    {
        $this->resetPage();
        $this->perPage = 10;
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->perPage = 10;
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function updateStatus($id, $status)
    {
        $order = Order::find($id);
        if ($order) {
            $order->status = $status;
            $order->save();
            $this->dispatch('notify', message: 'Status berhasil diperbarui!');
        }
    }

    public function render()
    {
        $query = Order::query()
            ->when($this->search, function ($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%')
                    ->orWhere('order_code', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            });

        return view('livewire.tenant.order.order-list', [
            'orders' => $query->latest()->paginate($this->perPage),

            // INI 4 VARIABEL YANG WAJIB DIKIRIM KE BLADE
            'allCount' => Order::count(),
            'pendingCount' => Order::where('status', 'pending')->count(),
            'confirmedCount' => Order::where('status', 'confirmed')->count(),
            'completedCount' => Order::where('status', 'completed')->count(),
        ]);
    }
}
