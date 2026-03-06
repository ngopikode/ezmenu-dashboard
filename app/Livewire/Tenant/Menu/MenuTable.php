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
        // Pas berhasil simpan/ubah, JANGAN ubah activeCategoryId
        // Cukup tarik ulang data produknya biar list-nya update
        if ($this->activeCategoryId) {
            $this->loadedProducts = Product::where('category_id', $this->activeCategoryId)
                ->orderBy('order_column', 'asc')
                ->get();
        }
        unset($this->categories); // Refresh badge count
    }

    public function switchView($mode)
    {
        $this->viewMode = $mode;
    }

    public function loadProducts($categoryId)
    {
        // Kalau yang diklik adalah kategori yang sudah kebuka, baru kita tutup (toggle)
        if ($this->activeCategoryId == $categoryId) {
            $this->activeCategoryId = null;
            $this->loadedProducts = [];
        } else {
            // Kalau klik kategori baru, buka dan load produknya
            $this->activeCategoryId = $categoryId;
            $this->loadedProducts = Product::where('category_id', $categoryId)
                ->orderBy('order_column', 'asc')
                ->get();
        }
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

        // FIX: Jangan panggil loadProducts() karena itu fungsi Toggle (bikin nutup).
        // Cukup tarik ulang datanya saja supaya UI-nya update.
        if ($this->activeCategoryId) {
            $this->loadedProducts = Product::where('category_id', $this->activeCategoryId)
                ->orderBy('order_column', 'asc')
                ->get();
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Status stok ' . $product->name . ' berhasil diubah ☕'
        ]);
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
