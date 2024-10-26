<div class="shop">
    <div class="shop-container">
        @if($shops->isEmpty())
        <p class="no-results__text">該当する店舗が見つかりませんでした</p>
        @else
        <div class="shop-wrap">
            @foreach($shops as $shop)
            <div class="shop-wrap__item">
                <img class="shop-wrap__item-photo" src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
                <div class="shop-wrap__item-content">
                    <h2 class="shop-wrap__item-name">{{ $shop->name }}</h2>
                    <ul class="shop-wrap__item-tag-container">
                        <li class="shop-wrap__item-tag">#{{ $shop->prefecture->name }}</li>
                        <li class="shop-wrap__item-tag">#{{ $shop->genre->name }}</li>
                    </ul>
                    <div class="shop-wrap__item-container">
                        <a class="shop-wrap__item-button" href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}">詳しく見る</a>

                        @if(auth()->check())
                        @livewire('favorite-toggle', ['shop' => $shop], key('favorite-toggle-' . $shop->id . '-' . $loop->index))

                        @else

                        <form action="/register" method="post" style="display: inline;">
                            @csrf
                            <input type="hidden" name="message" value="お気に入りに追加するには会員登録が必要です。<br>すでに会員登録済みの方は<a href='/login'>こちら</a>からログインしてください。">
                            <button type="submit" class="heart-toggle-wrapper" style="background: none; border: none;">
                                <span class="heart-icon"></span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>