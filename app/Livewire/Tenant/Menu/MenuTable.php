<?php

namespace App\Livewire\Tenant\Menu;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class MenuTable extends Component
{
    public $activeCategoryId = null;
    public $viewMode = 'grid'; // Default tampilan grid

    #[On('menu-saved')]
    #[On('menu-updated')]
    public function refreshTable()
    {
        // Otomatis men-trigger re-render karena computed property akan dihitung ulang
    }

    public function switchView($mode)
    {
        $this->viewMode = $mode;
    }

    #[Computed]
    public function categories()
    {
        // Eager Loading 'products' untuk performa maksimal (anti-lelet)
        return Category::with(['products' => function ($query) {
            $query->orderBy('order_column', 'asc');
        }])
            ->where('restaurant_id', Auth::user()->restaurant->id)
            ->orderBy('order_column', 'asc')
            ->get();
    }

    public function toggleAvailability($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update([
            'is_available' => !$product->is_available
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Status stok berhasil diubah ☕'
        ]);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category->products()->count() > 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Hapus semua produk di kategori ini dulu bos!'
            ]);
            return;
        }
        $category->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Kategori berhasil dihapus']);
    }

    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Produk berhasil dibuang']);
    }

    public function render()
    {
        return view('livewire.tenant.menu.menu-table');
    }
}
