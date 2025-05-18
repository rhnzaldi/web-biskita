<div>
    <div class="owl-carousel owl-all">
        @foreach ($artikels as $artikel)
        <div class="service text-center">
            <a href="#"><img src="{{ asset('storage/article/'.$artikel->image) }}" alt="Image" class="img-fluid"></a>
            <div class="px-md-3">
                <h3><a href="#">{{ $artikel->judul }}</a></h3>
                <p>{{$artikel->deskripsi}}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
