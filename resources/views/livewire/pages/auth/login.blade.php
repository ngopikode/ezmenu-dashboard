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

<div class="card card-login-enterprise border-0">
    <div class="card-body p-4 p-sm-5">

        <!-- Header: Consistent with Landing Page -->
        <div class="text-center mb-5">
            <div
                class="d-inline-flex align-items-center justify-content-center bg-dark text-white rounded-3 mb-3 shadow-sm"
                style="width: 48px; height: 48px;">
                <i class="bi bi-layers-fill fs-5"></i>
            </div>
            <h4 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.5px;">EzMenu Portal</h4>
            <p class="text-muted small">
                Sign in to manage <span class="fw-semibold text-dark">NgopiKode Enterprise</span>
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center mb-4 py-2 border-0 rounded-3 small" role="alert"
                 style="background-color: #ecfdf5; color: #047857;">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <form wire:submit="login">

            <!-- Email Input -->
            <div class="form-floating mb-3">
                <input wire:model="form.email"
                       type="email"
                       class="form-control form-control-enterprise @error('form.email') is-invalid @enderror"
                       id="email"
                       placeholder="name@example.com"
                       required autofocus>
                <label for="email" class="text-muted ps-3">Email Address</label>
                @error('form.email')
                <div class="invalid-feedback ps-1 small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Input -->
            <div x-data="{ show: false }" class="mb-4">
                <div class="input-group">
                    <div class="form-floating flex-grow-1">
                        <input wire:model="form.password"
                               :type="show ? 'text' : 'password'"
                               class="form-control form-control-enterprise rounded-end-0 @error('form.password') is-invalid @enderror"
                               id="password"
                               placeholder="Password"
                               style="border-right: none;"
                               required>
                        <label for="password" class="text-muted ps-3">Password</label>
                    </div>
                    <span class="input-group-text bg-white border-start-0 px-3 rounded-end-3"
                          style="border: 1px solid #e2e8f0; border-radius: 12px; cursor: pointer;"
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
                           style="cursor: pointer; border-color: #cbd5e1;">
                    <label for="remember" class="form-check-label text-muted small user-select-none"
                           style="cursor: pointer;">
                        Remember device
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-decoration-none small fw-semibold text-primary"
                       href="{{ route('password.request') }}" wire:navigate>
                        Forgot Password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="d-grid mb-4">
                <button type="submit"
                        class="btn btn-enterprise shadow-sm"
                        wire:loading.attr="disabled"
                        wire:target="login">
                    <span wire:loading.remove wire:target="login">Access Dashboard <i
                            class="bi bi-arrow-right ms-1"></i></span>
                    <span wire:loading wire:target="login">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Authenticating...
                    </span>
                </button>
            </div>

            <!-- Divider -->
            <div class="d-flex align-items-center mb-4">
                <hr class="flex-grow-1 m-0" style="color: #e2e8f0;">
                <span class="px-3 text-muted small" style="font-size: 0.8rem;">or continue with</span>
                <hr class="flex-grow-1 m-0" style="color: #e2e8f0;">
            </div>

            <!-- Social Login -->
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <a href="#"
                       class="btn social-btn w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" width="18"
                             height="18">
                        <span class="small">Google</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="#"
                       class="btn social-btn w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                        <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" alt="Facebook" width="18"
                             height="18">
                        <span class="small">Facebook</span>
                    </a>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-muted small mb-0">
                    New to EzMenu Enterprise?
                    <a href="{{ route('register') }}"
                       class="text-primary fw-semibold text-decoration-none"
                       wire:navigate>
                        Register Outlet
                    </a>
                </p>
            </div>

        </form>
    </div>
</div>
