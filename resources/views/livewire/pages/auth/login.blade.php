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

<div class="card card-auth border-0">
    <div class="card-body p-4 p-sm-5">

        <!-- Header: Font Serif (Playfair Display) -->
        <div class="text-center mb-4">
            <h3 class="font-serif fw-bold mb-1" style="color: #1e293b;">Selamat Datang</h3>
            <p class="text-muted small">Silakan masuk untuk mengelola undangan.</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center mb-4 border-0 shadow-sm rounded-3" role="alert"
                 style="background-color: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div class="small">{{ session('status') }}</div>
            </div>
        @endif

        <form wire:submit="login">

            <!-- Email Input -->
            <div class="form-floating mb-3">
                <input wire:model="form.email"
                       type="email"
                       class="form-control rounded-4 @error('form.email') is-invalid @enderror"
                       id="email"
                       placeholder="name@example.com"
                       style="background-color: #f8fafc; border: 1px solid #e2e8f0;"
                       required autofocus>
                <label for="email" class="text-muted">
                    <i class="bi bi-envelope me-1"></i> Email Address
                </label>

                @error('form.email')
                <div class="invalid-feedback ps-2 small">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Password Input dengan Toggle (AlpineJS) -->
            <div x-data="{ show: false }" class="mb-4">
                <div class="input-group">
                    <div class="form-floating flex-grow-1 position-relative">
                        <input wire:model="form.password"
                               :type="show ? 'text' : 'password'"
                               class="form-control rounded-4 rounded-end-0 @error('form.password') is-invalid @enderror"
                               id="password"
                               placeholder="Password"
                               style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-right: none;"
                               required>
                        <label for="password" class="text-muted">
                            <i class="bi bi-lock me-1"></i> Password
                        </label>
                    </div>

                    <!-- Tombol Mata menyatu dengan input -->
                    <span class="input-group-text bg-light border-start-0 rounded-4 rounded-start-0 pe-3"
                          style="background-color: #f8fafc !important; border: 1px solid #e2e8f0; cursor: pointer;"
                          @click="show = !show">
                        <i class="bi" :class="show ? 'bi-eye-slash text-brand' : 'bi-eye text-muted'"></i>
                    </span>
                </div>

                @error('form.password')
                <div class="text-danger small mt-1 ps-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input"
                           style="cursor: pointer;">
                    <label for="remember" class="form-check-label text-muted small" style="cursor: pointer;">
                        Ingat Saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-decoration-none small fw-bold text-brand" href="{{ route('password.request') }}"
                       wire:navigate>
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Submit Button (Gradient Pink) -->
            <div class="d-grid">
                <button type="submit"
                        class="btn btn-brand py-3 rounded-pill shadow-sm"
                        wire:loading.attr="disabled"
                        wire:target="login">

                    <span wire:loading.remove wire:target="login">
                        Masuk Sekarang <i class="bi bi-arrow-right ms-2"></i>
                    </span>

                    <span wire:loading wire:target="login">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Memproses...
                    </span>
                </button>
            </div>

            <!-- Register Link -->
            <div class="text-center mt-4">
                <p class="text-muted small mb-0">Belum punya akun?
                    <a href="{{ route('register') }}" class="text-brand fw-bold text-decoration-none" wire:navigate>
                        Daftar Akun
                    </a>
                </p>
            </div>

        </form>
    </div>
</div>
