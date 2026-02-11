<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold font-serif text-dark mb-1">Daftar Menu</h2>
            <p class="text-muted small mb-0">Kelola kategori dan produk menu restoran anda.</p>
        </div>

        <button wire:click="openCreateCategoryModal" class="btn btn-brand rounded-pill px-4 shadow-sm">
            <i class="bi bi-folder-plus me-2"></i> Tambah Kategori
        </button>
    </div>

    <!-- Categories & Products List -->
    <div class="row">
        <div class="col-12">
            @if($categories->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">Belum ada kategori menu.</h5>
                    <p class="text-muted small">Buat kategori pertama anda untuk mulai menambahkan produk.</p>
                </div>
            @else
                <div class="accordion" id="menuAccordion">
                    @foreach($categories as $category)
                        <div class="accordion-item border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                            <h2 class="accordion-header" id="heading{{ $category->id }}">
                                <button class="accordion-button {{ $activeCategoryId == $category->id ? '' : 'collapsed' }} bg-white px-4 py-3 fw-bold text-dark shadow-none"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $category->id }}"
                                        aria-expanded="{{ $activeCategoryId == $category->id ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $category->id }}">
                                    <span class="me-auto">{{ $category->name }} <span class="badge bg-light text-muted ms-2 rounded-pill">{{ $category->products->count() }} Item</span></span>
                                </button>
                            </h2>
                            <div id="collapse{{ $category->id }}"
                                 class="accordion-collapse collapse {{ $activeCategoryId == $category->id ? 'show' : '' }}"
                                 aria-labelledby="heading{{ $category->id }}"
                                 data-bs-parent="#menuAccordion">
                                <div class="accordion-body bg-light p-4">

                                    <!-- Category Actions -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="btn-group">
                                            <button wire:click="openEditCategoryModal({{ $category->id }})" class="btn btn-sm btn-outline-secondary rounded-pill me-2 px-3">
                                                <i class="bi bi-pencil me-1"></i> Edit Kategori
                                            </button>
                                            @if($category->products->count() == 0)
                                            <button wire:click="deleteCategory({{ $category->id }})"
                                                    wire:confirm="Yakin ingin menghapus kategori ini?"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                            @endif
                                        </div>
                                        <button wire:click="openCreateProductModal({{ $category->id }})" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                                        </button>
                                    </div>

                                    <!-- Products Grid -->
                                    <div class="row g-3">
                                        @forelse($category->products as $product)
                                            <div class="col-md-6 col-lg-4 col-xl-3">
                                                <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden {{ !$product->is_available ? 'opacity-75' : '' }}">
                                                    <!-- Image -->
                                                    <div class="ratio ratio-4x3 bg-secondary bg-opacity-10">
                                                        @if($product->image)
                                                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}">
                                                        @else
                                                            <div class="d-flex align-items-center justify-content-center text-muted">
                                                                <i class="bi bi-image fs-1"></i>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Status Badge -->
                                                    <div class="position-absolute top-0 end-0 p-2">
                                                        <span class="badge {{ $product->is_available ? 'bg-success' : 'bg-secondary' }} rounded-pill shadow-sm">
                                                            {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                                                        </span>
                                                    </div>

                                                    <div class="card-body p-3 d-flex flex-column">
                                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $product->name }}</h6>
                                                        <p class="text-brand fw-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                        <p class="small text-muted mb-2">{{ $product->type }}</p>

                                                        <div class="mt-auto d-flex gap-2">
                                                            <button wire:click="openEditProductModal({{ $product->id }})" class="btn btn-sm btn-light flex-grow-1 rounded-pill border">
                                                                Edit
                                                            </button>
                                                            <button wire:click="toggleAvailability({{ $product->id }})" class="btn btn-sm btn-light rounded-circle border" title="Toggle Status">
                                                                <i class="bi {{ $product->is_available ? 'bi-toggle-on text-success' : 'bi-toggle-off text-muted' }} fs-5"></i>
                                                            </button>
                                                            <button wire:click="deleteProduct({{ $product->id }})"
                                                                    wire:confirm="Hapus produk ini?"
                                                                    class="btn btn-sm btn-light rounded-circle border text-danger" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-4">
                                                <p class="text-muted small mb-0">Belum ada produk di kategori ini.</p>
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

    <!-- Category Modal -->
    @if($showCategoryModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">{{ $isEditingCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showCategoryModal', false)"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveCategory">
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Nama Kategori</label>
                            <input type="text" class="form-control rounded-pill {{ $errors->has('categoryName') ? 'is-invalid' : '' }}"
                                   wire:model="categoryName" placeholder="Contoh: Makanan Utama">
                            @error('categoryName') <span class="invalid-feedback ps-2">{{ $message }}</span> @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4" wire:click="$set('showCategoryModal', false)">Batal</button>
                            <button type="submit" class="btn btn-brand rounded-pill px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Product Modal -->
    @if($showProductModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">{{ $isEditingProduct ? 'Edit Produk' : 'Tambah Produk Baru' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showProductModal', false)"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="saveProduct">
                        <div class="row g-4">
                            <!-- Left: Image -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Foto Produk</label>
                                    <div class="ratio ratio-1x1 bg-light rounded-4 overflow-hidden position-relative border border-dashed text-center d-flex align-items-center justify-content-center">
                                        @if ($productImage)
                                            <img src="{{ $productImage->temporaryUrl() }}" class="object-fit-cover w-100 h-100">
                                        @elseif($existingProductImage)
                                            <img src="{{ asset('storage/' . $existingProductImage) }}" class="object-fit-cover w-100 h-100">
                                        @else
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 w-100 text-muted">
                                                <i class="bi bi-cloud-arrow-up fs-1 mb-2"></i>
                                                <small>Upload Foto</small>
                                            </div>
                                        @endif
                                        <input type="file" wire:model="productImage" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                                    </div>
                                    @error('productImage') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Right: Details -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Nama Produk</label>
                                    <input type="text" class="form-control rounded-pill {{ $errors->has('productName') ? 'is-invalid' : '' }}"
                                           wire:model="productName" placeholder="Contoh: Nasi Goreng Spesial">
                                    @error('productName') <span class="invalid-feedback ps-2">{{ $message }}</span> @enderror
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted fw-bold">Harga (Rp)</label>
                                        <input type="number" class="form-control rounded-pill {{ $errors->has('productPrice') ? 'is-invalid' : '' }}"
                                               wire:model="productPrice" placeholder="0">
                                        @error('productPrice') <span class="invalid-feedback ps-2">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted fw-bold">Kategori</label>
                                        <select class="form-select rounded-pill" wire:model="productCategoryId">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label small text-muted fw-bold">Tipe Produk</label>
                                        <select class="form-select rounded-pill" wire:model="productType">
                                            <option value="single">Single (Satu Pilihan)</option>
                                            <option value="multi">Multi (Banyak Pilihan)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Deskripsi</label>
                                    <textarea class="form-control rounded-4" rows="3" wire:model="productDescription" placeholder="Jelaskan detail produk..."></textarea>
                                </div>

                                <!-- Options Section -->
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold">Varian / Opsi</label>
                                    @foreach($productOptions as $index => $option)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control rounded-start-pill" wire:model="productOptions.{{ $index }}.name" placeholder="Nama Varian">
                                            <button class="btn btn-outline-danger rounded-end-pill" type="button" wire:click="removeOption({{ $index }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" wire:click="addOption">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Varian
                                    </button>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="availabilitySwitch" wire:model="productIsAvailable">
                                    <label class="form-check-label" for="availabilitySwitch">Produk Tersedia</label>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-light rounded-pill px-4" wire:click="$set('showProductModal', false)">Batal</button>
                                    <button type="submit" class="btn btn-brand rounded-pill px-4">Simpan Produk</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
