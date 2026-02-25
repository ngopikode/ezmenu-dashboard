<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MenuController extends Controller
{
    /**
     * Show a product preview for social media bots.
     *
     * @param Request $request
     * @param string $subdomain
     * @param string $productId
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse|Redirector|\Illuminate\View\View
     */
    public function showProductPreview(Request $request, string $subdomain, string $productId)
    {
        /** @var Restaurant $restaurant */
        $restaurant = $request->restaurant;

        $orderColumn = explode('-', $productId)[1] ?? null;
        $product = Product::where('restaurant_id', $restaurant->id)
            ->where('order_column', $orderColumn)
            ->firstOrFail();

        // Menggunakan frontend_url_base dari config
        $reactAppBaseUrl = config('app.frontend_url_base');

        $protocol = $request->isSecure() ? 'https' : 'http';
        $fullReactUrl = "$protocol://$subdomain.$reactAppBaseUrl";

        $userAgent = strtolower(request()->header('User-Agent'));
        if (
            str_contains($userAgent, 'whatsapp') ||
            str_contains($userAgent, 'facebookexternalhit') ||
            str_contains($userAgent, 'facebot') ||
            str_contains($userAgent, 'twitterbot') ||
            str_contains($userAgent, 'linkedinbot')
        ) {
            $imageUrl = $product->image ? asset(Storage::url($product->image)) : null;

            return view('product_preview', [
                'restaurant' => $restaurant,
                'product' => $product,
                'image_url' => $imageUrl,
                'react_app_url' => $fullReactUrl,
            ]);

        }

        return redirect("$fullReactUrl#$productId");
    }

    public function shareAsStory(Request $request, $subdomain, $productId)
    {
        // Set time limit sedikit lebih panjang untuk jaga-jaga saat generate pertama kali
        set_time_limit(60);

        $restaurant = $request->restaurant;
        $orderColumn = explode('-', $productId)[1] ?? null;
        $product = Product::where('restaurant_id', $restaurant->id)
            ->where('order_column', $orderColumn)
            ->firstOrFail();

        // --- CACHING STRATEGY ---
        // Nama file cache unik berdasarkan ID dan Timestamp update terakhir.
        // Jika produk diedit, timestamp berubah -> nama file baru -> generate ulang.
        $cacheFileName = "stories/{$restaurant->id}/{$product->id}_{$product->updated_at->timestamp}.jpg";

        // Cek apakah file cache sudah ada
        if (Storage::disk('public')->exists($cacheFileName)) {
            // Jika ada, langsung return file tersebut tanpa proses image processing sama sekali!
            return response()->file(Storage::disk('public')->path($cacheFileName), [
                'Content-Type' => 'image/jpeg'
            ]);
        }

        // --- JIKA BELUM ADA DI CACHE, GENERATE GAMBAR ---

        // Hapus cache lama untuk produk ini agar tidak menumpuk sampah file
        $oldFiles = Storage::disk('public')->files("stories/{$restaurant->id}");
        foreach ($oldFiles as $file) {
            if (str_contains($file, "{$product->id}_")) {
                Storage::disk('public')->delete($file);
            }
        }

        // Path ke gambar produk asli
        $productImagePath = Storage::disk('public')->path($product->image);

        // Gunakan resolusi HD (720x1280) bukan Full HD untuk hemat CPU & RAM
        $canvasWidth = 720;
        $canvasHeight = 1280;

        $img = Image::create($canvasWidth, $canvasHeight)->fill('#ffffff');

        // --- BACKGROUND (Fake Blur Technique) ---
        // Resize sangat kecil (36x64 px) lalu resize besar lagi.
        // Ini menciptakan efek blur alami tanpa kalkulasi Gaussian Blur yang berat.
        $backgroundImage = Image::read($productImagePath);
        $backgroundImage->cover(36, 64);
        $backgroundImage->resize($canvasWidth, $canvasHeight);

        // Overlay gelap (hemat CPU dibanding brightness adjustment)
        $overlay = Image::create($canvasWidth, $canvasHeight)->fill('rgba(0, 0, 0, 0.35)');
        $backgroundImage->place($overlay);

        $img->place($backgroundImage);

        // --- MAIN IMAGE ---
        $mainImage = Image::read($productImagePath);
        // Resize ke lebar 550px (cukup tajam untuk layar HP)
        $mainImage->scale(width: 550);
        $img->place($mainImage, 'center');

        // --- TEXT & QR (Koordinat disesuaikan untuk 720x1280) ---

        // Nama Produk
        $img->text($product->name, $canvasWidth / 2, 900, function (FontFactory $font) {
            $font->filename(public_path('fonts/Poppins-Bold.ttf'));
            $font->size(50); // Font size disesuaikan
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('bottom');
        });

        // Harga
        $img->text('Rp ' . number_format($product->price, 0, ',', '.'), $canvasWidth / 2, 980, function (FontFactory $font) {
            $font->filename(public_path('fonts/Poppins-Regular.ttf'));
            $font->size(40);
            $font->color('#ffdd00');
            $font->align('center');
            $font->valign('bottom');
        });

        // QR Code
        $reactAppBaseUrl = config('app.frontend_url_base');
        $protocol = $request->isSecure() ? 'https' : 'http';
        $fullReactUrl = "$protocol://$subdomain.$reactAppBaseUrl#$productId";

        // Generate QR (Ukuran lebih kecil: 180px)
        $qrCode = QrCode::format('png')->size(180)->margin(1)->generate($fullReactUrl);
        $qrImage = Image::read($qrCode);

        // Posisi QR (offset 180 dari bawah)
        $img->place($qrImage, 'bottom-center', 0, 180);

        // Call to Action
        $img->text('Scan untuk pesan', $canvasWidth / 2, 1150, function (FontFactory $font) {
            $font->filename(public_path('fonts/Poppins-Regular.ttf'));
            $font->size(20);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('top');
        });

        // Nama Restoran
        $img->text($restaurant->name, $canvasWidth / 2, 1230, function (FontFactory $font) {
            $font->filename(public_path('fonts/Poppins-Light.ttf'));
            $font->size(24);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('bottom');
        });

        // Simpan ke Cache
        // Pastikan direktori ada
        if (!Storage::disk('public')->exists("stories/{$restaurant->id}")) {
            Storage::disk('public')->makeDirectory("stories/{$restaurant->id}");
        }

        // Save kualitas 80% (cukup bagus, file size kecil)
        $img->save(Storage::disk('public')->path($cacheFileName), 80);

        // Return file yang baru saja dibuat
        return response()->file(Storage::disk('public')->path($cacheFileName), [
            'Content-Type' => 'image/jpeg'
        ]);
    }
}
