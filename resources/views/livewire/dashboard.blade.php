<div>
    <!-- 1. Header & Project Switcher -->
    <div class="row align-items-end mb-4">
        <div class="col-md-8">
            <h6 class="text-brand text-uppercase small fw-bold mb-1" style="letter-spacing: 2px;">Selamat
                Datang, {{ $user->name }}</h6>
            <h2 class="fw-bold font-serif text-dark mb-0">Kelola Undangan Digital</h2>
            <p class="text-muted small mb-0 mt-2">Anda memiliki <strong class="text-dark">{{ $activeCount }} Undangan Aktif</strong> dan
                <strong class="text-dark">{{ $draftCount }} Draft</strong>.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('invitation.create') }}" wire:navigate class="btn btn-brand rounded-pill px-4 py-2 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i> Buat Undangan Baru
            </a>
        </div>
    </div>

    <!-- 2. Project / Theme Cards List (Multi-Theme Support) -->
    <div class="row g-4 mb-5">
        @forelse($events as $event)
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden" style="{{ !$event->is_active ? 'opacity: 0.9;' : '' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        @if($event->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Publik</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">Draft</span>
                        @endif

                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i></button>
                            <ul class="dropdown-menu shadow-sm border-0">
                                <li><a class="dropdown-item" href="{{ route('invitation.show', $event->subdomain) }}">Edit Data</a></li>
                                <li><a class="dropdown-item" href="#">Ganti Tema</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="#">Arsipkan</a></li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark font-serif mb-1">{{ $event->title }}</h5>
                    <p class="text-muted small mb-3"><i class="bi bi-palette me-1 text-brand"></i> Tema: {{ $event->template_name ?? 'Default' }}
                    </p>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-4"
                                wire:click="$dispatch('open-preview', { url: '{{ route('invitation.preview', $event->subdomain) }}' })">
                            Preview
                        </button>
                        <a href="{{ route('invitation.show', $event->subdomain) }}" class="btn btn-sm btn-brand rounded-pill px-4">Kelola</a>
                    </div>
                </div>
                <!-- Decorative Border Bottom -->
                @if($event->is_active)
                <div class="position-absolute bottom-0 start-0 w-100 bg-brand" style="height: 4px;"></div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada undangan. Mulai buat sekarang!</p>
        </div>
        @endforelse

        <!-- Card 3: Add New Placeholder -->
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('invitation.create') }}" wire:navigate class="text-decoration-none">
                <div
                    class="card h-100 border-2 border-dashed shadow-none rounded-4 bg-transparent d-flex align-items-center justify-content-center"
                    style="border-color: #e2e8f0; min-height: 200px;">
                    <div class="text-center p-4">
                        <div
                            class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-plus-lg fs-4 text-brand"></i>
                        </div>
                        <h6 class="fw-bold text-muted">Tambah Tema / Acara</h6>
                        <p class="small text-muted opacity-75">Buat undangan untuk acara berbeda</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    @if($selectedEvent)
    <!-- 3. Detail Statistik (Fokus pada Event Utama) -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold text-dark mb-0">Statistik: {{ $selectedEvent->title }}</h5>
        <select class="form-select form-select-sm w-auto border-0 shadow-sm bg-white rounded-pill px-3" wire:model.live="selectedEventId">
            @foreach($events as $event)
                <option value="{{ $event->id }}">{{ $event->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="row g-4 mb-4">
        <!-- Countdown Timer -->
        <div class="col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden bg-white"
                 x-data="countdown('{{ $selectedEvent->event_date }}')" x-init="init()">
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center position-relative z-1">
                    <h6 class="text-muted text-uppercase small fw-bold mb-4">Menuju Hari Bahagia</h6>
                    <div class="d-flex justify-content-center gap-3 align-items-start text-dark">
                        <div>
                            <span class="display-4 fw-bold d-block lh-1 text-brand" x-text="days">00</span>
                            <span class="small text-muted text-uppercase" style="font-size: 0.7rem;">Hari</span>
                        </div>
                        <span class="display-5 fw-light text-muted">:</span>
                        <div>
                            <span class="display-4 fw-bold d-block lh-1 text-brand" x-text="hours">00</span>
                            <span class="small text-muted text-uppercase" style="font-size: 0.7rem;">Jam</span>
                        </div>
                    </div>
                </div>
                <!-- Background Decoration (Subtle) -->
                <i class="bi bi-hourglass-split position-absolute bottom-0 end-0 mb-n3 me-n3 text-muted opacity-10"
                   style="font-size: 8rem;"></i>
            </div>
        </div>

        <!-- RSVP Chart & Progress -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div
                    class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Kehadiran Tamu</h6>
                        <small class="text-muted">Total Undangan: {{ $stats['total_invitations'] }}</small>
                    </div>
                    <button class="btn btn-sm btn-light rounded-pill px-3 text-muted border"><i
                            class="bi bi-download me-1"></i> Export
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <!-- Donut Chart -->
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="position-relative d-inline-block" style="width: 140px; height: 140px;">
                                <div class="rounded-circle w-100 h-100"
                                     style="background: conic-gradient(
                                         var(--brand-color) 0% {{ $stats['attending_percent'] }}%,
                                         #dc3545 {{ $stats['attending_percent'] }}% {{ $stats['attending_percent'] + $stats['not_attending_percent'] }}%,
                                         #ffc107 {{ $stats['attending_percent'] + $stats['not_attending_percent'] }}% {{ $stats['attending_percent'] + $stats['not_attending_percent'] + $stats['maybe_percent'] }}%,
                                         #e9ecef {{ $stats['attending_percent'] + $stats['not_attending_percent'] + $stats['maybe_percent'] }}% 100%
                                     );">
                                </div>
                                <div
                                    class="position-absolute top-50 start-50 translate-middle bg-white rounded-circle d-flex flex-column align-items-center justify-content-center shadow-sm"
                                    style="width: 110px; height: 110px;">
                                    <span class="h3 fw-bold text-dark mb-0">{{ $stats['attending_percent'] }}%</span>
                                    <span class="small text-muted fw-bold" style="font-size: 0.6rem;">HADIR</span>
                                </div>
                            </div>
                        </div>

                        <!-- Details List -->
                        <div class="col-md-8">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted"><i class="bi bi-circle-fill text-brand me-2"
                                                                          style="font-size: 0.5rem;"></i>Hadir</span>
                                        <span class="small fw-bold text-dark">{{ $stats['attending'] }}</span>
                                    </div>
                                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                                        <div class="progress-bar bg-brand" role="progressbar" style="width: {{ $stats['attending_percent'] }}%"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted"><i class="bi bi-circle-fill text-danger me-2"
                                                                          style="font-size: 0.5rem;"></i>Tidak Hadir</span>
                                        <span class="small fw-bold text-dark">{{ $stats['not_attending'] }}</span>
                                    </div>
                                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                                        <div class="progress-bar bg-danger" role="progressbar"
                                             style="width: {{ $stats['not_attending_percent'] }}%"></div>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted"><i class="bi bi-circle-fill text-warning me-2"
                                                                          style="font-size: 0.5rem;"></i>Ragu-ragu</span>
                                        <span class="small fw-bold text-dark">{{ $stats['maybe'] }}</span>
                                    </div>
                                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                             style="width: {{ $stats['maybe_percent'] }}%"></div>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted"><i class="bi bi-circle-fill text-secondary me-2"
                                                                          style="font-size: 0.5rem;"></i>Belum Respon</span>
                                        <span class="small fw-bold text-dark">{{ $stats['not_responded'] }}</span>
                                    </div>
                                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                                        <div class="progress-bar bg-secondary" role="progressbar"
                                             style="width: {{ $stats['not_responded_percent'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 4. Quick Actions & Notifications -->
    <div class="row g-4">
        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">Aksi Cepat</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="#"
                           class="btn btn-outline-secondary rounded-pill px-3 py-2 small border bg-light text-dark"><i
                                class="bi bi-pencil-square me-2"></i>Edit Informasi</a>
                        <a href="#"
                           class="btn btn-outline-secondary rounded-pill px-3 py-2 small border bg-light text-dark"><i
                                class="bi bi-images me-2"></i>Kelola Galeri</a>
                        <a href="#"
                           class="btn btn-outline-secondary rounded-pill px-3 py-2 small border bg-light text-dark"><i
                                class="bi bi-music-note-beamed me-2"></i>Atur Musik</a>
                        <a href="#"
                           class="btn btn-outline-secondary rounded-pill px-3 py-2 small border bg-light text-dark"><i
                                class="bi bi-share me-2"></i>Bagikan Link</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification / Tip -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white position-relative overflow-hidden">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-circle p-3 me-3 text-warning">
                        <i class="bi bi-lightbulb fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Tips Hari Ini</h6>
                        <p class="text-muted small mb-0">Jangan lupa cek tampilan undangan di mode mobile agar tamu
                            lebih nyaman saat mengakses.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <livewire:invitation.preview-modal />

    <script>
        function countdown(dateString) {
            return {
                days: '00',
                hours: '00',
                target: new Date(dateString).getTime(),
                init() {
                    this.update();
                    setInterval(() => this.update(), 1000 * 60 * 60); // Update every hour is enough for days/hours, or every minute
                    // Actually user wants days and hours.
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    const now = new Date().getTime();
                    const diff = this.target - now;
                    if (diff < 0) {
                        this.days = '00';
                        this.hours = '00';
                        return;
                    }
                    this.days = Math.floor(diff / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
                    this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                }
            }
        }
    </script>
</div>
