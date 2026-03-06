<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use App\Models\Product;
use App\Traits\CompressesImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Form;

class MenuForm extends Form
{
    use CompressesImages;

    // Category Form
    public $categoryId;

    #[Rule('required|string|max:255')]
    public $categoryName;

    // Product Form
    public $productId;

    #[Rule('required|string|max:255')]
    public $productName;

    public $productDescription;

    #[Rule('required|numeric|min:0')]
    public $productPrice;

    #[Rule('nullable|image|max:2048')]
    public $productImage;

    public $existingProductImage;

    #[Rule('required|exists:categories,id')]
    public $productCategoryId;

    public $productIsAvailable = true;

    #[Rule('required|in:single,multi')]
    public $productType = 'single';

    public $productOptions = [];

    public function rules()
    {
        return [
            'productOptions.*.name' => 'required|string|max:255',
        ];
    }

    public function setCategory(Category $category)
    {
        $this->categoryId = $category->id;
        $this->categoryName = $category->name;
    }

    public function setProduct(Product $product)
    {
        $this->productId = $product->id;
        $this->productName = $product->name;
        $this->productDescription = $product->description;
        $this->productPrice = $product->price;
        $this->existingProductImage = $product->image;
        $this->productCategoryId = $product->category_id;
        $this->productIsAvailable = $product->is_available;
        $this->productType = $product->type;
        $this->productOptions = $product->options->toArray();
    }

    public function resetForm()
    {
        $this->reset();
        $this->productIsAvailable = true;
        $this->productType = 'single';
        $this->productOptions = [];
    }

    public function saveCategory($isEditing)
    {
        $this->validateOnly('categoryName');

        $user = Auth::user();

        if ($isEditing) {
            $category = Category::find($this->categoryId);
            $category->update(['name' => $this->categoryName]);
        } else {
            $user->restaurant->categories()->create([
                'name' => $this->categoryName,
                'order_column' => Category::where('restaurant_id', $user->restaurant->id)->max('order_column') + 1
            ]);
        }
    }

    public function saveProduct($isEditing)
    {
        $this->validate();

        $user = Auth::user();
        $imagePath = $this->existingProductImage;

        if ($this->productImage) {
            if ($this->existingProductImage) {
                Storage::disk('public')->delete($this->existingProductImage);
            }
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

        if ($isEditing) {
            $product = Product::find($this->productId);
            $product->update($data);
        } else {
            $data['restaurant_id'] = $user->restaurant->id;
            $data['order_column'] = Product::where('category_id', $this->productCategoryId)->max('order_column') + 1;
            $product = Product::create($data);
        }

        $product->options()->delete();
        if (!empty($this->productOptions)) {
            foreach ($this->productOptions as $option) {
                $product->options()->create(['name' => $option['name']]);
            }
        }
    }

    public function addOption()
    {
        $this->productOptions[] = ['name' => ''];
    }

    public function removeOption($index)
    {
        unset($this->productOptions[$index]);
        $this->productOptions = array_values($this->productOptions);
    }
}
