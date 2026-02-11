<?php

namespace App\Livewire\Menu;

use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use App\Traits\CompressesImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads, CompressesImages;

    public $categories;
    public $activeCategoryId = null;

    // Category Form
    public $showCategoryModal = false;
    public $isEditingCategory = false;
    public $categoryId;

    #[Rule('required|string|max:255')]
    public $categoryName;

    // Product Form
    public $showProductModal = false;
    public $isEditingProduct = false;
    public $productId;

    #[Rule('required|string|max:255')]
    public $productName;

    public $productDescription;

    #[Rule('required|numeric|min:0')]
    public $productPrice;

    #[Rule('nullable|image|max:2048')] // Increased max size since we compress
    public $productImage;

    public $existingProductImage;

    #[Rule('required|exists:categories,id')]
    public $productCategoryId;

    public $productIsAvailable = true;

    #[Rule('required|in:single,multi')]
    public $productType = 'single';

    #[Rule(['productOptions.*.name' => 'required|string|max:255'])]
    public $productOptions = [];

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $user = Auth::user();
        $this->categories = Category::where('restaurant_id', $user->restaurant->id)
            ->with(['products' => function ($q) {
                $q->with('options')->orderBy('order_column');
            }])
            ->orderBy('order_column')
            ->get();

        if (!$this->activeCategoryId && $this->categories->isNotEmpty()) {
            $this->activeCategoryId = $this->categories->first()->id;
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.menu.index');
    }

    // --- Options Management ---
    public function addOption()
    {
        $this->productOptions[] = ['name' => ''];
    }

    public function removeOption($index)
    {
        unset($this->productOptions[$index]);
        $this->productOptions = array_values($this->productOptions);
    }


    // --- Category Management ---

    public function openCreateCategoryModal()
    {
        $this->isEditingCategory = false;
        $this->categoryName = '';
        $this->categoryId = null;
        $this->showCategoryModal = true;
    }

    public function openEditCategoryModal($id)
    {
        $category = Category::find($id);
        if ($category) {
            $this->isEditingCategory = true;
            $this->categoryId = $id;
            $this->categoryName = $category->name;
            $this->showCategoryModal = true;
        }
    }

    public function saveCategory()
    {
        $this->validateOnly('categoryName');

        $user = Auth::user();

        if ($this->isEditingCategory) {
            $category = Category::find($this->categoryId);
            $category->update(['name' => $this->categoryName]);
        } else {
            $user->restaurant->categories()->create([
                'name' => $this->categoryName,
                'order_column' => Category::where('restaurant_id', $user->restaurant->id)->max('order_column') + 1
            ]);
        }

        $this->showCategoryModal = false;
        $this->refreshData();
        $this->dispatch('close-modal', 'categoryModal');
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category && $category->products()->count() == 0) {
            $category->delete();
            $this->refreshData();
        } else {
            // Optional: Flash error message "Cannot delete category with products"
        }
    }

    // --- Product Management ---

    public function openCreateProductModal($categoryId)
    {
        $this->isEditingProduct = false;
        $this->productId = null;
        $this->productName = '';
        $this->productDescription = '';
        $this->productPrice = '';
        $this->productImage = null;
        $this->existingProductImage = null;
        $this->productCategoryId = $categoryId;
        $this->productIsAvailable = true;
        $this->productType = 'single';
        $this->productOptions = [];
        $this->showProductModal = true;
    }

    public function openEditProductModal($id)
    {
        $product = Product::with('options')->find($id);
        if ($product) {
            $this->isEditingProduct = true;
            $this->productId = $id;
            $this->productName = $product->name;
            $this->productDescription = $product->description;
            $this->productPrice = $product->price;
            $this->existingProductImage = $product->image;
            $this->productCategoryId = $product->category_id;
            $this->productIsAvailable = $product->is_available;
            $this->productType = $product->type;
            $this->productOptions = $product->options->toArray();
            $this->showProductModal = true;
        }
    }

    public function saveProduct()
    {
        $this->validate();

        $user = Auth::user();
        $imagePath = $this->existingProductImage;

        if ($this->productImage) {
            if ($this->existingProductImage) {
                Storage::disk('public')->delete($this->existingProductImage);
            }
            // Use the trait to compress and store
            $imagePath = $this->compressAndStore($this->productImage, 'products/' . $user->restaurant->id);
        }

        $data = [
            'name' => $this->productName,
            'description' => $this->productDescription,
            'price' => $this->productPrice,
            'category_id' => $this->productCategoryId,
            'image' => $imagePath,
            'is_available' => $this->productIsAvailable,
            'type' => $this->productType,
        ];

        if ($this->isEditingProduct) {
            $product = Product::find($this->productId);
            $product->update($data);
        } else {
            $data['restaurant_id'] = $user->restaurant->id;
            $data['order_column'] = Product::where('category_id', $this->productCategoryId)->max('order_column') + 1;
            $product = Product::create($data);
        }

        // Handle Options
        $product->options()->delete();
        if (!empty($this->productOptions)) {
            foreach ($this->productOptions as $option) {
                $product->options()->create(['name' => $option['name']]);
            }
        }

        $this->showProductModal = false;
        $this->refreshData();
        $this->dispatch('close-modal', 'productModal');
    }

    public function toggleAvailability($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->is_available = !$product->is_available;
            $product->save();
            $this->refreshData();
        }
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            $this->refreshData();
        }
    }
}
