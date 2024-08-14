<div class="shop">
    <div class="shop-container">
        @if($shops->isEmpty())
        <p class="result__text">該当する店舗が見つかりませんでした</p>
        @else
        <div class="shop-wrap">
            @foreach($shops as $shop)
            <div class="shop-wrap__item">
                <img class="shop-wrap__item-photo" src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">{{ $shop->name }}</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#{{ $shop->prefecture->name }}</li>
                        <li class="shop-wrap__item-tag">#{{ $shop->genre->name }}</li>
                    </ul>

                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}">詳しく見る</a>

                        
                        @livewire('favorite-toggle', ['shop' => $shop], key('favorite-toggle-' . $shop->id))
                        
                        <!-- <a class="heart-toggle-wrapper" href="/register"> -->
                            <!-- <label class="heart-icon"></label></a> -->
                    
                    </div>

                </div>
            </div>
            @endforeach

        </div>
        
    </div>
    @endif
</div>