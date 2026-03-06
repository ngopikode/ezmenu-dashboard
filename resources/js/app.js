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

    Livewire.hook('commit', ({component, commit, respond, succeed, fail}) => {
        // Hanya munculkan loader jika yang dikerjakan adalah proses simpan atau buka modal besar
        const heavyActions = ['save', 'openModal', 'deleteProduct'];
        const isHeavy = commit.calls.some(call => heavyActions.includes(call.method));

        if (isHeavy) {
            loader.classList.add('d-flex');
            loader.classList.remove('d-none');
        }

        succeed(() => {
            queueMicrotask(() => {
                loader.classList.add('d-none');
                loader.classList.remove('d-flex');
            });
        });
    });
});
