<div class="position-relative">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold font-serif text-dark mb-1">Manajemen Menu</h2>
            <p class="text-muted small mb-0">Atur kategori dan produk restoranmu dengan cepat.</p>
        </div>

        <button wire:click="$dispatch('open-menu-modal', { type: 'category', mode: 'create' })"
                wire:loading.attr="disabled"
                class="btn btn-brand rounded-pill px-4 shadow-sm py-2">
            <i class="bi bi-folder-plus me-2"></i> Tambah Kategori
        </button>
    </div>

    <div class="row" wire:loading.class="opacity-50" wire:target="toggleAvailability, deleteProduct, deleteCategory">
        <div class="col-12">

            {{-- Pake count() biar aman kalo datanya Array atau Collection --}}
            @if(count($this->categories) === 0)
                <div class="card border-0 shadow-sm rounded-4 py-5 text-center bg-white">
                    <div class="card-body py-5">
                        <i class="bi bi-cup-hot text-muted mb-3 fs-1 opacity-25"></i>
                        <h5 class="fw-bold text-dark">Menu Masih Kosong</h5>
                        <p class="text-muted">Buat kategori makanan/minumanmu untuk mulai berjualan.</p>
                        <button wire:click="$dispatch('open-menu-modal', { type: 'category', mode: 'create' })"
                                class="btn btn-brand btn-sm rounded-pill px-4">
                            Buat Kategori Sekarang
                        </button>
                    </div>
                </div>
            @else
                <div class="accordion" id="menuAccordion" wire:ignore.self>
                    @foreach($this->categories as $category)
                        <div class="accordion-item border-0 shadow-sm rounded-4 mb-3 overflow-hidden"
                             wire:key="cat-{{ $category->id }}">

                            <h2 class="accordion-header">
                                <button
                                    class="accordion-button {{ $activeCategoryId == $category->id ? '' : 'collapsed' }} bg-white px-4 py-3 fw-bold text-dark shadow-none border-bottom"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $category->id }}">
                                    <i class="bi bi-grid-1x2 me-3 text-brand"></i>
                                    <span class="me-auto">{{ $category->name }}</span>
                                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2"
                                          style="font-size: 0.7rem;">
                                        {{ $category->products->count() }} Produk
                                    </span>
                                </button>
                            </h2>

                            <div id="collapse{{ $category->id }}"
                                 class="accordion-collapse collapse {{ $activeCategoryId == $category->id ? 'show' : '' }}"
                                 data-bs-parent="#menuAccordion"
                                 wire:ignore.self>
                                <div class="accordion-body bg-light p-3">

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-3 bg-white p-2 rounded-pill shadow-sm border px-3">
                                        <div class="btn-group">
                                            <button
                                                wire:click="$dispatch('open-menu-modal', { type: 'category', mode: 'edit', id: {{ $category->id }} })"
                                                class="btn btn-sm btn-light rounded-pill px-3 border-0">
                                                <i class="bi bi-pencil me-1"></i> Edit
                                            </button>
                                            @if($category->products->count() == 0)
                                                <button wire:click="deleteCategory({{ $category->id }})"
                                                        wire:confirm="Hapus kategori ini?"
                                                        class="btn btn-sm btn-light rounded-pill px-3 border-0 text-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <button
                                            wire:click="$dispatch('open-menu-modal', { type: 'product', mode: 'create', categoryId: {{ $category->id }} })"
                                            class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
                                            <i class="bi bi-plus-lg me-1"></i> Produk
                                        </button>
                                    </div>

                                    <div class="row g-2">
                                        @forelse($category->products as $product)
                                            <div class="col-6 col-md-4 col-lg-3" wire:key="prod-{{ $product->id }}">
                                                <div
                                                    class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative {{ !$product->is_available ? 'opacity-75 bg-secondary-subtle' : '' }}">

                                                    <div wire:loading
                                                         wire:target="toggleAvailability({{ $product->id }})">

                                                        <div
                                                            class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-50 d-flex align-items-center justify-content-center"
                                                            style="z-index: 5;">
                                                            <div
                                                                class="spinner-border text-brand spinner-border-sm"></div>
                                                        </div>
                                                    </div>

                                                    <div class="ratio ratio-1x1 bg-light border-bottom">
                                                        @if($product->image)
                                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                                 class="object-fit-cover">
                                                        @else
                                                            <div
                                                                class="d-flex align-items-center justify-content-center text-muted opacity-25">
                                                                <i class="bi bi-image fs-1"></i>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="card-body p-2">
                                                        <h6 class="fw-bold text-dark mb-1 text-truncate small">{{ $product->name }}</h6>
                                                        <p class="text-brand fw-bold mb-2" style="font-size: 0.8rem;">
                                                            Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                                                        <div class="d-flex gap-1">
                                                            <button
                                                                wire:click="$dispatch('open-menu-modal', { type: 'product', mode: 'edit', id: {{ $product->id }} })"
                                                                class="btn btn-sm btn-light border flex-grow-1 rounded-3 py-1">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button wire:click="toggleAvailability({{ $product->id }})"
                                                                    class="btn btn-sm {{ $product->is_available ? 'btn-light border text-success' : 'btn-danger text-white' }} rounded-3 px-2">
                                                                <i class="bi {{ $product->is_available ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                                                <small
                                                                    class="d-none d-md-inline ms-1">{{ $product->is_available ? 'Ready' : 'Habis' }}</small>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 py-3 text-center">
                                                <small class="text-muted">Kategori ini belum ada isinya.</small>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
