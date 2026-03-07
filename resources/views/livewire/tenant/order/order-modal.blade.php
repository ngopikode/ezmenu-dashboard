<div>
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true" wire:ignore.self
         x-data="{ modal: null }"
         x-init="
            modal = new bootstrap.Modal($el);
            $wire.on('show-bootstrap-modal', () => modal.show());
            $wire.on('hide-bootstrap-modal', () => modal.hide());
         "
         x-on:livewire:navigating.window="
            if(modal) modal.dispose();
            document.querySelectorAll('.modal-backdrop').forEach(bg => bg.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
         ">

        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem; overflow: hidden;">

                @if($selectedOrder)
                    <div class="modal-header bg-light bg-opacity-75 border-bottom-0 px-4 pt-4 pb-3">
                        <div class="w-100 d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="fw-bolder text-dark mb-1">Pesanan #{{ $selectedOrder->id }}</h4>
                                <div class="text-muted small fw-medium">
                                    <i class="bi bi-calendar-event me-1"></i> {{ $selectedOrder->created_at->format('d M Y, H:i') }}
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-bag me-1"></i> {{ $selectedOrder->order_type }}
                                </div>
                            </div>
                            <button type="button" class="btn-close shadow-none bg-white rounded-circle p-2 border"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="row g-0 h-100">

                            <div class="col-lg-5 p-4 border-end border-light bg-white">

                                <div class="mb-4">
                                    <span class="text-uppercase small fw-bold text-muted" style="letter-spacing: 1px;">Status Saat Ini</span>
                                    <div class="mt-2">
                                        @if($selectedOrder->status == 'pending')
                                            <div
                                                class="alert alert-warning border-0 bg-warning bg-opacity-10 text-dark d-flex align-items-center mb-0">
                                                <i class="bi bi-hourglass-split fs-4 text-warning me-3"></i>
                                                <div><strong class="d-block">Menunggu Proses</strong><small>Pesanan baru
                                                        masuk dan butuh konfirmasi.</small></div>
                                            </div>
                                        @elseif($selectedOrder->status == 'confirmed')
                                            <div
                                                class="alert alert-info border-0 bg-info bg-opacity-10 text-dark d-flex align-items-center mb-0">
                                                <i class="bi bi-gear-wide-connected fs-4 text-info me-3"></i>
                                                <div><strong class="d-block">Sedang Diproses</strong><small>Pesanan
                                                        sedang disiapkan untuk pelanggan.</small></div>
                                            </div>
                                        @elseif($selectedOrder->status == 'completed')
                                            <div
                                                class="alert alert-success border-0 bg-success bg-opacity-10 text-dark d-flex align-items-center mb-0">
                                                <i class="bi bi-check-circle-fill fs-4 text-success me-3"></i>
                                                <div><strong class="d-block">Pesanan Selesai</strong><small>Transaksi
                                                        telah berhasil diselesaikan.</small></div>
                                            </div>
                                        @elseif($selectedOrder->status == 'cancelled')
                                            <div
                                                class="alert alert-danger border-0 bg-danger bg-opacity-10 text-dark d-flex align-items-center mb-0">
                                                <i class="bi bi-x-octagon-fill fs-4 text-danger me-3"></i>
                                                <div><strong class="d-block">Dibatalkan</strong><small>Pesanan ini telah
                                                        ditolak atau dibatalkan.</small></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <span class="text-uppercase small fw-bold text-muted" style="letter-spacing: 1px;">Pemesan</span>
                                    <div class="d-flex align-items-center mt-2">
                                        <div
                                            class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3"
                                            style="width: 48px; height: 48px;">
                                            <i class="bi bi-person fs-4 text-secondary"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $selectedOrder->customer_name }}</h6>
                                            @if($selectedOrder->source)
                                                <small class="text-muted"><i
                                                        class="bi bi-globe me-1"></i> {{ $selectedOrder->source }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($selectedOrder->order_info)
                                    <div>
                                        <span class="text-uppercase small fw-bold text-muted"
                                              style="letter-spacing: 1px;">Catatan Khusus</span>
                                        <div class="bg-light p-3 rounded-3 mt-2 border border-light">
                                            <p class="mb-0 text-dark fw-medium" style="font-style: italic;">
                                                "{{ $selectedOrder->order_info }}"</p>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="col-lg-7 d-flex flex-column bg-light bg-opacity-25">

                                <div class="p-4 flex-grow-1">
                                    <span class="text-uppercase small fw-bold text-muted mb-3 d-block"
                                          style="letter-spacing: 1px;">Daftar Item</span>

                                    <div class="d-flex flex-column gap-3">
                                        @foreach($selectedOrder->items as $item)
                                            <div
                                                class="d-flex justify-content-between align-items-center bg-white p-3 border rounded-3 shadow-sm">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="badge bg-light text-dark border p-2 rounded-2 fs-6">
                                                        {{ $item->quantity }}x
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-1">{{ $item->product_name }}</h6>
                                                        <span
                                                            class="small text-muted">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                                <div class="fw-bolder text-dark fs-6 text-end">
                                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="p-4 bg-white border-top mt-auto rounded-bottom-end">
                                    <div class="d-flex justify-content-between align-items-end mb-4">
                                        <span class="text-muted fw-bold">Total Pembayaran</span>
                                        <h3 class="fw-bolder text-dark mb-0">
                                            Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</h3>
                                    </div>

                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                        @if($selectedOrder->status == 'pending')
                                            <button wire:click="updateStatus({{ $selectedOrder->id }}, 'cancelled')"
                                                    wire:loading.attr="disabled"
                                                    class="btn btn-light border text-danger py-2 px-4 fw-bold flex-grow-1 rounded-3">
                                                <span wire:loading.remove
                                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'cancelled')">Tolak Pesanan</span>
                                                <span wire:loading
                                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'cancelled')">
                                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                                </span>
                                            </button>

                                            <button wire:click="updateStatus({{ $selectedOrder->id }}, 'confirmed')"
                                                    wire:loading.attr="disabled"
                                                    class="btn btn-dark py-2 px-4 fw-bold flex-grow-1 shadow-sm rounded-3">
                                                <span wire:loading.remove
                                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'confirmed')">Terima & Proses</span>
                                                <span wire:loading
                                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'confirmed')">
                                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                                </span>
                                            </button>

                                        @elseif($selectedOrder->status == 'confirmed')
                                            <button wire:click="updateStatus({{ $selectedOrder->id }}, 'completed')"
                                                    wire:loading.attr="disabled"
                                                    class="btn btn-success py-2 px-4 fw-bold w-100 shadow-sm rounded-3 text-white">
                                                <span wire:loading.remove
                                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'completed')">
                                                    <i class="bi bi-check2-circle me-1"></i> Selesaikan Pesanan
                                                </span>
                                                <span wire:loading
                                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'completed')">
                                                    <span class="spinner-border spinner-border-sm" role="status"></span> Memproses...
                                                </span>
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="btn btn-light border py-2 px-4 fw-bold w-100 rounded-3"
                                                    data-bs-dismiss="modal">
                                                Kembali
                                            </button>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                @else
                    <div class="modal-body py-5 d-flex flex-column justify-content-center align-items-center">
                        <div class="spinner-border text-dark mb-3" role="status"
                             style="width: 3rem; height: 3rem;"></div>
                        <h6 class="text-muted fw-bold">Memuat data pesanan...</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
