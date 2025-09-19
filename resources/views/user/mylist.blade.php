@extends('layouts.app')

@section('title', 'My Watchlist - MovieTalks')
<link rel="stylesheet" href="{{ asset('css/mylist.css') }}">

@section('content')
<div class="container">

    <!-- Header -->
    <section class="content-header">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">My Saved Content</h2>
            <div class="sort-group">
                <label for="sort">Sort By:</label>
                <select class="filter-select" id="sort">
                    <option value="recent">Recently Added</option>
                    <option value="rating">Highest Rating</option>
                    <option value="title">Title</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Stats -->
<section class="stats-section mb-4">
    <div class="stats-container d-flex gap-3">
        <div class="stat-item text-center">
            <div class="stat-number" id="stat-total">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Items</div>
        </div>
        <div class="stat-item text-center">
            <div class="stat-number" id="stat-watched">{{ $stats['watched'] }}</div>
            <div class="stat-label">Watched</div>
        </div>
        <div class="stat-item text-center">
            <div class="stat-number" id="stat-unwatched">{{ $stats['unwatched'] }}</div>
            <div class="stat-label">Unwatched</div>
        </div>
    </div>
</section>


    <!-- Tabs -->
    <section class="tabs-section mb-4">
        <div class="tabs d-flex gap-2">
            <a href="{{ route('watchlist.index', ['filter' => 'all']) }}" 
               class="btn filter-btn {{ $filter=='all' ? 'btn-dark' : 'btn-outline-dark' }}" 
               data-filter="all">All Movies</a>
            <a href="{{ route('watchlist.index', ['filter' => 'watched']) }}" 
               class="btn filter-btn {{ $filter=='watched' ? 'btn-dark' : 'btn-outline-dark' }}" 
               data-filter="watched">Watched</a>
            <a href="{{ route('watchlist.index', ['filter' => 'unwatched']) }}" 
               class="btn filter-btn {{ $filter=='unwatched' ? 'btn-dark' : 'btn-outline-dark' }}" 
               data-filter="unwatched">Unwatched</a>
        </div>
    </section>

    <!-- Movies Grid -->
    <section class="movies-grid-section" id="movies-container">
        @include('user.mylist_movies', ['watchlist' => $watchlist])
    </section>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Navigate to movie detail when clicking card (excluding buttons/forms)
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.movie-card');
        if(card && !e.target.closest('form')) {
            const url = card.dataset.url;
            window.location.href = url;
        }
    });

    // Update stats dynamically
    function updateStats(stats) {
        if (!stats) return;
        document.getElementById('stat-total').textContent = stats.total;
        document.getElementById('stat-watched').textContent = stats.watched;
        document.getElementById('stat-unwatched').textContent = stats.unwatched;
    }

    // Bind watch/unwatch/remove forms with AJAX
    function bindForms() {
        document.querySelectorAll('.watchlist-mark, .watchlist-toggle').forEach(form => {
            form.addEventListener('submit', async e => {
                e.preventDefault();
                const action = form.getAttribute('action');
                const token = form.querySelector('input[name="_token"]').value;

                const res = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if (['removed','added','updated'].includes(data.status)) {
                    // Reload only the movies grid
                    fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(res => res.text())
                        .then(html => {
                            document.querySelector('#movies-container').innerHTML = html;
                            bindForms(); // rebind AJAX buttons

                            // Update stats dynamically
                            updateStats(data.stats);
                        });
                }
            });
        });
    }

    bindForms();
});
</script>

@endpush
