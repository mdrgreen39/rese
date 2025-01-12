<div>
    <div class="comment">
        <form wire:submit.prevent="submit" enctype="multipart/form-data" novalidate>
            <div class="comment-content">
                <!-- 左側の店舗情報 -->
                <div class="comment-left">
                    <div class="comment-shop">
                        <h2 class="comment-shop__heading">今回のご利用はいかがでしたか</h2>

                        <div class="comment-shop-container">

                            <div class="comment-shop-wrap__item">
                                <img class="comment-shop-wrap__item-photo" src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
                                <div class="comment-shop-wrap__item-content">
                                    <h3 class="comment-shop-wrap__item-name">{{ $shop->name }}</h3>
                                    <ul class="comment-shop-wrap__item-tag-container">
                                        <li class="comment-shop-wrap__item-tag">#{{ $shop->prefecture->name }}</li>
                                        <li class="comment-shop-wrap__item-tag">#{{ $shop->genre->name }}</li>
                                    </ul>
                                    <div class="comment-shop-wrap__item-container">
                                        <a class="comment-shop-wrap__item-button" href="{{ route('shop.detail', ['shop_id' => $shop->id]) }}">詳しく見る</a>

                                        @livewire('favorite-toggle', ['shop' => $shop], key('favorite-toggle-' . $shop->id))

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- 右側の評価フォーム -->
                <div class="comment-right">
                    <div class="comment-form">
                        <!-- 星評価 -->
                        <div class="comment-form__group">
                            <p class="comment-form__group-text">体験を評価してください</p>
                            @livewire('comment-star', ['rating' => $rating, 'isEditing' => false], key('comment-star'))
                        </div>
                        @if ($errors->has('rating'))
                        <p class="comment-form__error-message">{{ $errors->first('rating') }}</p>
                        @endif

                        <!-- 口コミ入力フォーム -->
                        <div class="comment-form__group">
                            <label for="comment" class="comment-form__group-text">口コミを投稿</label>
                            @livewire('comment-textarea')

                        </div>
                        @if ($errors->has('comment'))
                        <p class="comment-form__error-message">{{ $errors->first('comment') }}</p>
                        @endif

                        <!-- 画像追加 -->
                        <div class="comment-form__group">
                            <label for="image-upload" class="comment-form__group-text">画像の追加</label>
                            @livewire('comment-image', key('comment-image'))
                        </div>
                    </div>
                    @if ($errors->has('image'))
                    <p class="comment-form__error-message">{{ $errors->first('image') }}</p>
                    @endif

                </div>
            </div>
            <!-- 投稿ボタン -->
            <div class="comment-send">
                @if (session()->has('error'))
                <div class="comment-send__error-message">
                    {{ session('error') }}
                </div>
                @endif
                <button type="submit" class="comment-send__button">口コミを投稿</button>
            </div>
        </form>
    </div>
</div>