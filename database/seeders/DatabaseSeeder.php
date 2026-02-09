<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Product;
use App\Models\Option;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Kosongkan tabel untuk menghindari duplikasi saat seeding ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Restaurant::truncate();
        Category::truncate();
        Product::truncate();
        Option::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Buat User (Pemilik Restoran)
        $user = User::create([
            'name' => 'Pemilik Rokus',
            'email' => 'owner@samarotikukus.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman nanti
        ]);

        // 3. Buat Restoran (Tenant)
        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Sama Roti Kukus',
            'subdomain' => 'samarotikukus',
            'logo' => '/assets/img/logo.png', // Sesuaikan path jika perlu
            'theme_color' => '#d4b982',
            'whatsapp_number' => '6282283668001',
            'address' => "Kompleks @allnewtsjcafe, Bangkot Kab Kampar",
            'is_active' => true,
        ]);

        // 4. Buat Kategori untuk restoran ini
        $catRoti = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Roti',
            'order_column' => 1,
        ]);

        $catMinuman = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Minuman',
            'order_column' => 2,
        ]);

        // 5. Data Menu Lengkap
        $menuData = [
            [
                'id' => 1, 'cat' => 'roti', 'name' => 'Rokus Original', 'price' => 7000, 'desc' => 'Pilih 1 varian rasa klasik favoritmu.',
                'type' => 'single', 'options' => ['Cokelat Original', 'Tiramisu', 'Keju', 'Sarikaya', 'Choco Cruncy'],
                'img' => '/assets/img/rokus-ori.png'
            ],
            [
                'id' => 2, 'cat' => 'roti', 'name' => 'Rokus Mix', 'price' => 8000, 'desc' => 'Perpaduan 2 rasa lumer dalam satu gigitan.',
                'type' => 'single', 'options' => ['Cokelat + Keju', 'Cokelat + Oreo', 'Cokelat + Kacang', 'Cokelat + Tiramisu', 'Sarikaya + Keju', 'Tiramisu + Keju', 'Tiramisu + Oreo', 'Choco Cruncy + Oreo'],
                'img' => '/assets/img/rokus-mix.png'
            ],
            [
                'id' => 3, 'cat' => 'roti', 'name' => 'Rokus Combo', 'price' => 10000, 'desc' => 'Eksplorasi rasa dengan maksimal 3 topping.',
                'type' => 'multi', 'options' => ['Cokelat', 'Tiramisu', 'Keju', 'Sarikaya', 'Oreo', 'Kacang', 'Choco Cruncy'],
                'img' => '/assets/img/rokus-combo.png'
            ],
            [
                'id' => 4, 'cat' => 'minuman', 'name' => 'Kopi Susu Aren', 'price' => 11000, 'desc' => 'Signature coffee dengan gula aren asli.',
                'type' => 'drink', 'options' => [], 'img' => '/assets/img/kopi-susu-aren.jpeg'
            ],
            [
                'id' => 5, 'cat' => 'minuman', 'name' => 'Blue Ocean', 'price' => 11000, 'desc' => 'Kesegaran soda biru lemon yang unik.',
                'type' => 'drink', 'options' => [], 'img' => '/assets/img/blue-ocean.jpeg'
            ],
            [
                'id' => 6, 'cat' => 'minuman', 'name' => 'Milky Mango', 'price' => 11000, 'desc' => 'Creamy milk dengan rasa mangga manis.',
                'type' => 'drink', 'options' => [], 'img' => '/assets/img/milky-mango.jpeg'
            ],
            [
                'id' => 7, 'cat' => 'minuman', 'name' => 'Passion Soda', 'price' => 11000, 'desc' => 'Soda markisa yang menyegarkan dahaga.',
                'type' => 'drink', 'options' => [], 'img' => '/assets/img/passion-soda.jpeg'
            ],
            [
                'id' => 8, 'cat' => 'minuman', 'name' => 'Choco Milky', 'price' => 11000, 'desc' => 'Susu cokelat creamy yang nyoklat banget.',
                'type' => 'drink', 'options' => [], 'img' => '/assets/img/choco-milky.jpeg'
            ],
            [
                'id' => 9, 'cat' => 'minuman', 'name' => 'Yakult Mango', 'price' => 11000, 'desc' => 'Perpaduan Yakult dan mangga yang segar.',
                'type' => 'drink', 'options' => [], 'img' => '/assets/img/yakult-mango.jpeg'
            ],
            [
                'id' => 10, 'cat' => 'minuman', 'name' => 'Yakult Peach', 'price' => 11000, 'desc' => 'Kesegaran Yakult dengan aroma peach.',
                'type' => 'drink', 'options' => [], 'img' => '/assets/img/yakult-peach.jpeg'
            ],
            [
                'id' => 11, 'cat' => 'minuman', 'name' => 'Cendol Aren', 'price' => 10000, 'desc' => 'Cita rasa tradisional cendol gula aren.',
                'type' => 'drink', 'options' => [], 'img' => '/assets/img/cendol-aren.png'
            ]
        ];

        // 6. Loop dan masukkan setiap produk beserta opsinya
        foreach ($menuData as $item) {
            $product = Product::create([
                'restaurant_id' => $restaurant->id,
                'category_id' => ($item['cat'] === 'roti') ? $catRoti->id : $catMinuman->id,
                'name' => $item['name'],
                'description' => $item['desc'],
                'price' => $item['price'],
                'image' => $item['img'],
                'type' => $item['type'],
                'is_available' => true,
            ]);

            // Jika ada opsi, masukkan ke tabel options
            if (!empty($item['options'])) {
                foreach ($item['options'] as $optionName) {
                    Option::create([
                        'product_id' => $product->id,
                        'name' => $optionName,
                    ]);
                }
            }
        }
    }
}
