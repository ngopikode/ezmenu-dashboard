<?php

namespace App\Livewire\Tenant\Dashboard;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DashboardIndex extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        $stats = [
            'orders_today' => 0,
            'revenue_today' => 0,
            'pending_orders' => 0,
            'active_products' => 0,
        ];
        $recentOrders = [];

        if ($restaurant) {
            $stats['orders_today'] = Order::where('restaurant_id', $restaurant->id)
                ->whereDate('created_at', today())
                ->count();

            $stats['revenue_today'] = Order::where('restaurant_id', $restaurant->id)
                ->whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('total_price');

            $stats['pending_orders'] = Order::where('restaurant_id', $restaurant->id)
                ->where('status', 'pending')
                ->count();

            $stats['active_products'] = Product::where('restaurant_id', $restaurant->id)
                ->where('is_available', true)
                ->count();

            $recentOrders = Order::where('restaurant_id', $restaurant->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('livewire.tenant.dashboard.dashboard-index', [
            'user' => $user,
            'restaurant' => $restaurant,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
        ]);
    }
}
