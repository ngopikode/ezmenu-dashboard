<div class="position-relative">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold font-serif text-dark mb-1">Manajemen Menu</h2>
            <p class="text-muted small mb-0">Atur kategori dan produk restoranmu dengan cepat.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="btn-group p-1 rounded-pill border shadow-sm bg-light">
                <button wire:click="switchView('grid')"
                        class="btn btn-sm rounded-pill px-3 {{ $viewMode == 'grid' ? 'btn-brand shadow-sm text-white' : 'btn-light border-0 text-muted' }}">
                    <i class="bi bi-grid-fill"></i>
                </button>
                <button wire:click="switchView('list')"
                        class="btn btn-sm rounded-pill px-3 {{ $viewMode == 'list' ? 'btn-brand shadow-sm text-white' : 'btn-light border-0 text-muted' }}">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>

            <button wire:click="$dispatch('openModal', { type: 'category', mode: 'create' })"
                    wire:loading.attr="disabled"
                    class="btn btn-brand rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Tambah Kategori</span>
            </button>
        </div>
    </div>

    <div class="row" wire:loading.class="opacity-50" wire:target="toggleAvailability, deleteProduct, deleteCategory">
        <div class="col-12">

            @if(count($this->categories) === 0)
                <div class="card border-0 shadow-sm rounded-4 py-5 text-center bg-white">
                    <div class="card-body py-5">
                        <i class="bi bi-cup-hot text-muted mb-3 fs-1 opacity-25"></i>
                        <h5 class="fw-bold text-dark font-serif">Menu Masih Kosong</h5>
                        <p class="text-muted small">Mulai buat kategori untuk mengisi daftar menu restoranmu.</p>
                        <button wire:click="$dispatch('openModal', { type: 'category', mode: 'create' })"
                                class="btn btn-brand btn-sm rounded-pill px-4">Buat Kategori
                        </button>
                    </div>
                </div>
            @else
                <div class="accordion" id="menuAccordion">
                    @foreach($this->categories as $category)
                        <div class="accordion-item border-0 shadow-sm rounded-4 mb-3 overflow-hidden"
                             wire:key="cat-{{ $category->id }}">

                            <h2 class="accordion-header position-relative">
                                <button wire:click="loadProducts({{ $category->id }})"
                                        class="accordion-button {{ $activeCategoryId == $category->id ? '' : 'collapsed' }} bg-white px-3 px-md-4 py-3 fw-bold text-dark shadow-none border-bottom"
                                        type="button">
                                    <i class="bi bi-grid-1x2 me-2 me-md-3 text-brand"></i>
                                    <span class="text-truncate me-2">{{ $category->name }}</span>
                                    <small class="badge bg-light text-muted border rounded-pill"
                                           style="font-size: 0.65rem;">
                                        {{ $category->products_count }} <span class="d-none d-sm-inline">Produk</span>
                                    </small>
                                </button>
                            </h2>

                            <div wire:loading wire:target="loadProducts({{ $category->id }})" class="w-100">
                                <div class="accordion-collapse show">
                                    <div class="accordion-body bg-light p-2 p-md-3">

                                        <div
                                            class="mb-3 bg-white p-2 rounded-pill shadow-sm border px-3 placeholder-glow d-flex justify-content-between">
                                            <span class="placeholder col-2 rounded-pill" style="height: 30px;"></span>
                                            <span class="placeholder col-2 rounded-pill" style="height: 30px;"></span>
                                        </div>

                                        @if($viewMode == 'grid')
                                            <div class="row g-2 g-md-3">
                                                @for($i = 0; $i < 4; $i++)
                                                    <div class="col-6 col-md-4 col-lg-3">
                                                        <div
                                                            class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white"
                                                            aria-hidden="true">
                                                            <div
                                                                class="ratio ratio-1x1 bg-secondary opacity-25 placeholder-wave"></div>
                                                            <div class="card-body p-3 placeholder-glow">
                                                                <span
                                                                    class="placeholder col-8 rounded-pill mb-2"></span>
                                                                <span
                                                                    class="placeholder col-5 rounded-pill mb-3"></span>
                                                                <div class="d-flex gap-1">
                                                                    <span class="placeholder col-6 rounded-3"
                                                                          style="height: 30px;"></span>
                                                                    <span class="placeholder col-6 rounded-3"
                                                                          style="height: 30px;"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        @else
                                            <div class="d-flex flex-column gap-2">
                                                @for($i = 0; $i < 3; $i++)
                                                    <div
                                                        class="d-flex align-items-center bg-white p-2 rounded-4 shadow-sm border placeholder-glow"
                                                        aria-hidden="true">
                                                        <div class="rounded-3 bg-secondary opacity-25 placeholder me-3"
                                                             style="width: 55px; height: 55px;"></div>
                                                        <div class="flex-grow-1">
                                                            <span
                                                                class="placeholder col-5 rounded-pill mb-1 d-block"></span>
                                                            <span class="placeholder col-3 rounded-pill"></span>
                                                        </div>
                                                        <div class="d-flex gap-1 ms-2">
                                                            <span class="placeholder rounded-3"
                                                                  style="width: 35px; height: 30px;"></span>
                                                            <span class="placeholder rounded-3"
                                                                  style="width: 65px; height: 30px;"></span>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($activeCategoryId == $category->id)
                                <div class="accordion-collapse show" wire:loading.remove
                                     wire:target="loadProducts({{ $category->id }})">
                                    <div class="accordion-body bg-light p-2 p-md-3">

                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3 bg-white p-2 rounded-pill shadow-sm border px-3">
                                            <div class="btn-group">
                                                <button
                                                    wire:click="$dispatch('openModal', { type: 'category', mode: 'edit', id: {{ $category->id }} })"
                                                    class="btn btn-sm btn-light rounded-pill px-2 px-md-3 border-0">
                                                    <i class="bi bi-pencil"></i> <span class="d-none d-md-inline ms-1">Edit</span>
                                                </button>
                                                @if($category->products_count == 0)
                                                    <button wire:click="deleteCategory({{ $category->id }})"
                                                            wire:confirm="Hapus kategori?"
                                                            class="btn btn-sm btn-light rounded-pill px-2 px-md-3 border-0 text-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            <button
                                                wire:click="$dispatch('openModal', { type: 'product', mode: 'create', categoryId: {{ $category->id }} })"
                                                class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
                                                <i class="bi bi-plus-lg"></i> <span class="d-none d-md-inline ms-1">Produk</span>
                                            </button>
                                        </div>

                                        @if($viewMode == 'grid')
                                            <div class="row g-2 g-md-3">
                                                @forelse($loadedProducts as $product)
                                                    <div class="col-6 col-md-4 col-lg-3"
                                                         wire:key="prod-grid-{{ $product->id }}">
                                                        <div
                                                            class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative {{ !$product->is_available ? 'opacity-75 bg-secondary-subtle' : 'bg-white' }}">

                                                            <div wire:loading
                                                                 wire:target="toggleAvailability({{ $product->id }})">
                                                                <div
                                                                    class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-50 d-flex align-items-center justify-content-center"
                                                                    style="z-index: 5;">
                                                                    <div
                                                                        class="spinner-border text-brand spinner-border-sm"></div>
                                                                </div>
                                                            </div>

                                                            <div class="ratio ratio-1x1 border-bottom bg-light">
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

                                                            <div class="card-body p-2 p-md-3">
                                                                <h6 class="fw-bold text-dark mb-1 text-truncate small">{{ $product->name }}</h6>
                                                                <p class="text-brand fw-bold mb-2"
                                                                   style="font-size: 0.8rem;">
                                                                    Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                                <div class="d-flex gap-1">
                                                                    <button
                                                                        wire:click="$dispatch('openModal', { type: 'product', mode: 'edit', id: {{ $product->id }} })"
                                                                        class="btn btn-sm btn-light border flex-grow-1 rounded-3 py-1">
                                                                        <i class="bi bi-pencil-square"></i>
                                                                    </button>
                                                                    <button
                                                                        wire:click="toggleAvailability({{ $product->id }})"
                                                                        class="btn btn-sm {{ $product->is_available ? 'btn-light border text-success' : 'btn-danger text-white' }} rounded-3 px-2">
                                                                        <i class="bi {{ $product->is_available ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="col-12 py-4 text-center"><small class="text-muted">Kategori
                                                            belum memiliki produk.</small></div>
                                                @endforelse
                                            </div>
                                        @else
                                            <div class="d-flex flex-column gap-2">
                                                @forelse($loadedProducts as $product)
                                                    <div
                                                        class="d-flex align-items-center bg-white p-2 rounded-4 shadow-sm border position-relative {{ !$product->is_available ? 'opacity-75 bg-light' : '' }}"
                                                        wire:key="prod-list-{{ $product->id }}">

                                                        <div wire:loading
                                                             wire:target="toggleAvailability({{ $product->id }})">
                                                            <div
                                                                class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-50 rounded-4 d-flex align-items-center justify-content-center"
                                                                style="z-index: 5;">
                                                                <div
                                                                    class="spinner-border text-brand spinner-border-sm"></div>
                                                            </div>
                                                        </div>

                                                        <img
                                                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=random' }}"
                                                            class="rounded-3 object-fit-cover me-3"
                                                            style="width: 55px; height: 55px;">

                                                        <div class="flex-grow-1 min-w-0 me-2">
                                                            <h6 class="fw-bold text-dark mb-0 text-truncate small text-wrap">{{ $product->name }}</h6>
                                                            <span class="text-brand fw-bold"
                                                                  style="font-size: 0.75rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                        </div>

                                                        <div class="d-flex gap-1">
                                                            <button
                                                                wire:click="$dispatch('openModal', { type: 'product', mode: 'edit', id: {{ $product->id }} })"
                                                                class="btn btn-sm btn-light border rounded-3"><i
                                                                    class="bi bi-pencil"></i></button>
                                                            <button wire:click="toggleAvailability({{ $product->id }})"
                                                                    class="btn btn-sm {{ $product->is_available ? 'btn-light border text-success' : 'btn-danger text-white' }} rounded-3 px-3 fw-bold shadow-sm"
                                                                    style="font-size: 0.7rem;">
                                                                {{ $product->is_available ? 'READY' : 'HABIS' }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-3"><small class="text-muted">Kategori
                                                            belum memiliki produk.</small></div>
                                                @endforelse
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
