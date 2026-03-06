<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ThemeToggle extends Component
{
    public $themeMode = 'light';

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->themeMode = $user->theme_mode ?? 'light';
        }
    }

    public function toggleTheme()
    {
        $this->themeMode = $this->themeMode === 'light' ? 'dark' : 'light';

        $user = Auth::user();
        if ($user) {
            $user->theme_mode = $this->themeMode;
            $user->save();
        }

        $this->dispatch('theme-updated', theme: $this->themeMode);
    }

    public function render()
    {
        return view('livewire.theme-toggle');
    }
}
