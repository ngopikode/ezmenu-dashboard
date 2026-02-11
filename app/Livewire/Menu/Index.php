<?php

namespace App\Livewire\Menu;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $categories;
    public $activeCategoryId = null; // For accordion or tabs

    // Category Form
    public $showCategoryModal = false;
    public $isEditingCategory = false;
    public $categoryId;
    public $categoryName;

    // Product Form
    public $showProductModal = false;
    public $isEditingProduct = false;
    public $productId;
    public $productName;
    public $productDescription;
    public $productPrice;
    public $productImage;
    public $existingProductImage; // To show existing image
    public $productCategoryId; // Helper for creating product in specific category
    public $productIsAvailable = true;

    // Options Logic (Simplified for now - can be expanded)
    public $productOptions = []; // Array of strings or objects

    protected $rules = [
        'categoryName' => 'required|string|max:255',
        'productName' => 'required|string|max:255',
        'productPrice' => 'required|numeric|min:0',
        'productCategoryId' => 'required|exists:categories,id',
        'productImage' => 'nullable|image|max:1024', // 1MB Max
    ];

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $user = Auth::user();
        $this->categories = Category::where('restaurant_id', $user->restaurant->id)
            ->with(['products' => function ($q) {
                $q->orderBy('order_column');
            }])
            ->orderBy('order_column')
            ->get();

        if (!$this->activeCategoryId && $this->categories->isNotEmpty()) {
            $this->activeCategoryId = $this->categories->first()->id;
        }
    }

    public function render()
    {
        return view('livewire.menu.index');
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
        $this->validate(['categoryName' => 'required|string|max:255']);

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
        $this->showProductModal = true;
    }

    public function openEditProductModal($id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->isEditingProduct = true;
            $this->productId = $id;
            $this->productName = $product->name;
            $this->productDescription = $product->description;
            $this->productPrice = $product->price;
            $this->existingProductImage = $product->image;
            $this->productCategoryId = $product->category_id;
            $this->productIsAvailable = $product->is_available;
            $this->showProductModal = true;
        }
    }

    public function saveProduct()
    {
        $this->validate([
            'productName' => 'required|string|max:255',
            'productPrice' => 'required|numeric|min:0',
            'productCategoryId' => 'required|exists:categories,id',
            'productImage' => 'nullable|image|max:1024',
        ]);

        $user = Auth::user();
        $imagePath = $this->existingProductImage;

        if ($this->productImage) {
            $imagePath = $this->productImage->store('products', 'public');
        }

        $data = [
            'name' => $this->productName,
            'description' => $this->productDescription,
            'price' => $this->productPrice,
            'category_id' => $this->productCategoryId,
            'image' => $imagePath,
            'is_available' => $this->productIsAvailable,
        ];

        if ($this->isEditingProduct) {
            $product = Product::find($this->productId);
            $product->update($data);
        } else {
            $data['restaurant_id'] = $user->restaurant->id;
            // Calculate order
            $data['order_column'] = Product::where('category_id', $this->productCategoryId)->max('order_column') + 1;
            Product::create($data);
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
            $product->delete();
            $this->refreshData();
        }
    }
}
