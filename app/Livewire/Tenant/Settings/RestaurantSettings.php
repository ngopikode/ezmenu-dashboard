<?php

namespace App\Livewire\Tenant\Settings;

use App\Livewire\Forms\RestaurantForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class RestaurantSettings extends Component
{
    use WithFileUploads;

    public RestaurantForm $form;

    public $activeTab = 'general';
    public $hasRestaurant = false;
    public $saved = false;

    public function mount()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        if ($restaurant) {
            $this->hasRestaurant = true;
            $this->form->setRestaurant($restaurant);
        }
    }

    public function save()
    {
        $this->form->save();
        $this->hasRestaurant = true;
        $this->saved = true;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.tenant.settings.restaurant-settings');
    }
}
