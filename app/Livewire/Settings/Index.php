<?php

namespace App\Livewire\Settings;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    // General Info
    public $restaurantName;
    public $subdomain;
    public $whatsappNumber;
    public $address;
    public $themeColor;
    public $logo;
    public $existingLogo;
    public $isActive = true;

    // Hero Section
    public $heroPromoText;
    public $heroStatusText;
    public $heroHeadline;
    public $heroTagline;
    public $heroInstagramUrl;

    // Navbar
    public $navbarBrandText;
    public $navbarTitle;
    public $navbarSubtitle;

    // SEO
    public $seoTitle;
    public $seoDescription;
    public $seoKeywords;
    public $ogTitle;
    public $ogDescription;
    public $ogImage;
    public $existingOgImage;

    // State
    public $activeTab = 'general';
    public $hasRestaurant = false;
    public $saved = false;

    protected $rules = [
        'restaurantName' => 'required|string|max:255',
        'subdomain' => 'required|string|max:50|alpha_dash',
        'whatsappNumber' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'themeColor' => 'nullable|string|max:7',
        'logo' => 'nullable|image|max:1024',
        'heroPromoText' => 'nullable|string|max:255',
        'heroStatusText' => 'nullable|string|max:255',
        'heroHeadline' => 'nullable|string|max:255',
        'heroTagline' => 'nullable|string|max:255',
        'heroInstagramUrl' => 'nullable|url|max:255',
        'navbarBrandText' => 'nullable|string|max:255',
        'navbarTitle' => 'nullable|string|max:255',
        'navbarSubtitle' => 'nullable|string|max:255',
        'seoTitle' => 'nullable|string|max:255',
        'seoDescription' => 'nullable|string|max:500',
        'seoKeywords' => 'nullable|string|max:255',
        'ogTitle' => 'nullable|string|max:255',
        'ogDescription' => 'nullable|string|max:500',
        'ogImage' => 'nullable|image|max:1024',
    ];

    public function mount()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        if ($restaurant) {
            $this->hasRestaurant = true;
            $this->restaurantName = $restaurant->name;
            $this->subdomain = $restaurant->subdomain;
            $this->whatsappNumber = $restaurant->whatsapp_number;
            $this->address = $restaurant->address;
            $this->themeColor = $restaurant->theme_color ?? '#F50057';
            $this->existingLogo = $restaurant->logo;
            $this->isActive = $restaurant->is_active;

            // Hero
            $this->heroPromoText = $restaurant->hero_promo_text;
            $this->heroStatusText = $restaurant->hero_status_text;
            $this->heroHeadline = $restaurant->hero_headline;
            $this->heroTagline = $restaurant->hero_tagline;
            $this->heroInstagramUrl = $restaurant->hero_instagram_url;

            // Navbar
            $this->navbarBrandText = $restaurant->navbar_brand_text;
            $this->navbarTitle = $restaurant->navbar_title;
            $this->navbarSubtitle = $restaurant->navbar_subtitle;

            // SEO
            $this->seoTitle = $restaurant->seo_title;
            $this->seoDescription = $restaurant->seo_description;
            $this->seoKeywords = $restaurant->seo_keywords;
            $this->ogTitle = $restaurant->og_title;
            $this->ogDescription = $restaurant->og_description;
            $this->existingOgImage = $restaurant->og_image;
        }
    }

    public function save()
    {
        $this->validate([
            'restaurantName' => 'required|string|max:255',
            'subdomain' => 'required|string|max:50|alpha_dash',
        ]);

        $user = Auth::user();
        $restaurant = $user->restaurant;

        $logoPath = $this->existingLogo;
        if ($this->logo) {
            $logoPath = $this->logo->store('logos', 'public');
        }

        $ogImagePath = $this->existingOgImage;
        if ($this->ogImage) {
            $ogImagePath = $this->ogImage->store('og', 'public');
        }

        $data = [
            'name' => $this->restaurantName,
            'subdomain' => $this->subdomain,
            'whatsapp_number' => $this->whatsappNumber,
            'address' => $this->address,
            'theme_color' => $this->themeColor,
            'logo' => $logoPath,
            'is_active' => $this->isActive,
            // Hero
            'hero_promo_text' => $this->heroPromoText,
            'hero_status_text' => $this->heroStatusText,
            'hero_headline' => $this->heroHeadline,
            'hero_tagline' => $this->heroTagline,
            'hero_instagram_url' => $this->heroInstagramUrl,
            // Navbar
            'navbar_brand_text' => $this->navbarBrandText,
            'navbar_title' => $this->navbarTitle,
            'navbar_subtitle' => $this->navbarSubtitle,
            // SEO
            'seo_title' => $this->seoTitle,
            'seo_description' => $this->seoDescription,
            'seo_keywords' => $this->seoKeywords,
            'og_title' => $this->ogTitle,
            'og_description' => $this->ogDescription,
            'og_image' => $ogImagePath,
        ];

        if ($restaurant) {
            $restaurant->update($data);
        } else {
            $data['user_id'] = $user->id;
            Restaurant::create($data);
            $this->hasRestaurant = true;
        }

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
