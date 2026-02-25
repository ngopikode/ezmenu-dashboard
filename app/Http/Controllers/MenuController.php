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
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;

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

    // Menampilkan halaman preview story (HTML)
    public function shareAsStory(Request $request, $subdomain, $productId)
    {
        $restaurant = $request->restaurant;
        $orderColumn = explode('-', $productId)[1] ?? null;
        $product = Product::where('restaurant_id', $restaurant->id)
            ->where('order_column', $orderColumn)
            ->firstOrFail();

        $reactAppBaseUrl = config('app.frontend_url_base');
        $protocol = $request->isSecure() ? 'https' : 'http';
        $fullReactUrl = "$protocol://$subdomain.$reactAppBaseUrl";
        $productUrl = "$fullReactUrl#$productId";

        $imageUrl = $product->image ? asset(Storage::url($product->image)) : null;

        // --- LOGIKA WORDING DI SINI ---
        $generalHooks = [
            '✨ *Barangkali lagi kepikiran ini...*',
            '🌟 *Salah satu yang paling sering dicari nih,*',
            '📍 *Jangan sampai kelewat yang satu ini ya,*',
            '🍃 *Pilihan pas buat nemenin hari kamu,*',
            '🤍 *Rekomendasi spesial buat kamu,*',
            '💡 *Cek deh, siapa tahu kamu suka,*'
        ];

        $randomHook = $generalHooks[array_rand($generalHooks)];
        $priceFormatted = 'Rp ' . number_format($product->price, 0, ',', '.');

        $shareText = "{$randomHook}\n\n";
        $shareText .= "*{$product->name}* @ *{$restaurant->name}*\n";
        if ($product->description) {
            $shareText .= "_\"{$product->description}\"_\n\n";
        } else {
            $shareText .= "\n";
        }
        $shareText .= "Harganya cuma *{$priceFormatted}* aja lho. ✨\n\n";
        $shareText .= "Cek selengkapnya atau langsung order di sini ya:\n";

        return view('story_preview', [
            'restaurant' => $restaurant,
            'product' => $product,
            'image_url' => $imageUrl,
            'product_url' => $productUrl,
            'share_text' => $shareText, // Kirim teks yang sudah jadi ke view
            'share_title' => "{$product->name} - {$restaurant->name}"
        ]);
    }

    // Men-generate gambar story (JPEG)
    public function generateStoryImage(Request $request, $subdomain, $productId)
    {
        set_time_limit(60);

        $restaurant = $request->restaurant;
        $orderColumn = explode('-', $productId)[1] ?? null;
        $product = Product::where('restaurant_id', $restaurant->id)
            ->where('order_column', $orderColumn)
            ->firstOrFail();

        // --- CACHING STRATEGY ---
        $cacheFileName = "stories/{$restaurant->id}/{$product->id}_{$product->updated_at->timestamp}.jpg";

        // Nama file download yang cantik (slugified)
        $downloadName = Str::slug($product->name) . '-story.jpg';

        if (Storage::disk('public')->exists($cacheFileName)) {
            return response()->download(Storage::disk('public')->path($cacheFileName), $downloadName, [
                'Content-Type' => 'image/jpeg'
            ]);
        }

        // Hapus cache lama
        $oldFiles = Storage::disk('public')->files("stories/{$restaurant->id}");
        foreach ($oldFiles as $file) {
            if (str_contains($file, "{$product->id}_")) {
                Storage::disk('public')->delete($file);
            }
        }

        $productImagePath = Storage::disk('public')->path($product->image);

        if (!file_exists($productImagePath)) {
            abort(404, 'Product image not found');
        }

        // Canvas HD (720x1280)
        $canvasWidth = 720;
        $canvasHeight = 1280;

        $img = Image::create($canvasWidth, $canvasHeight)->fill('#ffffff');

        // --- BACKGROUND (Fake Blur) ---
        $backgroundImage = Image::read($productImagePath);
        $backgroundImage->cover(36, 64);
        $backgroundImage->resize($canvasWidth, $canvasHeight);

        $overlay = Image::create($canvasWidth, $canvasHeight)->fill('rgba(0, 0, 0, 0.35)');
        $backgroundImage->place($overlay);

        $img->place($backgroundImage);

        // --- MAIN IMAGE ---
        $mainImage = Image::read($productImagePath);
        $mainImage->scale(width: 550);
        $img->place($mainImage, 'center');

        // --- FONT HANDLING ---
        $getFont = function ($fontName) {
            $path = public_path("fonts/{$fontName}");
            return file_exists($path) ? $path : null;
        };

        $fontBold = $getFont('Poppins-Bold.ttf');
        $fontRegular = $getFont('Poppins-Regular.ttf');
        $fontLight = $getFont('Poppins-Light.ttf');

        // --- TEXT ---

        // Nama Produk
        if ($fontBold) {
            $img->text($product->name, $canvasWidth / 2, 900, function (FontFactory $font) use ($fontBold) {
                $font->filename($fontBold);
                $font->size(50);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('bottom');
            });
        }

        // Harga
        if ($fontRegular) {
            $img->text('Rp ' . number_format($product->price, 0, ',', '.'), $canvasWidth / 2, 980, function (FontFactory $font) use ($fontRegular) {
                $font->filename($fontRegular);
                $font->size(40);
                $font->color('#ffdd00');
                $font->align('center');
                $font->valign('bottom');
            });
        }

        // Call to Action (Tanpa QR)
        if ($fontRegular) {
            $img->text('Cek menu lengkap di link bio!', $canvasWidth / 2, 1150, function (FontFactory $font) use ($fontRegular) {
                $font->filename($fontRegular);
                $font->size(24);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('top');
            });
        }

        // Nama Restoran
        if ($fontLight) {
            $img->text($restaurant->name, $canvasWidth / 2, 1230, function (FontFactory $font) use ($fontLight) {
                $font->filename($fontLight);
                $font->size(24);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('bottom');
            });
        }

        // Simpan ke Cache
        if (!Storage::disk('public')->exists("stories/{$restaurant->id}")) {
            Storage::disk('public')->makeDirectory("stories/{$restaurant->id}");
        }

        $img->save(Storage::disk('public')->path($cacheFileName), 80);

        return response()->download(Storage::disk('public')->path($cacheFileName), $downloadName, [
            'Content-Type' => 'image/jpeg'
        ]);
    }
}
