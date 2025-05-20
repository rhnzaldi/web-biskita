<div class="artikel-scroll-container">
    <div class="artikel-scroll-wrapper">
        @foreach ($wisatas as $wisata)
            <div class="artikel-card">
                <a href="#">
                    <img src="{{ asset('storage/wisata/' . $wisata->image) }}" alt="Image">
                </a>
                <div class="artikel-content">
                    <h3>{{ $wisata->nama }}</h3>
                    <p>{{ $wisata->kategori }}</p>
                    <div class="btn-wrapper">
                        <button class="btn btn-primary btn-lg"
                            wire:click="tampilkanDiPeta({{ $wisata->latitude ?? 'null' }}, {{ $wisata->longitude ?? 'null' }}, @js($wisata->nama ?? 'null'))">
                            Tampilkan di Peta
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
