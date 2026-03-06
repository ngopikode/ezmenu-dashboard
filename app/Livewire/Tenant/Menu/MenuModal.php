<?php

namespace App\Livewire\Tenant\Menu;

use App\Livewire\Forms\MenuForm;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class MenuModal extends Component
{
    use WithFileUploads;

    public MenuForm $form;

    public $modalType = 'category';
    public $isEditing = false;
    public $categories = [];

    #[On('open-menu-modal')]
    public function openModal($type, $mode, $id = null, $categoryId = null): void
    {
        $this->form->resetForm();
        $this->resetValidation();

        $this->modalType = $type;
        $this->isEditing = ($mode === 'edit');

        if ($type === 'product') {
            $this->categories = Category::where('restaurant_id', Auth::user()->restaurant->id)
                ->orderBy('order_column')
                ->get();
        }

        if ($type === 'category' && $this->isEditing && $id) {
            $category = Category::find($id);
            if ($category) $this->form->setCategory($category);
        } elseif ($type === 'product') {
            if ($this->isEditing && $id) {
                $product = Product::with('options')->find($id);
                if ($product) $this->form->setProduct($product);
            } else {
                $this->form->productCategoryId = $categoryId;
            }
        }

        $this->dispatch('show-bootstrap-modal');
    }

    public function closeModal(): void
    {
        $this->form->resetForm();
        $this->resetValidation();
    }

    public function save(): void
    {
        // 1. Tentukan pesan sukses sebelum modal ditutup
        $message = ($this->modalType === 'category')
            ? 'Kategori berhasil ' . ($this->isEditing ? 'diperbarui' : 'ditambahkan')
            : 'Produk berhasil ' . ($this->isEditing ? 'diperbarui' : 'ditambahkan');

        // 2. Eksekusi Save
        if ($this->modalType === 'category') {
            $this->form->saveCategory($this->isEditing);
        } elseif ($this->modalType === 'product') {
            $this->form->saveProduct($this->isEditing);
        }

        // 3. Dispatch ke Frontend
        $this->dispatch('hide-bootstrap-modal'); // Tutup Modal
        $this->dispatch('menu-saved');           // Refresh Tabel

        // DISPATCH NOTIFIKASI (Ini yang tadi kurang)
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message . ' ☕'
        ]);

        $this->closeModal();
    }

    public function addOption(): void
    {
        $this->form->addOption();
    }

    public function removeOption($index): void
    {
        $this->form->removeOption($index);
    }

    public function render()
    {
        return view('livewire.tenant.menu.menu-modal');
    }
}
