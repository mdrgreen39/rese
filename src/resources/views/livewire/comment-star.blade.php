<div>
    <div class="stars">
        @foreach (range(1, 5) as $value)
        <span class="star {{ $value <= $rating ? 'selected' : '' }}" data-value="{{ $value }}" wire:click="setRating({{ $value }})">★</span>
        @endforeach
    </div>
</div>