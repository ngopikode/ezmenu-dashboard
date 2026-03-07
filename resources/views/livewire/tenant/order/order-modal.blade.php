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

        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg">

                @if($selectedOrder)
                    <div class="modal-header border-bottom-0 pt-4 px-4 pb-0">
                        <div>
                            <h4 class="modal-title fw-bolder font-serif text-dark mb-1">
                                Pesanan #{{ $selectedOrder->id }}
                            </h4>
                            <div class="text-muted small">
                                <i class="bi bi-clock me-1"></i> {{ $selectedOrder->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close shadow-none p-2 bg-light rounded-circle"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4 py-4">

                        <div class="row g-3 mb-4">
                            <div class="col-md-5">
                                <div class="bg-light bg-opacity-50 p-3 rounded-4 border h-100">
                                    <label class="text-muted small text-uppercase fw-bold mb-1 d-block"
                                           style="letter-spacing: 0.5px;">Pelanggan</label>
                                    <span class="fw-bold text-dark fs-5">{{ $selectedOrder->customer_name }}</span>
                                    @if($selectedOrder->source)
                                        <div class="mt-1 small text-muted"><i
                                                class="bi bi-globe me-1"></i> {{ $selectedOrder->source }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="bg-light bg-opacity-50 p-3 rounded-4 border h-100">
                                    <label class="text-muted small text-uppercase fw-bold mb-1 d-block"
                                           style="letter-spacing: 0.5px;">Tipe</label>
                                    <span class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <i class="bi bi-bag"></i> {{ $selectedOrder->order_type }}
                                </span>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div
                                    class="bg-light bg-opacity-50 p-3 rounded-4 border h-100 d-flex flex-column justify-content-center align-items-start">
                                    <label class="text-muted small text-uppercase fw-bold mb-2 d-block"
                                           style="letter-spacing: 0.5px;">Status</label>
                                    @if($selectedOrder->status == 'pending')
                                        <span
                                            class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-2 w-100 text-center"><i
                                                class="bi bi-clock me-1"></i> Menunggu</span>
                                    @elseif($selectedOrder->status == 'confirmed')
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-2 w-100 text-center"><i
                                                class="bi bi-arrow-repeat me-1"></i> Diproses</span>
                                    @elseif($selectedOrder->status == 'completed')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 w-100 text-center"><i
                                                class="bi bi-check-circle me-1"></i> Selesai</span>
                                    @elseif($selectedOrder->status == 'cancelled')
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2 w-100 text-center"><i
                                                class="bi bi-x-circle me-1"></i> Dibatalkan</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($selectedOrder->order_info)
                            <div
                                class="alert bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 mb-4 text-dark shadow-sm">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-primary mt-1 me-2 fs-5"></i>
                                    <div>
                                        <strong class="d-block mb-1">Catatan Tambahan:</strong>
                                        {{ $selectedOrder->order_info }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card border border-opacity-50 rounded-4 shadow-sm">
                            <div class="card-header bg-transparent border-bottom px-4 py-3">
                                <h6 class="fw-bold text-dark mb-0">Rincian Pesanan</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="bg-light text-muted small text-uppercase"
                                           style="letter-spacing: 0.5px;">
                                    <tr>
                                        <th class="ps-4 py-3">Produk</th>
                                        <th class="py-3 text-center" style="width: 80px;">Qty</th>
                                        <th class="py-3 text-end">Harga</th>
                                        <th class="pe-4 py-3 text-end">Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($selectedOrder->items as $item)
                                        <tr class="border-bottom border-light">
                                            <td class="ps-4 py-3">
                                                <span class="fw-bold text-dark">{{ $item->product_name }}</span>
                                            </td>
                                            <td class="text-center py-3">
                                                <span class="badge bg-light text-dark border rounded-pill">{{ $item->quantity }}x</span>
                                            </td>
                                            <td class="text-end text-muted py-3">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-end pe-4 fw-bold text-dark py-3">
                                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr class="bg-light bg-opacity-50">
                                        <td colspan="3"
                                            class="text-end fw-bold ps-4 py-3 text-uppercase small text-muted">Total
                                            Pembayaran
                                        </td>
                                        <td class="text-end fw-bolder pe-4 py-3 text-dark fs-5">
                                            Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div
                        class="modal-footer border-top-0 pt-0 px-4 pb-4 bg-white rounded-bottom-4 d-flex justify-content-between align-items-center">

                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold border"
                                data-bs-dismiss="modal">
                            Kembali
                        </button>

                        <div class="d-flex gap-2">
                            @if($selectedOrder->status == 'pending')
                                <button wire:click="updateStatus({{ $selectedOrder->id }}, 'cancelled')"
                                        wire:loading.attr="disabled"
                                        class="btn btn-outline-danger rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                                <span wire:loading.remove
                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'cancelled')">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </span>
                                    <span wire:loading
                                          wire:target="updateStatus({{ $selectedOrder->id }}, 'cancelled')">
                                    <span class="spinner-border spinner-border-sm" role="status"></span> Proses...
                                </span>
                                </button>

                                <button wire:click="updateStatus({{ $selectedOrder->id }}, 'confirmed')"
                                        wire:loading.attr="disabled"
                                        class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                                <span wire:loading.remove
                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'confirmed')">
                                    <i class="bi bi-play-fill"></i> Proses Pesanan
                                </span>
                                    <span wire:loading
                                          wire:target="updateStatus({{ $selectedOrder->id }}, 'confirmed')">
                                    <span class="spinner-border spinner-border-sm" role="status"></span> Menyimpan...
                                </span>
                                </button>

                            @elseif($selectedOrder->status == 'confirmed')
                                <button wire:click="updateStatus({{ $selectedOrder->id }}, 'completed')"
                                        wire:loading.attr="disabled"
                                        class="btn btn-success text-white rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                                <span wire:loading.remove
                                      wire:target="updateStatus({{ $selectedOrder->id }}, 'completed')">
                                    <i class="bi bi-check2-all"></i> Tandai Selesai
                                </span>
                                    <span wire:loading
                                          wire:target="updateStatus({{ $selectedOrder->id }}, 'completed')">
                                    <span class="spinner-border spinner-border-sm" role="status"></span> Menyelesaikan...
                                </span>
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="modal-body text-center py-5">
                        <div class="spinner-border text-brand" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
