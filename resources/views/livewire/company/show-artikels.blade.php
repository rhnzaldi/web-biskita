<div>
    @if (auth()->check())
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#tambahArtikel">Tambah
            Artikel</button>
    @endif
    @if ($artikels->isEmpty())
        <div class="text-center mt-4">
            <i class="fa-solid fa-circle-exclamation fa-5x"></i>
            <h3 class="text-black mt-4">INFORMASI</h3>
            <p>Saat ini belum ada berita terbaru dari wisata di Kota Bogor.</p>
        </div>
    @endif
    <div class="row">
        @foreach ($artikels as $artikel)
            <div class="col-4 mt-4">
                <div class="d-flex flex-column h-100 text-center">
                    <a href="#">
                        <img src="{{ asset('storage/article/' . $artikel->image) }}" alt="Image" class="img-fluid">
                    </a>
                    <div class="px-md-3 flex-grow-1 mt-3">
                        <h3><a href="#">{{ $artikel->judul }}</a></h3>
                        <p>{{ Str::limit($artikel->deskripsi, 120, '...') }}</p>
                    </div>
                    @if (auth()->check())
                        <div class="mt-auto px-md-3 pb-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" wire:click="openModal({{ $artikel->id }})"
                                        class="btn btn-outline-warning w-100" data-bs-toggle="modal"
                                        data-bs-target="#editArtikel">Edit Artikel</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" wire:click="openModal({{ $artikel->id }})"
                                        data-bs-toggle="modal" data-bs-target="#modalHapus"
                                        class="btn btn-outline-danger w-100">Hapus Artikel</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- MODAL EDIT --}}
    <div wire:ignore.self class="modal fade" id="editArtikel" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="editArtikelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editArtikelLabel">Tambah Artikel</h1>
                </div>
                @if ($selectedArticle)
                    <form wire:submit="update({{ $selectedArticle->id }})">
                        <div class="modal-body">
                            <div class="mb-3">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" alt=""
                                        class="img-thumbnail img-preview">
                                @elseif($selectedArticle)
                                    <img src="{{ asset('storage/article/' . $selectedArticle->image) }}" alt=""
                                        class="img-thumbnail img-preview">
                                @else
                                    <img src="{{ asset('images/blank_image.jpg') }}" alt=""
                                        class="img-thumbnail img-preview">
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Foto Artikel</label>
                                <input type="file" id="image" wire:model="image">
                                @error('image')
                                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="alert"
                                            aria-label="Close">Close</button>
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Artikel</label>
                                <input type="text" class="form-control" id="judul" maxlength="50"
                                    placeholder="Tentukan Judul Artikel" required wire:model.live="judul">
                                <small>{{ strlen($judul) }}/50 karakter</small>
                                @error('judul')
                                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="alert"
                                            aria-label="Close">Close</button>
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Artikel</label>
                                <textarea class="form-control" id="deskripsi" rows="3" required wire:model="deskripsi"></textarea>
                                @error('deskripsi')
                                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                        <strong>{{ $message }}</strong>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="alert"
                                            aria-label="Close">Close</button>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                wire:click="resetInput">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAHs --}}
    <div wire:ignore.self class="modal fade" id="tambahArtikel" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="tambahArtikelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tambahArtikelLabel">Tambah Artikel</h1>
                </div>
                <form wire:submit="store">
                    <div class="modal-body">
                        <div class="mb-3">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" alt=""
                                    class="img-thumbnail img-preview">
                            @else
                                <img src="{{ asset('images/blank_image.jpg') }}" alt=""
                                    class="img-thumbnail img-preview">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Foto Artikel</label>
                            <input type="file" id="image" wire:model="image">
                            @error('image')
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    <strong>{{ $message }}</strong>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="alert"
                                        aria-label="Close">Close</button>
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Artikel</label>
                            <input type="text" class="form-control" id="judul" maxlength="50"
                                placeholder="Tentukan Judul Artikel" required wire:model.live="judul">
                            <small>{{ strlen($judul) }}/50 karakter</small>
                            @error('judul')
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    <strong>{{ $message }}</strong>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="alert"
                                        aria-label="Close">Close</button>
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Artikel</label>
                            <textarea class="form-control" id="deskripsi" rows="3" required wire:model="deskripsi"></textarea>
                            @error('deskripsi')
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    <strong>{{ $message }}</strong>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="alert"
                                        aria-label="Close">Close</button>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div wire:ignore.self class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-danger fw-bold" id="modalHapusLabel">PERINGATAN !</h1>
                </div>
                <div class="modal-body">
                    @if ($selectedArticle)
                        <form wire:submit="delete({{ $selectedArticle->id }})">
                            <p>Apakah anda yakin ingin menghapus artikel ini ?</p>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary" style="margin-right: 10px;"
                                    data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
