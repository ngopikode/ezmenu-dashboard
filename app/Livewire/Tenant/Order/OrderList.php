<?php

namespace App\Livewire\Tenant\Order;

use App\Livewire\Forms\OrderStatusForm;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function updateStatus($orderId, $newStatus)
    {
        $this->form->updateStatus($orderId, $newStatus);
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Status pesanan berhasil diperbarui ☕'
        ]);
    }

    #[On('order-updated')]
    public function refreshList()
    {
        // Just to refresh the component
    }

    public function render()
    {
        $restaurantId = Auth::user()->restaurant->id ?? 0;

        // 1. OPTIMASI: Gunakan withCount, bukan with. Ini menghemat memori drastis!
        $query = Order::where('restaurant_id', $restaurantId)->withCount('items');

        // Filter Status
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Filter Pencarian
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        $orders = $query->latest()->paginate(15);

        // 2. OPTIMASI: Ambil semua statistik dalam 1 kali query database (Jauh lebih cepat!)
        $stats = Order::where('restaurant_id', $restaurantId)
            ->select(
                DB::raw('count(*) as all_count'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count'),
                DB::raw('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed_count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_count')
            )->first();

        return view('livewire.tenant.order.order-list', [
            'orders' => $orders,
            'allCount' => $stats->all_count ?? 0,
            'pendingCount' => $stats->pending_count ?? 0,
            'confirmedCount' => $stats->confirmed_count ?? 0,
            'completedCount' => $stats->completed_count ?? 0,
        ]);
    }
}
