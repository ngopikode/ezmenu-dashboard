<?php

namespace App\Livewire\Tenant\Profile;

use App\Livewire\Forms\ProfileForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class UserProfile extends Component
{
    public ProfileForm $form;

    public $showSuccessMessage = false;

    public function mount()
    {
        $this->form->setUser(Auth::user());
    }

    public function updateProfileInformation()
    {
        $this->form->updateProfile();
        $this->showSuccessMessage = true;
    }

    public function updatePassword()
    {
        $this->form->updatePassword();
        $this->showSuccessMessage = true;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.tenant.profile.user-profile');
    }
}
