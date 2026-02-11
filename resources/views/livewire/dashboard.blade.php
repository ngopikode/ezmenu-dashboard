<div>
    <!-- 1. Header & Welcome -->
    <div class="row align-items-end mb-4">
        <div class="col-md-8">
            <h6 class="text-brand text-uppercase small fw-bold mb-1" style="letter-spacing: 2px;">Selamat
                Datang, {{ $user->name }}</h6>
            <h2 class="fw-bold font-serif text-dark mb-0">{{ $restaurant->name ?? 'Restaurant Name' }}</h2>
            <p class="text-muted small mb-0 mt-2">
                @if($restaurant)
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Active</span>
                    <a href="{{ config('app.frontend_url_base') }}/client/{{ $restaurant->subdomain }}" target="_blank" class="text-decoration-none ms-2">
                        <i class="bi bi-link-45deg"></i> {{ $restaurant->subdomain }}.{{ config('app.frontend_url_base') }}
                    </a>
                @else
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2">Setup Required</span>
                @endif
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('orders.index') }}" wire:navigate class="btn btn-brand rounded-pill px-4 py-2 shadow-sm">
                <i class="bi bi-receipt me-2"></i> Lihat Pesanan
            </a>
        </div>
    </div>

    @if(!$restaurant)
        <div class="alert alert-warning border-0 shadow-sm rounded-4" role="alert">
            <h4 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Belum ada Restaurant!</h4>
            <p>Anda belum mengatur informasi restoran anda. Silahkan atur sekarang untuk mulai menerima pesanan.</p>
            <hr>
            <p class="mb-0"><a href="{{ route('settings.index') }}" class="btn btn-sm btn-dark rounded-pill">Atur Restoran Sekarang</a></p>
        </div>
    @else

    <!-- 2. Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Orders Today -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Pesanan Hari Ini</p>
                            <h3 class="fw-bold text-dark mb-0">{{ $stats['orders_today'] }}</h3>
                        </div>
                        <div class="bg-brand bg-opacity-10 text-brand rounded-circle p-3">
                            <i class="bi bi-bag-check-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Today -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Pendapatan Hari Ini</p>
                            <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small text-uppercase fw-bold mb-1">Pesanan Menunggu</p>
                            <h3 class="fw-bold text-{{ $stats['pending_orders'] > 0 ? 'danger' : 'dark' }} mb-0">{{ $stats['pending_orders'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Recent Orders & Quick Actions -->
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">Pesanan Terbaru</h6>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-light rounded-pill px-3 text-muted border">Lihat Semua</a>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="border-0 rounded-start ps-3 py-3">ID</th>
                                    <th class="border-0 py-3">Pelanggan</th>
                                    <th class="border-0 py-3">Total</th>
                                    <th class="border-0 py-3">Status</th>
                                    <th class="border-0 rounded-end pe-3 py-3 text-end">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td class="ps-3 fw-bold">#{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $order->customer_name }}</span>
                                            <span class="small text-muted">{{ $order->order_type }}</span>
                                        </div>
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info text-white rounded-pill px-3">Diproses</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success text-white rounded-pill px-3">Selesai</span>
                                        @else
                                            <span class="badge bg-secondary text-white rounded-pill px-3">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3 text-muted small">
                                        {{ $order->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada pesanan terbaru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Active Menu -->
        <div class="col-lg-4">
            <!-- Active Products -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                 <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Menu Aktif</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $stats['active_products'] }} <span class="fs-6 text-muted fw-normal">Item</span></h3>
                    </div>
                    <a href="{{ route('menu.index') }}" class="btn btn-outline-dark rounded-circle p-3">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                 </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Aksi Cepat</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 text-start border bg-light text-dark">
                            <i class="bi bi-plus-circle me-2 text-brand"></i> Tambah Menu Baru
                        </a>
                        <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 text-start border bg-light text-dark">
                            <i class="bi bi-qr-code me-2 text-primary"></i> Lihat QR Code
                        </a>
                        <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 text-start border bg-light text-dark">
                            <i class="bi bi-shop me-2 text-success"></i> Edit Profil Resto
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
