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
        set_time_limit(60);

        $restaurant = $request->restaurant;
        $orderColumn = explode('-', $productId)[1] ?? null;
        $product = Product::where('restaurant_id', $restaurant->id)
            ->where('order_column', $orderColumn)
            ->firstOrFail();

        // --- CACHING STRATEGY ---
        $cacheFileName = "stories/{$restaurant->id}/{$product->id}_{$product->updated_at->timestamp}.jpg";

        if (Storage::disk('public')->exists($cacheFileName)) {
            return response()->file(Storage::disk('public')->path($cacheFileName), [
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
        // Helper function untuk memilih font yang aman
        $getFont = function($fontName) {
            $path = public_path("fonts/{$fontName}");
            return file_exists($path) ? $path : null;
        };

        $fontBold = $getFont('Poppins-Bold.ttf');
        $fontRegular = $getFont('Poppins-Regular.ttf');
        $fontLight = $getFont('Poppins-Light.ttf');

        // --- TEXT & QR ---

        // Nama Produk
        $img->text($product->name, $canvasWidth / 2, 900, function (FontFactory $font) use ($fontBold) {
            if ($fontBold) {
                $font->filename($fontBold);
                $font->size(50);
            } else {
                $font->file(5); // Fallback ke font internal GD terbesar
            }
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('bottom');
        });

        // Harga
        $img->text('Rp ' . number_format($product->price, 0, ',', '.'), $canvasWidth / 2, 980, function (FontFactory $font) use ($fontRegular) {
            if ($fontRegular) {
                $font->filename($fontRegular);
                $font->size(40);
            } else {
                $font->file(4);
            }
            $font->color('#ffdd00');
            $font->align('center');
            $font->valign('bottom');
        });

        // QR Code
        $reactAppBaseUrl = config('app.frontend_url_base');
        $protocol = $request->isSecure() ? 'https' : 'http';
        $fullReactUrl = "$protocol://$subdomain.$reactAppBaseUrl#$productId";

        $qrCode = QrCode::format('png')->size(180)->margin(1)->generate($fullReactUrl);
        $qrImage = Image::read($qrCode);
        $img->place($qrImage, 'bottom-center', 0, 180);

        // Call to Action
        $img->text('Scan untuk pesan', $canvasWidth / 2, 1150, function (FontFactory $font) use ($fontRegular) {
            if ($fontRegular) {
                $font->filename($fontRegular);
                $font->size(20);
            } else {
                $font->file(3);
            }
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('top');
        });

        // Nama Restoran
        $img->text($restaurant->name, $canvasWidth / 2, 1230, function (FontFactory $font) use ($fontLight) {
            if ($fontLight) {
                $font->filename($fontLight);
                $font->size(24);
            } else {
                $font->file(3);
            }
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('bottom');
        });

        // Simpan ke Cache
        if (!Storage::disk('public')->exists("stories/{$restaurant->id}")) {
            Storage::disk('public')->makeDirectory("stories/{$restaurant->id}");
        }

        $img->save(Storage::disk('public')->path($cacheFileName), 80);

        return response()->file(Storage::disk('public')->path($cacheFileName), [
            'Content-Type' => 'image/jpeg'
        ]);
    }
}
