<div x-data="{ activeFilter: $wire.entangle('statusFilter').live }">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-4">
        <div>
            <h2 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Pesanan Masuk</h2>
            <p class="text-secondary mb-0">Kelola antrean dan pantau performa pesanan hari ini.</p>
        </div>

        <div class="position-relative" style="min-width: 320px;">
            <i class="bi bi-search position-absolute text-muted"
               style="top: 50%; left: 1.25rem; transform: translateY(-50%);"></i>
            <input type="text"
                   class="form-control form-control-lg rounded-pill bg-white border border-light shadow-sm ps-5 text-sm"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Ketik nama atau ID pesanan...">
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-6 col-md-3">
            <div @click="activeFilter = 'all'"
                 :class="activeFilter === 'all' ? 'border-dark border-2 shadow-sm' : 'border-light border hover-shadow'"
                 class="card rounded-4 bg-white transition-all h-100" style="cursor: pointer;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-2 rounded-circle bg-light text-dark">
                            <i class="bi bi-inbox-fill fs-5"></i>
                        </div>
                        <span class="badge bg-light text-dark border rounded-pill px-2">Total</span>
                    </div>
                    <h3 class="fw-bolder text-dark mb-0">{{ $allCount }}</h3>
                    <p class="text-muted small fw-medium mb-0 mt-1">Semua Pesanan</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div @click="activeFilter = 'pending'"
                 :class="activeFilter === 'pending' ? 'border-warning border-2 shadow-sm' : 'border-light border hover-shadow'"
                 class="card rounded-4 bg-white transition-all h-100" style="cursor: pointer;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-2 rounded-circle bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-clock-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bolder text-dark mb-0">{{ $pendingCount }}</h3>
                    <p class="text-muted small fw-medium mb-0 mt-1">Menunggu Proses</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div @click="activeFilter = 'confirmed'"
                 :class="activeFilter === 'confirmed' ? 'border-info border-2 shadow-sm' : 'border-light border hover-shadow'"
                 class="card rounded-4 bg-white transition-all h-100" style="cursor: pointer;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-2 rounded-circle bg-info bg-opacity-10 text-info">
                            <i class="bi bi-arrow-repeat fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bolder text-dark mb-0">{{ $confirmedCount }}</h3>
                    <p class="text-muted small fw-medium mb-0 mt-1">Sedang Disiapkan</p>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div @click="activeFilter = 'completed'"
                 :class="activeFilter === 'completed' ? 'border-success border-2 shadow-sm' : 'border-light border hover-shadow'"
                 class="card rounded-4 bg-white transition-all h-100" style="cursor: pointer;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-2 rounded-circle bg-success bg-opacity-10 text-success">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bolder text-dark mb-0">{{ $completedCount }}</h3>
                    <p class="text-muted small fw-medium mb-0 mt-1">Pesanan Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="position-relative" wire:poll.15s>

        <div wire:loading wire:target="statusFilter, search, gotoPage, previousPage, nextPage"
             class="w-100 position-absolute top-0 start-0 z-2 bg-white" style="min-height: 400px;">
            <div class="d-flex flex-column gap-3 py-4">
                @for($i = 0; $i < 5; $i++)
                    <div class="d-flex align-items-center justify-content-between placeholder-glow px-3">
                        <div class="d-flex align-items-center gap-3 w-50">
                            <span class="placeholder rounded-circle" style="width: 48px; height: 48px;"></span>
                            <div class="d-flex flex-column gap-2 w-50">
                                <span class="placeholder col-8 rounded"></span>
                                <span class="placeholder col-4 rounded"></span>
                            </div>
                        </div>
                        <span class="placeholder col-2 rounded"></span>
                        <span class="placeholder col-2 rounded"></span>
                    </div>
                    <hr class="text-light my-1">
                @endfor
            </div>
        </div>

        <div wire:loading.remove wire:target="statusFilter, search, gotoPage, previousPage, nextPage" class="w-100">

            @if($orders->isEmpty())
                <div
                    class="card border border-light border-2 border-dashed shadow-none rounded-4 text-center py-5 my-3 bg-light bg-opacity-50">
                    <div class="card-body py-5">
                        <div
                            class="d-inline-flex align-items-center justify-content-center bg-white shadow-sm rounded-circle mb-4"
                            style="width: 90px; height: 90px;">
                            <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Belum ada pesanan</h4>
                        <p class="text-secondary mb-0">Pesanan yang sesuai dengan filter akan muncul di sini.</p>
                    </div>
                </div>
            @else

                <div class="d-md-none d-flex flex-column gap-3">
                    @foreach($orders as $order)
                        <div class="card border border-light shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span
                                        class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-bold text-uppercase"
                                        style="letter-spacing: 1px;">#{{ $order->id }}</span>
                                    <span class="text-muted small fw-medium"><i class="bi bi-clock me-1"></i> {{ $order->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div
                                        class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold fs-5 shadow-sm"
                                        style="width: 48px; height: 48px;">
                                        {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bolder text-dark mb-1 fs-5">{{ $order->customer_name }}</h6>
                                        <p class="small text-secondary mb-0">
                                            {{ $order->order_type }} • {{ $order->items_count }} item
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="d-flex justify-content-between align-items-center p-3 bg-light rounded-4 mb-3">
                                    <span class="text-secondary small fw-medium text-uppercase">Total</span>
                                    <span
                                        class="fw-bolder text-dark fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>

                                <div class="d-flex gap-2">
                                    <button wire:click="$dispatch('openModal', { orderId: {{ $order->id }} })"
                                            class="btn btn-light rounded-pill border fw-bold text-dark w-50 py-2">
                                        Detail
                                    </button>

                                    @if($order->status == 'pending')
                                        <button wire:click="updateStatus({{ $order->id }}, 'confirmed')"
                                                class="btn btn-dark rounded-pill fw-bold w-50 py-2">
                                            Proses
                                        </button>
                                    @elseif($order->status == 'confirmed')
                                        <button wire:click="updateStatus({{ $order->id }}, 'completed')"
                                                class="btn btn-success text-white rounded-pill fw-bold w-50 py-2">
                                            Selesai
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-none d-md-block card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-white border-bottom border-light">
                            <tr>
                                <th class="text-secondary small text-uppercase fw-bold py-4 ps-4"
                                    style="letter-spacing: 0.5px;">Pesanan
                                </th>
                                <th class="text-secondary small text-uppercase fw-bold py-4">Pelanggan</th>
                                <th class="text-secondary small text-uppercase fw-bold py-4">Status</th>
                                <th class="text-secondary small text-uppercase fw-bold py-4">Waktu</th>
                                <th class="text-secondary small text-uppercase fw-bold py-4 text-end">Total</th>
                                <th class="text-secondary small text-uppercase fw-bold py-4 pe-4 text-center">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr class="border-bottom border-light" style="transition: all 0.2s ease;">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bolder text-dark fs-6">#{{ $order->id }}</span>
                                            <span class="small text-secondary mt-1"><i class="bi bi-box me-1"></i>{{ $order->items_count }} item</span>
                                        </div>
                                    </td>

                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div
                                                class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center fw-bolder"
                                                style="width: 40px; height: 40px; font-size: 1.1rem;">
                                                {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">{{ $order->customer_name }}</span>
                                                <span class="small text-secondary mt-1">{{ $order->order_type }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3">
                                        @if($order->status == 'pending')
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                                                <span class="rounded-circle bg-warning"
                                                      style="width: 6px; height: 6px;"></span> Menunggu
                                            </span>
                                        @elseif($order->status == 'confirmed')
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                                                <span class="rounded-circle bg-info"
                                                      style="width: 6px; height: 6px;"></span> Diproses
                                            </span>
                                        @elseif($order->status == 'completed')
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                                                <span class="rounded-circle bg-success"
                                                      style="width: 6px; height: 6px;"></span> Selesai
                                            </span>
                                        @elseif($order->status == 'cancelled')
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                                                <span class="rounded-circle bg-danger"
                                                      style="width: 6px; height: 6px;"></span> Dibatalkan
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-secondary fw-medium py-3">
                                        {{ $order->created_at->diffForHumans() }}
                                    </td>

                                    <td class="text-end fw-bolder text-dark fs-6 py-3">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>

                                    <td class="pe-4 py-3 text-center">
                                        <div class="d-inline-flex gap-2">
                                            <button wire:click="$dispatch('openModal', { orderId: {{ $order->id }} })"
                                                    class="btn btn-light rounded-pill border-light text-dark fw-bold px-3 py-2 btn-sm hover-dark transition-all">
                                                Detail
                                            </button>
                                            @if($order->status == 'pending')
                                                <button wire:click="updateStatus({{ $order->id }}, 'confirmed')"
                                                        class="btn btn-dark rounded-pill fw-bold px-4 py-2 btn-sm shadow-sm transition-all">
                                                    Proses
                                                </button>
                                            @elseif($order->status == 'confirmed')
                                                <button wire:click="updateStatus({{ $order->id }}, 'completed')"
                                                        class="btn btn-success text-white rounded-pill fw-bold px-4 py-2 btn-sm shadow-sm transition-all">
                                                    Selesai
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 gap-3 border-top pt-4">
        <div class="text-secondary small text-center text-md-start fw-medium">
            Menampilkan <span class="fw-bold text-dark">{{ $orders->firstItem() ?? 0 }}</span> - <span
                class="fw-bold text-dark">{{ $orders->lastItem() ?? 0 }}</span>
            dari total <span class="fw-bold text-dark">{{ $orders->total() }}</span> pesanan
        </div>
        <div class="overflow-auto w-100 d-flex justify-content-center justify-content-md-end">
            {{ $orders->links() }}
        </div>
    </div>
</div>
