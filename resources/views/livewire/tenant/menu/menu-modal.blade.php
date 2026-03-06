<div>
    <div class="modal fade" id="dynamicMenuModal" tabindex="-1" aria-hidden="true" wire:ignore.self
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

        <div class="modal-dialog modal-dialog-centered {{ $modalType === 'product' ? 'modal-lg' : '' }}">
            <div class="modal-content rounded-4 border-0 shadow-lg">

                <div class="modal-header border-0 pt-4 px-4 pb-0">
                    <h5 class="modal-title fw-bold font-serif text-dark">
                        @if($modalType === 'category')
                            <i class="bi bi-folder2-open me-2 text-brand"></i> {{ $isEditing ? 'Edit Kategori' : 'Kategori Baru' }}
                        @elseif($modalType === 'product')
                            <i class="bi bi-box-seam me-2 text-brand"></i> {{ $isEditing ? 'Edit Produk' : 'Produk Baru' }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close shadow-none rounded-circle p-2"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 pt-3 pb-4">
                    <form wire:submit.prevent="save">

                        @if($modalType === 'category')
                            <div class="mb-3">
                                <label class="form-label small text-muted fw-bold">Nama Kategori</label>
                                <input type="text"
                                       class="form-control rounded-pill px-3 py-2 {{ $errors->has('form.categoryName') ? 'is-invalid border-danger' : '' }}"
                                       wire:model.blur="form.categoryName"
                                       placeholder="Contoh: Makanan Utama, Kopi, dsb.">
                                @error('form.categoryName') <span
                                    class="invalid-feedback ps-3">{{ $message }}</span> @enderror
                            </div>

                        @elseif($modalType === 'product')
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="mb-3 text-center text-md-start">
                                        <label class="form-label small text-muted fw-bold d-block">Foto Produk</label>
                                        <div
                                            class="ratio ratio-1x1 rounded-4 overflow-hidden position-relative border border-2 border-dashed mx-auto"
                                            style="border-color: var(--ezmenu-border-color) !important;">

                                            @if ($form->productImage)
                                                <img src="{{ $form->productImage->temporaryUrl() }}"
                                                     class="object-fit-cover w-100 h-100">
                                            @elseif($form->existingProductImage)
                                                <img src="{{ asset('storage/' . $form->existingProductImage) }}"
                                                     class="object-fit-cover w-100 h-100">
                                            @else
                                                <div
                                                    class="d-flex flex-column align-items-center justify-content-center h-100 w-100 text-muted opacity-50">
                                                    <i class="bi bi-camera fs-1"></i>
                                                    <small class="fw-bold">Pilih Foto</small>
                                                </div>
                                            @endif

                                            <input type="file" wire:model="form.productImage"
                                                   accept="image/png, image/jpeg, image/webp"
                                                   class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                                        </div>
                                        @error('form.productImage') <span
                                            class="text-danger small d-block mt-2">{{ $message }}</span> @enderror

                                        <div wire:loading wire:target="form.productImage" class="mt-2">
                                            <div class="spinner-border spinner-border-sm text-brand"
                                                 role="status"></div>
                                            <small class="text-brand ms-1 fw-bold">Mengunggah...</small>
                                        </div>
                                    </div>

                                    <div class="bg-light p-3 rounded-4 border">
                                        <div
                                            class="form-check form-switch d-flex justify-content-between align-items-center p-0 m-0">
                                            <label class="form-check-label small fw-bold text-dark" for="availSwitch">Produk
                                                Ready</label>
                                            <input class="form-check-input ms-0 fs-4 cursor-pointer" type="checkbox"
                                                   role="switch" id="availSwitch" wire:model="form.productIsAvailable">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted fw-bold">Nama Menu</label>
                                        <input type="text"
                                               class="form-control rounded-pill {{ $errors->has('form.productName') ? 'is-invalid' : '' }}"
                                               wire:model.blur="form.productName"
                                               placeholder="Contoh: Kopi Susu Gula Aren">
                                        @error('form.productName') <span
                                            class="invalid-feedback ps-3">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small text-muted fw-bold">Harga</label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text border-end-0 rounded-start-pill text-muted small fw-bold">Rp</span>
                                                <input type="number"
                                                       class="form-control border-start-0 rounded-end-pill {{ $errors->has('form.productPrice') ? 'is-invalid' : '' }}"
                                                       wire:model.blur="form.productPrice" placeholder="0">
                                            </div>
                                            @error('form.productPrice') <span
                                                class="text-danger small ps-3 d-block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small text-muted fw-bold">Kategori</label>
                                            <select class="form-select rounded-pill px-3"
                                                    wire:model="form.productCategoryId">
                                                <option value="">Pilih...</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('form.productCategoryId') <span
                                                class="text-danger small ps-3 d-block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="p-3 rounded-4 border">
                                        <div class="mb-3">
                                            <label class="form-label small text-muted fw-bold">Tipe Produk</label>
                                            <select class="form-select form-select-sm rounded-pill"
                                                    wire:model="form.productType">
                                                <option value="single">Single (Tanpa Varian)</option>
                                                <option value="multi">Multi (Pelanggan Pilih Varian)</option>
                                            </select>
                                        </div>

                                        @if(count($form->productOptions) > 0)
                                            <label class="form-label small text-muted fw-bold mb-2">Daftar
                                                Varian</label>
                                            @foreach($form->productOptions as $index => $option)
                                                <div class="input-group input-group-sm mb-2 shadow-sm"
                                                     wire:key="opt-{{ $index }}">
                                                    <input type="text"
                                                           class="form-control rounded-start-pill border-end-0"
                                                           wire:model.blur="form.productOptions.{{ $index }}.name"
                                                           placeholder="Contoh: Level Pedas, Ukuran Large...">
                                                    <button
                                                        class="btn btn-white border border-start-0 rounded-end-pill text-danger px-3"
                                                        type="button" wire:click="removeOption({{ $index }})">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif

                                        <button type="button"
                                                class="btn btn-sm btn-outline-brand rounded-pill w-100 fw-bold mt-1 shadow-sm"
                                                wire:click="addOption">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah Varian Produk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold border"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit"
                                    class="btn btn-brand rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2"
                                    wire:loading.attr="disabled"
                                    wire:target="save">

                                <div wire:loading.remove wire:target="save">
                                    <i class="bi bi-cloud-check"></i> Simpan Data
                                </div>

                                <div wire:loading wire:target="save">
                                    <small class="spinner-border spinner-border-sm" aria-hidden="true"></small>
                                    Menyimpan...
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
