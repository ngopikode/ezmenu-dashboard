<?php

namespace App\Livewire\Tenant\Menu;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MenuManager extends Component
{
    #[Layout('components.layouts.app')]
    public function render(): View|Application|Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.tenant.menu.menu-manager');
    }
}
