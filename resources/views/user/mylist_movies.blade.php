<div class="row">
    @forelse($watchlist as $item)
        <div class="col-md-3 mb-4">
            <div class="card h-100 movie-card" data-url="{{ route('movie.detail', $item->movie->id) }}">
                <img src="{{ asset('storage/' . $item->movie->poster) }}" 
                     class="card-img-top" 
                     alt="{{ $item->movie->title }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $item->movie->title }}</h5>
                    <p class="card-text">{{ Str::limit($item->movie->description, 80) }}</p>
                    <p class="small text-muted">{{ $item->movie->release_year }} • {{ $item->movie->category->name }}</p>

                    <div class="mt-auto d-flex justify-content-between gap-2">
                        <!-- Mark/Unmark Watched -->
                        <form action="{{ route('watchlist.mark', $item->id) }}" method="POST" class="watchlist-mark">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-dark btn-sm">
                                {{ $item->watched ? 'Already Watched' : 'Not Watched' }}
                            </button>
                        </form>

                        <!-- Remove from Watchlist -->
                        <form action="{{ route('watchlist.toggle', $item->movie->id) }}" method="POST" class="watchlist-toggle">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-dark btn-sm">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <p>No movies in your watchlist.</p>
        </div>
    @endforelse
</div>
