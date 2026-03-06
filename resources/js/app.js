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

document.addEventListener('livewire:initialized', () => {

    // ==========================================
    // 1. KONFIGURASI SWEETALERT2 TOAST
    // ==========================================
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'colored-toast' // Opsional: untuk styling tambahan jika diperlukan
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    // Mendengarkan perintah trigger 'notify' dari backend (Livewire)
    Livewire.on('notify', (event) => {
        Toast.fire({
            icon: event[0].type || 'success',
            title: event[0].message
        });
    });

    // ==========================================
    // 2. KONFIGURASI GLOBAL LOADER
    // ==========================================
    const loader = document.getElementById('global-loader');

    // 1. Dengerin Akses ke Server (Method Call)
    Livewire.hook('commit', ({commit, succeed, fail}) => {
        const heavyActions = ['save', 'deleteProduct', 'deleteCategory'];
        if (commit.calls.some(call => heavyActions.includes(call.method))) {
            showLoader();
            succeed(hideLoader);
            fail(hideLoader);
        }
    });

    // 2. Dengerin Teriakan Dispatch (Manual Dispatch Listener)
    // Pas ada dispatch 'openModal', langsung paksa munculin loader
    window.addEventListener('openModal', () => {
        showLoader();
    });

    // 3. Sembunyiin Loader pas Modal udah muncul (Pake event dari Alpine/Bootstrap)
    window.addEventListener('show-bootstrap-modal', () => {
        hideLoader();
    });

    function showLoader() {
        loader.classList.replace('d-none', 'd-flex');
    }

    function hideLoader() {
        loader.classList.replace('d-flex', 'd-none');
    }
});
