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

    public $showModal = false;
    public $modalType = ''; // 'category' or 'product'
    public $isEditing = false;

    // Data for dropdowns
    public $categories = [];

    #[On('open-menu-modal')]
    public function openModal($type, $mode, $id = null, $categoryId = null)
    {
        $this->form->resetForm();
        $this->resetValidation();
        $this->resetExcept('form');

        $this->modalType = $type;
        $this->isEditing = ($mode === 'edit');
        $this->showModal = true;

        if ($type === 'product') {
             $this->categories = Category::where('restaurant_id', Auth::user()->restaurant->id)
                ->orderBy('order_column')
                ->get();
        }

        if ($type === 'category') {
            if ($this->isEditing && $id) {
                $category = Category::find($id);
                if ($category) {
                    $this->form->setCategory($category);
                }
            }
        } elseif ($type === 'product') {
            if ($this->isEditing && $id) {
                $product = Product::with('options')->find($id);
                if ($product) {
                    $this->form->setProduct($product);
                }
            } else {
                $this->form->productCategoryId = $categoryId;
            }
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->form->resetForm();
    }

    public function save()
    {
        if ($this->modalType === 'category') {
            $this->form->saveCategory($this->isEditing);
        } elseif ($this->modalType === 'product') {
            $this->form->saveProduct($this->isEditing);
        }

        $this->closeModal();
        $this->dispatch('menu-updated');
    }

    public function addOption()
    {
        $this->form->addOption();
    }

    public function removeOption($index)
    {
        $this->form->removeOption($index);
    }

    public function render()
    {
        return view('livewire.tenant.menu.menu-modal');
    }
}
