<div>
    @if($showDetailModal && $selectedOrder)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        Detail Pesanan #{{ $selectedOrder->id }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('showDetailModal', false)"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold d-block">Pelanggan</label>
                                <span class="fw-bold text-dark fs-5">{{ $selectedOrder->customer_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold d-block">Tipe</label>
                                <span class="fw-bold text-dark">{{ $selectedOrder->order_type }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold d-block">Status</label>
                                @if($selectedOrder->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Menunggu</span>
                                @elseif($selectedOrder->status == 'confirmed')
                                    <span class="badge bg-info text-white rounded-pill px-3 py-2">Diproses</span>
                                @elseif($selectedOrder->status == 'completed')
                                    <span class="badge bg-success text-white rounded-pill px-3 py-2">Selesai</span>
                                @elseif($selectedOrder->status == 'cancelled')
                                    <span class="badge bg-danger text-white rounded-pill px-3 py-2">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($selectedOrder->order_info)
                    <div class="alert alert-light border rounded-4 mb-4">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        <strong>Info:</strong> {{ $selectedOrder->order_info }}
                    </div>
                    @endif

                    <!-- Order Items -->
                    <h6 class="fw-bold text-dark mb-3">Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="border-0 rounded-start ps-3 py-3">Produk</th>
                                    <th class="border-0 py-3 text-center">Qty</th>
                                    <th class="border-0 py-3 text-end">Harga</th>
                                    <th class="border-0 rounded-end pe-3 py-3 text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedOrder->items as $item)
                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-bold text-dark">{{ $item->product_name }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}x</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end pe-3 fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold ps-3 pt-3">Total</td>
                                    <td class="text-end fw-bold pe-3 pt-3 text-brand fs-5">Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-muted small mt-2">
                        <i class="bi bi-clock me-1"></i> Dipesan pada {{ $selectedOrder->created_at->format('d M Y, H:i') }}
                        @if($selectedOrder->source)
                        • <i class="bi bi-globe me-1"></i> Sumber: {{ $selectedOrder->source }}
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    @if($selectedOrder->status == 'pending')
                        <button wire:click="updateStatus({{ $selectedOrder->id }}, 'cancelled')" class="btn btn-outline-danger rounded-pill px-4">
                            <i class="bi bi-x-circle me-1"></i> Tolak
                        </button>
                        <button wire:click="updateStatus({{ $selectedOrder->id }}, 'confirmed')" class="btn btn-info text-white rounded-pill px-4">
                            <i class="bi bi-play-fill me-1"></i> Proses Pesanan
                        </button>
                    @elseif($selectedOrder->status == 'confirmed')
                        <button wire:click="updateStatus({{ $selectedOrder->id }}, 'completed')" class="btn btn-success text-white rounded-pill px-4">
                            <i class="bi bi-check2 me-1"></i> Tandai Selesai
                        </button>
                    @endif
                    <button type="button" class="btn btn-light rounded-pill px-4" wire:click="$set('showDetailModal', false)">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
