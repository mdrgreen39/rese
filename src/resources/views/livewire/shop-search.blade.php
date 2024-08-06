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
                <img class="header-search__caretdown-icon" src="/images/icons/icon_caretdown.svg" alt="caretdown_icon">
            </div>
            <div class="header-search__select-wrapper">
                <select class="header-search__select" wire:model.live="genreId" name="genre_id" id="genre_id">
                    <option value="">All genre</option>
                    @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
                <img class="header-search__caretdown-icon" src="/images/icons/icon_caretdown.svg" alt="caretdown_icon">
            </div>
        </div>

        <div class="header-search__input-group">
            <img class="header-search__search-icon" src="/images/icons/icon_search.svg" alt="search_icon"></button>
            <input class="header-search__input" wire:model.live.debounce.300ms="searchTerm" type="text" name="search" placeholder="Search ...">
        </div>

    </div>
</div>