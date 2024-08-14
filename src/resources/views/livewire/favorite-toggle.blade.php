<div>
    <input type="checkbox" id="toggle-heart-{{ $shop->id }}" wire:click="toggleFavorite" class="heart-toggle" {{ $isFavorited ? 'checked' : '' }}>
    <label for="toggle-heart-{{ $shop->id }}" class="heart-icon"></label>
</div>