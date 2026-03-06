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

    // Properti untuk menyimpan produk dari kategori yang sedang aktif
    public $loadedProducts = [];

    #[On('menu-saved')]
    #[On('menu-updated')]
    public function refreshTable()
    {
        // 1. Refresh isi produk kalau kategorinya lagi kebuka
        if ($this->activeCategoryId) {
            $this->loadedProducts = Product::where('category_id', $this->activeCategoryId)
                ->orderBy('order_column', 'asc')
                ->get();
        }

        // 2. HAPUS CACHE COMPUTED INI PENTING!
        // Biar query Category::withCount dihitung ulang dan badge otomatis berubah
        unset($this->categories);
    }

    public function switchView($mode)
    {
        $this->viewMode = $mode;
    }

    // Fungsi Trigger saat user klik Accordion
    public function loadProducts($categoryId)
    {
        // Jika kategori yang diklik sama dengan yang sudah terbuka, tutup accordion
        if ($this->activeCategoryId === $categoryId) {
            $this->activeCategoryId = null;
            $this->loadedProducts = []; // Bersihkan memory
            return;
        }

        // Set kategori aktif dan load HANYA produk dari kategori tersebut
        $this->activeCategoryId = $categoryId;
        $this->loadedProducts = Product::where('category_id', $categoryId)
            ->orderBy('order_column', 'asc')
            ->get();
    }

    #[Computed]
    public function categories()
    {
        // HANYA LOAD NAMA KATEGORI SAJA (Tanpa eager load produk)
        // Kita pakai withCount untuk tetap bisa nampilin angka "Berapa Produk" di header
        return Category::withCount('products')
            ->where('restaurant_id', Auth::user()->restaurant->id)
            ->orderBy('order_column', 'asc')
            ->get();
    }

    public function toggleAvailability($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_available' => !$product->is_available]);

        // Refresh data array produk lokal agar tampilan langsung berubah
        $this->loadProducts($this->activeCategoryId);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Status stok diubah ☕']);
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category->products()->count() > 0) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Hapus semua produk dulu!']);
            return;
        }
        $category->delete();
        $this->activeCategoryId = null; // Reset
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Kategori dihapus']);
    }

    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
        $this->loadProducts($this->activeCategoryId); // Refresh daftar produk di layar
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Produk dibuang']);
    }

    public function render()
    {
        return view('livewire.tenant.menu.menu-table');
    }
}
