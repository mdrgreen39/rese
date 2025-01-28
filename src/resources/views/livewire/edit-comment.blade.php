<div>
    <div class="comment">
        <form wire:submit.prevent="updateComment" enctype="multipart/form-data" novalidate>
            <div class="comment-content">
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
                <div class="comment-right">
                    <div class="comment-form">
                        <div class="comment-form__group">
                            <p class="comment-form__group-text">体験を評価してください</p>
                            <div class="stars">
                                @foreach (range(1, 5) as $value)
                                <span class="star {{ $value <= $rating ? 'selected' : '' }}" data-value="{{ $value }}" wire:click="setRating({{ $value }})">★</span>
                                @endforeach
                            </div>
                        </div>
                        @if ($errors->has('rating'))
                        <p class="comment-form__error-message">{{ $errors->first('rating') }}</p>
                        @endif
                        <div class="comment-form__group">
                            <label for="comment" class="comment-form__group-text">口コミを編集</label>
                            <textarea
                                id=" comment"
                                name="comment"
                                placeholder="カジュアルな夜のお出かけにおすすめのスポット"
                                wire:model.live="comment"></textarea>
                            <div class="comment-form__char-count">{{ mb_strlen($comment, 'UTF-8') }}/400(最高文字数)</div>
                        </div>
                        @if ($errors->has('comment'))
                        <p class="comment-form__error-message">{{ $errors->first('comment') }}</p>
                        @endif
                        <div class="image-upload-box" x-data="{
                            imagePreview: @entangle('imagePreview'),
                            handleDrop(event) {
                                const files = event.dataTransfer.files;
                                if (files && files.length > 0) {
                                    const file = files[0];
                                    this.imagePreview = URL.createObjectURL(file);
                                    @this.upload('image', file);
                                }
                            },
                            isDragOver: false,
                            changeImage() {
                                $refs.fileInput.click();
                            }
                        }">
                            @if ($existingImage)
                            <div class="image-preview" @click="changeImage()">
                                <img src="{{ Storage::url($existingImage) }}" alt="Existing Image">
                            </div>
                            @endif
                            <div class="drop-zone"
                                :class="{'drag-over': isDragOver}"
                                @dragover.prevent
                                @dragenter="isDragOver = true"
                                @dragleave="isDragOver = false"
                                @drop.prevent="handleDrop($event)">
                                <div class="drop-zone__text">

                                    <input
                                        type="file"
                                        x-ref="fileInput"
                                        wire:model="image"
                                        accept="image/jpeg,image/png"
                                        style="display:none;" />

                                    <p class="click-upload" @click="$refs.fileInput.click()">クリックして写真を追加</p>
                                    <p class="drag-drop">またはドラッグアンドドロップ</p>
                                </div>

                            </div>
                            @if ($image)
                            <div class="image-preview" @click="changeImage()">
                                <img src="{{ $image->temporaryUrl() }}" alt="Image Preview">
                            </div>
                            @endif
                        </div>
                    </div>
                    @if ($errors->has('image'))
                    <p class="comment-form__error-message">{{ $errors->first('image') }}</p>
                    @endif
                </div>
            </div>
            <div class="comment-send">
                @if (session()->has('error'))
                <div class="comment-send__error-message">
                    {{ session('error') }}
                </div>
                @endif
                <button type="submit" class="comment-send__button">口コミを編集</button>
            </div>
        </form>
    </div>
</div>