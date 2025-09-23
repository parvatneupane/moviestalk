@foreach($reviews as $review)
<div class="review-card border p-3 mb-3 rounded d-flex gap-3 align-items-start">
    <img src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : asset('images/default-avatar.png') }}" 
         alt="{{ $review->user->name }}'s Avatar"
         class="rounded-circle" style="width:50px; height:50px; object-fit:cover;">

    <div class="flex-grow-1">
        <div class="review-header mb-1 d-flex justify-content-between align-items-center">
            <strong>{{ $review->user->name }}</strong>
            <span style="font-size:0.85rem;">{{ $review->created_at->format('F j, Y') }}</span>
        </div>

        <div class="review-rating mb-1">
            @php
                $userRating = $allRatings->has($review->user->id) 
                    ? $allRatings[$review->user->id]->rating 
                    : null;
            @endphp

            @if($userRating)
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star" style="color: {{ $i <= $userRating ? '#ffc107' : '#ccc' }}"></i>
                @endfor
                <span style="font-size:0.85rem; color:#ffffff;">({{ $userRating }}/5)</span>
            @else
                <span style="font-size:0.85rem; color:#ffffff;">Rating: N/A</span>
            @endif
        </div>

        <div class="review-content">
            <p class="mb-0">{{ $review->review }}</p>
        </div>
    </div>
</div>
@endforeach

