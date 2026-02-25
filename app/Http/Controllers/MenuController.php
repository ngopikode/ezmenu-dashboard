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
        $restaurant = $request->restaurant;
        $orderColumn = explode('-', $productId)[1] ?? null;
        $product = Product::where('restaurant_id', $restaurant->id)
            ->where('order_column', $orderColumn)
            ->firstOrFail();

        // Path ke gambar produk
        $productImagePath = Storage::disk('public')->path($product->image);

        // Buat canvas utama
        $img = Image::canvas(1080, 1920, '#ffffff');

        // Tambahkan gambar produk sebagai background, di-blur dan digelapkan
        $backgroundImage = Image::make($productImagePath)->fit(1080, 1920, function ($constraint) {
            $constraint->upsize();
        })->blur(50)->brightness(-25);
        $img->insert($backgroundImage, 'center');

        // Tambahkan gambar produk utama di tengah
        $mainImage = Image::make($productImagePath)->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->insert($mainImage, 'center');

        // Tambahkan Nama Produk
        $img->text($product->name, 540, 1350, function ($font) {
            $font->file(public_path('fonts/Poppins-Bold.ttf'));
            $font->size(80);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('bottom');
        });

        // Tambahkan Harga
        $img->text('Rp ' . number_format($product->price, 0, ',', '.'), 540, 1450, function ($font) {
            $font->file(public_path('fonts/Poppins-Regular.ttf'));
            $font->size(60);
            $font->color('#ffdd00');
            $font->align('center');
            $font->valign('bottom');
        });

        // Generate URL Produk
        $reactAppBaseUrl = config('app.frontend_url_base');
        $protocol = $request->isSecure() ? 'https' : 'http';
        $fullReactUrl = "$protocol://$subdomain.$reactAppBaseUrl#$productId";

        // Tambahkan QR Code
        // Pastikan library simple-qrcode terinstall: composer require simplesoftwareio/simple-qrcode
        $qrCode = QrCode::format('png')->size(250)->margin(1)->generate($fullReactUrl);
        $qrImage = Image::make($qrCode);

        // Insert QR Code di bagian bawah
        $img->insert($qrImage, 'bottom-center', 0, 250);

        // Tambahkan Call to Action Text di bawah QR Code
        $img->text('Scan untuk pesan', 540, 1720, function ($font) {
            $font->file(public_path('fonts/Poppins-Regular.ttf'));
            $font->size(30);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('top');
        });

        // Tambahkan Nama Restoran di paling bawah
        $img->text($restaurant->name, 540, 1850, function ($font) {
            $font->file(public_path('fonts/Poppins-Light.ttf'));
            $font->size(40);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('bottom');
        });

        return $img->response('jpg');
    }
}
