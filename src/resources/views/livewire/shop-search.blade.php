<div class="header-search-container">
    <div class="header-search-form">
        <div class="header-search__select-group">
            <div class="header-search__select-wrapper">
                <select class="header-search__select" wire:model.live="prefectureId" name="prefecture_id" id="prefecture_id">
                    <option value="">All area</option>
                    @foreach($prefectures as $prefecture)
                    <option value="{{ $prefecture->id }}">{{ $prefecture->name }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-caret-down fa-xl"></i>
            </div>
            <div class="header-search__select-wrapper">
                <select class="header-search__select" wire:model.live="genreId" name="genre_id" id="genre_id">
                    <option value="">All genre</option>
                    @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-caret-down fa-xl"></i>
            </div>
        </div>
        <div class="header-search__input-group">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input class="header-search__input" wire:model.live.debounce.300ms="searchTerm" type="text" name="search" placeholder="Search ...">
        </div>
    </div>
</div>