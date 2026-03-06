import './bootstrap';
import * as bootstrap from 'bootstrap';
import NProgress from 'nprogress';
import Swal from 'sweetalert2';

window.bootstrap = bootstrap;
window.Swal = Swal;

NProgress.configure({showSpinner: false});
document.addEventListener('livewire:navigating', () => NProgress.start());
document.addEventListener('livewire:navigated', () => NProgress.done());

document.addEventListener('livewire:initialized', () => {
    Livewire.on('theme-updated', (event) => {
        document.documentElement.setAttribute('data-bs-theme', event.theme);
    });
});

function initDesktopSidebarToggle() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.onclick = function (e) {
            e.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem(
                'sb|sidebar-toggle',
                document.body.classList.contains('sb-sidenav-toggled').toString()
            );
        };
    }
}

document.addEventListener('DOMContentLoaded', initDesktopSidebarToggle);
document.addEventListener('livewire:navigated', initDesktopSidebarToggle);

// Pakai 'livewire:navigated' biar script ini di-rebind tiap kali pindah halaman
document.addEventListener('livewire:navigated', () => {

    // ==========================================
    // 1. KONFIGURASI SWEETALERT2 TOAST
    // ==========================================
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    // Gunakan sintaks Livewire.on yang baru untuk v3
    Livewire.on('notify', (event) => {
        // Di Livewire 3, data event biasanya dibungkus array/object,
        // pastikan aksesnya bener (biasanya event[0])
        const data = Array.isArray(event) ? event[0] : event;
        Toast.fire({
            icon: data.type || 'success',
            title: data.message
        });
    });

    // ==========================================
    // 2. KONFIGURASI GLOBAL LOADER
    // ==========================================
    const loader = document.getElementById('global-loader');
    if (!loader) return; // Guard clause biar gak error kalau element gak ada

    // Gunakan Livewire.hook di dalam navigated agar tetap terdaftar
    Livewire.hook('commit', ({commit, succeed, fail}) => {
        // Tambahkan 'loadProducts' atau method lain yang dirasa berat
        const heavyActions = ['save', 'deleteProduct', 'deleteCategory'];

        const isHeavy = commit.calls.some(call => heavyActions.includes(call.method));

        if (isHeavy) {
            showLoader();
            succeed(() => hideLoader());
            fail(() => hideLoader());
        }
    });

    // Listener manual untuk dispatch modal
    window.addEventListener('openModal', () => showLoader());
    window.addEventListener('show-bootstrap-modal', () => hideLoader());

    function showLoader() {
        if (loader) {
            loader.classList.remove('d-none');
            loader.classList.add('d-flex');
        }
    }

    function hideLoader() {
        if (loader) {
            loader.classList.remove('d-flex');
            loader.classList.add('d-none');
        }
    }
});
