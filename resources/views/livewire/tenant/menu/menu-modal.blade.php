<div>
    <div class="modal fade" id="dynamicMenuModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered {{ $modalType === 'product' ? 'modal-lg' : '' }}">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">

                <div class="modal-header border-bottom-0 pt-4 px-4 pb-2">
                    <h5 class="modal-title fw-bold font-serif text-dark">
                        @if($modalType === 'category')
                            <i class="bi bi-folder me-2 text-brand"></i> {{ $isEditing ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                        @elseif($modalType === 'product')
                            <i class="bi bi-box-seam me-2 text-brand"></i> {{ $isEditing ? 'Edit Produk' : 'Tambah Produk Baru' }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close bg-light rounded-circle p-2 shadow-sm"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 pb-4 pt-2">
                    <form wire:submit.prevent="save">

                        @if($modalType === 'category')
                            <div class="mb-4">
                                <label class="form-label small text-muted fw-bold">Nama Kategori</label>
                                <input type="text"
                                       class="form-control rounded-pill px-3 py-2 {{ $errors->has('form.categoryName') ? 'is-invalid border-danger' : '' }}"
                                       wire:model="form.categoryName"
                                       placeholder="Contoh: Makanan Utama, Minuman Dingin...">
                                @error('form.categoryName') <span
                                    class="invalid-feedback ps-3">{{ $message }}</span> @enderror
                            </div>

                        @elseif($modalType === 'product')
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted fw-bold">Foto Produk</label>
                                        <div
                                            class="ratio ratio-1x1 bg-light rounded-4 overflow-hidden position-relative border border-2 border-dashed text-center d-flex align-items-center justify-content-center"
                                            style="border-color: var(--ezmenu-border-color) !important; transition: all 0.2s ease;">
                                            @if ($form->productImage)
                                                <img src="{{ $form->productImage->temporaryUrl() }}"
                                                     class="object-fit-cover w-100 h-100">
                                            @elseif($form->existingProductImage)
                                                <img src="{{ asset('storage/' . $form->existingProductImage) }}"
                                                     class="object-fit-cover w-100 h-100">
                                            @else
                                                <div
                                                    class="d-flex flex-column align-items-center justify-content-center h-100 w-100 text-muted px-3">
                                                    <i class="bi bi-cloud-arrow-up fs-1 mb-2 text-brand opacity-75"></i>
                                                    <small class="fw-medium">Klik untuk upload foto</small>
                                                    <small class="opacity-50" style="font-size: 0.7rem;">PNG, JPG max
                                                        2MB</small>
                                                </div>
                                            @endif

                                            <input type="file" wire:model="form.productImage"
                                                   accept="image/png, image/jpeg, image/jpg, image/webp"
                                                   class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer"
                                                   title="Pilih Foto">
                                        </div>
                                        @error('form.productImage') <span
                                            class="text-danger small d-block mt-2 ps-1">{{ $message }}</span> @enderror

                                        <div wire:loading wire:target="form.productImage"
                                             class="text-center w-100 mt-2 bg-light rounded-pill py-1 border">
                                            <small class="text-brand fw-medium"><span
                                                    class="spinner-border spinner-border-sm me-1"></span> Mengunggah...</small>
                                        </div>
                                    </div>

                                    <div
                                        class="form-check form-switch mt-4 bg-light p-3 rounded-4 border d-flex align-items-center justify-content-between">
                                        <label class="form-check-label small fw-bold text-dark mb-0 ms-2"
                                               for="availabilitySwitch">Produk Tersedia</label>
                                        <input class="form-check-input m-0 fs-5 cursor-pointer" type="checkbox"
                                               role="switch" id="availabilitySwitch"
                                               wire:model="form.productIsAvailable">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted fw-bold">Nama Produk</label>
                                        <input type="text"
                                               class="form-control rounded-pill px-3 py-2 {{ $errors->has('form.productName') ? 'is-invalid border-danger' : '' }}"
                                               wire:model="form.productName" placeholder="Contoh: Caramel Macchiato">
                                        @error('form.productName') <span
                                            class="invalid-feedback ps-3">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted fw-bold">Harga (Rp)</label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text bg-light border-end-0 rounded-start-pill text-muted fw-bold">Rp</span>
                                                <input type="number"
                                                       class="form-control border-start-0 rounded-end-pill px-2 py-2 {{ $errors->has('form.productPrice') ? 'is-invalid border-danger' : '' }}"
                                                       wire:model="form.productPrice" placeholder="0">
                                            </div>
                                            @error('form.productPrice') <span
                                                class="text-danger small ps-3 d-block mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted fw-bold">Kategori</label>
                                            <select
                                                class="form-select rounded-pill px-3 py-2 {{ $errors->has('form.productCategoryId') ? 'is-invalid border-danger' : '' }}"
                                                wire:model="form.productCategoryId">
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('form.productCategoryId') <span
                                                class="invalid-feedback ps-3">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label small text-muted fw-bold">Tipe Penjualan</label>
                                            <select class="form-select rounded-pill px-3 py-2"
                                                    wire:model="form.productType">
                                                <option value="single">Single (Langsung masuk keranjang)</option>
                                                <option value="multi">Multi (Pelanggan harus pilih varian)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small text-muted fw-bold">Deskripsi Singkat</label>
                                        <textarea class="form-control rounded-4 px-3 py-2" rows="2"
                                                  wire:model="form.productDescription"
                                                  placeholder="Jelaskan komposisi atau rasa produk ini..."></textarea>
                                    </div>

                                    <div class="mb-2 p-3 bg-light rounded-4 border">
                                        <label
                                            class="form-label small text-dark fw-bold mb-2 d-flex justify-content-between align-items-center">
                                            <span>Varian / Opsi (Opsional)</span>
                                            <button type="button"
                                                    class="btn btn-sm btn-light border text-brand fw-medium rounded-pill shadow-sm"
                                                    wire:click="addOption">
                                                <i class="bi bi-plus-lg"></i> Tambah
                                            </button>
                                        </label>

                                        @if(count($form->productOptions) === 0)
                                            <p class="text-muted small mb-0 font-italic">Belum ada varian. (Contoh:
                                                Dingin, Panas, Less Sugar)</p>
                                        @endif

                                        <div class="mt-2">
                                            @foreach($form->productOptions as $index => $option)
                                                <div class="input-group input-group-sm mb-2 shadow-sm">
                                                    <span
                                                        class="input-group-text bg-white rounded-start-pill border-end-0 text-muted"><i
                                                            class="bi bi-funnel"></i></span>
                                                    <input type="text" class="form-control border-start-0 py-2"
                                                           wire:model="form.productOptions.{{ $index }}.name"
                                                           placeholder="Nama Varian (Misal: Extra Shot)">
                                                    <button
                                                        class="btn btn-white border border-start-0 rounded-end-pill text-danger px-3"
                                                        type="button"
                                                        wire:click="removeOption({{ $index }})" title="Hapus Varian">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                @error('form.productOptions.'.$index.'.name') <span
                                                    class="text-danger small ps-3 d-block mb-2">{{ $message }}</span> @enderror
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-light rounded-pill px-4 fw-medium border shadow-sm"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit"
                                    class="btn btn-brand rounded-pill px-4 fw-medium shadow-sm d-flex align-items-center gap-2"
                                    wire:loading.attr="disabled" wire:target="save, form.productImage">
                                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm"></span>
                                <i class="bi bi-check2-circle" wire:loading.remove wire:target="save"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const modalEl = document.getElementById('dynamicMenuModal');
            const modal = new bootstrap.Modal(modalEl);

            Livewire.on('show-bootstrap-modal', () => {
                modal.show();
            });

            Livewire.on('hide-bootstrap-modal', () => {
                modal.hide();
            });

            // Pastikan data kereset pas modal ditutup klik luar
            modalEl.addEventListener('hidden.bs.modal', () => {
                @this.
                call('closeModal');
            });
        });
    </script>
</div>
