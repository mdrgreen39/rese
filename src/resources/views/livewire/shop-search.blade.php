<div class="header-search-container flex flex-col flex flex-col sm:flex-row-reverse sm:space-x-4">

    <div class=" header-search-form">
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

    @auth
    <div x-data="{ sortBy: @entangle('sortBy'), showMenu: false }" class="relative inline-block">
        <button id="sort-button" @click="showMenu = !showMenu" class="dropdown-button">
            並び替え：<span x-text="sortBy === 'random' ? 'ランダム' : (sortBy === 'high_rating' ? '評価高/低' : '評価低/高')"></span>
        </button>

        <div x-show="showMenu"
            @click.away="showMenu = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="dropdown-menu">
            <ul>
                <li>
                    <button @click="sortBy = 'random'; showMenu = false; $wire.updateSortBy(sortBy)"
                        :class="{ 'selected': sortBy === 'random' }"
                        class="menu-item menu-item__first">
                        ランダム
                    </button>
                </li>
                <li>
                    <button @click="sortBy = 'high_rating'; showMenu = false; $wire.updateSortBy(sortBy)"
                        :class="{ 'selected': sortBy === 'high_rating' }"
                        class="menu-item">
                        評価が高い順
                    </button>
                </li>
                <li>
                    <button @click="sortBy = 'low_rating'; showMenu = false; $wire.updateSortBy(sortBy)"
                        :class="{ 'selected': sortBy === 'low_rating' }"
                        class="menu-item menu-item__last">
                        評価が低い順
                    </button>
                </li>
            </ul>
        </div>
    </div>
    @endauth
</div>