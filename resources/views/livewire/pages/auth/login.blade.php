<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')]
class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<div class="card card-auth bg-white border-0 shadow-sm">
    <div class="card-body p-4 p-sm-5">

        <!-- Header -->
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Log In Portal</h4>
            <p class="text-muted small">
                Manage <span class="fw-semibold text-dark">Restaurant NgopiKode</span>
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center mb-4 py-2 border-0 rounded-3 small" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <form wire:submit="login">

            <!-- Email Input (Floating Label BS5) -->
            <div class="form-floating mb-3">
                <input wire:model="form.email"
                       type="email"
                       class="form-control @error('form.email') is-invalid @enderror"
                       id="email"
                       placeholder="name@example.com"
                       required autofocus>
                <label for="email">Email Address</label>
                @error('form.email')
                <div class="invalid-feedback ps-1 small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Input with Toggle (BS5 Input Group) -->
            <div x-data="{ show: false }" class="mb-4">
                <div class="input-group">
                    <div class="form-floating flex-grow-1">
                        <input wire:model="form.password"
                               :type="show ? 'text' : 'password'"
                               class="form-control rounded-end-0 @error('form.password') is-invalid @enderror"
                               id="password"
                               placeholder="Password"
                               style="border-right: none;"
                               required>
                        <label for="password">Password</label>
                    </div>
                    <span class="input-group-text bg-white border-start-0 px-3"
                          style="cursor: pointer;"
                          @click="show = !show">
                        <i class="bi" :class="show ? 'bi-eye-slash text-primary' : 'bi-eye text-muted'"></i>
                    </span>
                </div>
                @error('form.password')
                <div class="text-danger small mt-1 ps-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input"
                           style="cursor: pointer;">
                    <label for="remember" class="form-check-label text-muted small user-select-none"
                           style="cursor: pointer;">
                        Ingat Saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-decoration-none small fw-semibold text-primary"
                       href="{{ route('password.request') }}" wire:navigate>
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Submit Button (BS5 d-grid) -->
            <div class="d-grid mb-4">
                <button type="submit"
                        class="btn btn-primary-ez rounded-3 shadow-sm"
                        wire:loading.attr="disabled"
                        wire:target="login">
                    <span wire:loading.remove wire:target="login">Log In</span>
                    <span wire:loading wire:target="login">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Loading...
                    </span>
                </button>
            </div>

            <!-- Divider -->
            <div class="d-flex align-items-center mb-4">
                <hr class="flex-grow-1 m-0 text-muted opacity-25">
                <span class="px-3 text-muted small opacity-75">atau masuk dengan</span>
                <hr class="flex-grow-1 m-0 text-muted opacity-25">
            </div>

            <!-- Social Login (Bootstrap 5 Grid & Gap) -->
            <div class="d-grid gap-2 mb-4">
                <!-- Google -->
                <a href="{{ '#' }}"
                   class="btn btn-outline-secondary d-flex align-items-center justify-content-center position-relative py-2">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" width="20" height="20"
                         class="position-absolute start-0 ms-3">
                    <span class="fw-medium">Google</span>
                </a>

                <!-- Facebook -->
                <a href="{{ '#' }}"
                   class="btn btn-outline-secondary d-flex align-items-center justify-content-center position-relative py-2">
                    <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" alt="Facebook" width="20"
                         height="20" class="position-absolute start-0 ms-3">
                    <span class="fw-medium">Facebook</span>
                </a>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-muted small mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none"
                       wire:navigate>
                        Daftar disini
                    </a>
                </p>
            </div>

        </form>
    </div>
</div>
