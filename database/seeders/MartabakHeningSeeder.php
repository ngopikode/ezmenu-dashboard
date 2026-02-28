<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MartabakHeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Buat User Baru (Pemilik Martabak Hening)
        // Kita gunakan email unik agar tidak bentrok dengan user lama
        $user = User::create([
            'name' => 'Owner Martabak Hening',
            'email' => 'owner@martabakhening.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Buat Restoran (Tenant Baru)
        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Martabak Hening',
            'subdomain' => 'martabakhening',
            // Gunakan placeholder path, nanti file gambarnya diupload ke folder ID restoran yang baru terbentuk
            'logo' => 'logos/martabak-hening/logo.png',
            'theme_color' => '#f59e0b', // Warna oranye keemasan khas martabak
            'whatsapp_number' => '6285172441544', // Nomor kamu (format 62)
            'address' => "Jl. Kemenangan No. 88, (Depan Indomaret Point)",
            'is_active' => true,

            // Hero Section
            'hero_promo_text' => 'Promo Spesial',
            'hero_status_text' => 'Buka 17.00 - 23.00',
            'hero_headline' => 'Manis & Gurih',
            'hero_tagline' => 'Nikmati kelembutan di setiap gigitan.',
            'hero_instagram_url' => '#',

            // Navbar
            'navbar_brand_text' => 'Martabak',
            'navbar_title' => 'Hening',
            'navbar_subtitle' => 'Sejak 2020',

            // SEO
            'seo_title' => 'Martabak Hening - Manis & Telur Premium',
            'seo_description' => 'Martabak manis dengan topping melimpah dan martabak telur renyah. Bahan premium, rasa juara.',
            'seo_keywords' => 'Martabak Hening, Martabak Manis, Terang Bulan, Martabak Telur, Kuliner Malam',
            'og_title' => 'Martabak Hening - Tebal & Lembut',
            'og_description' => 'Lapar malam? Martabak Hening solusinya. Order via WhatsApp sekarang!',
            'og_image' => 'og_images/martabak-hening/og-main.jpg',
        ]);

        // 3. Buat Kategori untuk Martabak Hening
        $catManis = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Martabak Manis',
            'order_column' => 1,
        ]);

        $catTelur = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Martabak Telur',
            'order_column' => 2,
        ]);

        $catMinum = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Minuman Segar',
            'order_column' => 3,
        ]);

        // 4. Data Menu Martabak
        $menuData = [
            // --- Kategori Martabak Manis ---
            [
                'cat_id' => $catManis->id,
                'name' => 'Manis Original (Cokelat Kacang)',
                'price' => 25000,
                'desc' => 'Adonan original lembut dengan taburan meises cokelat dan kacang sangrai.',
                'type' => 'single',
                'options' => ['Biasa', 'Pakai Wijen'],
                'img' => 'products/martabak-hening/manis-cokelat-kacang.jpg'
            ],
            [
                'cat_id' => $catManis->id,
                'name' => 'Manis Keju Susu',
                'price' => 28000,
                'desc' => 'Parutan keju melimpah disiram susu kental manis.',
                'type' => 'single',
                'options' => ['Keju Cheddar', 'Keju + Jagung'],
                'img' => 'products/martabak-hening/manis-keju.jpg'
            ],
            [
                'cat_id' => $catManis->id,
                'name' => 'Manis Komplit (Campur)',
                'price' => 32000,
                'desc' => 'Perpaduan sempurna Cokelat, Kacang, Keju, dan Wijen.',
                'type' => 'single',
                'options' => [],
                'img' => 'products/martabak-hening/manis-komplit.jpg'
            ],
            [
                'cat_id' => $catManis->id,
                'name' => 'Manis Premium (Red Velvet/Pandan)',
                'price' => 35000,
                'desc' => 'Pilih base adonan favoritmu dengan topping Cream Cheese Oreo.',
                'type' => 'single',
                'options' => ['Red Velvet Oreo', 'Pandan Keju', 'Blackforest Cokelat'],
                'img' => 'products/martabak-hening/manis-premium.jpg'
            ],

            // --- Kategori Martabak Telur ---
            [
                'cat_id' => $catTelur->id,
                'name' => 'Martabak Telur Ayam',
                'price' => 22000,
                'desc' => 'Martabak telur renyah dengan isian daging ayam cincang dan daun bawang.',
                'type' => 'single',
                'options' => ['Biasa (2 Telur)', 'Spesial (3 Telur)', 'Istimewa (4 Telur)'],
                'img' => 'products/martabak-hening/telur-ayam.jpg'
            ],
            [
                'cat_id' => $catTelur->id,
                'name' => 'Martabak Telur Sapi',
                'price' => 28000,
                'desc' => 'Isian daging sapi giling berbumbu kari yang gurih.',
                'type' => 'single',
                'options' => ['Biasa (2 Telur)', 'Spesial (3 Telur)', 'Super Daging'],
                'img' => 'products/martabak-hening/telur-sapi.jpg'
            ],
            [
                'cat_id' => $catTelur->id,
                'name' => 'Martabak Telur Mozzarella',
                'price' => 35000,
                'desc' => 'Martabak telur sapi dengan topping keju mozzarella leleh di atasnya.',
                'type' => 'single',
                'options' => [],
                'img' => 'products/martabak-hening/telur-mozza.jpg'
            ],

            // --- Kategori Minuman ---
            [
                'cat_id' => $catMinum->id,
                'name' => 'Teh Tarik Hangat',
                'price' => 8000,
                'desc' => 'Teh susu khas yang ditarik hingga berbusa.',
                'type' => 'single',
                'options' => [],
                'img' => 'products/martabak-hening/teh-tarik.jpg'
            ],
            [
                'cat_id' => $catMinum->id,
                'name' => 'Es Jeruk Murni',
                'price' => 10000,
                'desc' => 'Perasan jeruk asli yang menyegarkan.',
                'type' => 'single',
                'options' => [],
                'img' => 'products/martabak-hening/es-jeruk.jpg'
            ],
        ];

        // 5. Loop Insert Produk
        $orderColumn = 1;
        foreach ($menuData as $item) {
            $product = Product::create([
                'restaurant_id' => $restaurant->id,
                'category_id' => $item['cat_id'],
                'name' => $item['name'],
                'description' => $item['desc'],
                'price' => $item['price'],
                'image' => $item['img'],
                'type' => $item['type'],
                'order_column' => $orderColumn++,
                'is_available' => true,
            ]);

            // Insert Opsi jika ada
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
