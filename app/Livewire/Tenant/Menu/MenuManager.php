<?php

namespace App\Livewire\Tenant\Menu;

use Livewire\Attributes\Layout;
use Livewire\Component;

class MenuManager extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.tenant.menu.menu-manager');
    }
}
