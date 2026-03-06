<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold font-serif text-dark mb-1">Pesanan Masuk</h2>
            <p class="text-muted small mb-0">Kelola dan proses pesanan dari pelanggan.</p>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            <ul class="nav nav-pills gap-2 p-3">
                <li class="nav-item">
                    <button wire:click="filterByStatus('all')" class="nav-link rounded-pill px-4 {{ $statusFilter == 'all' ? 'active bg-dark' : 'text-muted' }}">
                        Semua <span class="badge bg-light text-dark ms-1 rounded-pill">{{ $allCount }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button wire:click="filterByStatus('pending')" class="nav-link rounded-pill px-4 {{ $statusFilter == 'pending' ? 'active bg-warning text-dark' : 'text-muted' }}">
                        <i class="bi bi-clock me-1"></i> Menunggu <span class="badge bg-warning text-dark ms-1 rounded-pill">{{ $pendingCount }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button wire:click="filterByStatus('confirmed')" class="nav-link rounded-pill px-4 {{ $statusFilter == 'confirmed' ? 'active bg-info text-white' : 'text-muted' }}">
                        <i class="bi bi-arrow-repeat me-1"></i> Diproses <span class="badge bg-info text-white ms-1 rounded-pill">{{ $confirmedCount }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button wire:click="filterByStatus('completed')" class="nav-link rounded-pill px-4 {{ $statusFilter == 'completed' ? 'active bg-success text-white' : 'text-muted' }}">
                        <i class="bi bi-check-circle me-1"></i> Selesai <span class="badge bg-success text-white ms-1 rounded-pill">{{ $completedCount }}</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control border-start-0 rounded-end-pill"
                   wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan nama pelanggan atau ID...">
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm rounded-4" wire:poll.15s>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="border-0 ps-4 py-3">ID</th>
                            <th class="border-0 py-3">Pelanggan</th>
                            <th class="border-0 py-3">Item</th>
                            <th class="border-0 py-3">Total</th>
                            <th class="border-0 py-3">Status</th>
                            <th class="border-0 py-3">Waktu</th>
                            <th class="border-0 pe-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $order->customer_name }}</span>
                                    <span class="small text-muted">{{ $order->order_type }} {{ $order->order_info ? '• ' . $order->order_info : '' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark rounded-pill">{{ $order->items->count() }} item</span>
                            </td>
                            <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                        <i class="bi bi-clock me-1"></i> Menunggu
                                    </span>
                                @elseif($order->status == 'confirmed')
                                    <span class="badge bg-info text-white rounded-pill px-3 py-2">
                                        <i class="bi bi-arrow-repeat me-1"></i> Diproses
                                    </span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success text-white rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i> Selesai
                                    </span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger text-white rounded-pill px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i> Dibatalkan
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white rounded-pill px-3 py-2">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $order->created_at->diffForHumans() }}</td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <button wire:click="viewOrder({{ $order->id }})" class="btn btn-sm btn-light rounded-pill border px-3">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </button>
                                    @if($order->status == 'pending')
                                        <button wire:click="updateStatus({{ $order->id }}, 'confirmed')" class="btn btn-sm btn-info text-white rounded-pill px-3 ms-1">
                                            <i class="bi bi-play-fill me-1"></i> Proses
                                        </button>
                                    @elseif($order->status == 'confirmed')
                                        <button wire:click="updateStatus({{ $order->id }}, 'completed')" class="btn btn-sm btn-success text-white rounded-pill px-3 ms-1">
                                            <i class="bi bi-check2 me-1"></i> Selesai
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <p class="text-muted mb-0">Belum ada pesanan{{ $statusFilter != 'all' ? ' dengan status ini' : '' }}.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
</div>
