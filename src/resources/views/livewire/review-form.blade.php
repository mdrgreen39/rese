<div>
    @if($canReview)
    <button class="review-form__button" wire:click="toggleForm">
        レビューを書く
    </button>

    @if($showForm)
    <form class="review-form" method="post" wire:submit.prevent="submit" novalidate>
        @csrf
        <div class="review-form__group">
            <label class="review-form__group-rating" for="rating">評価:</label>
            <input class="review-form__group-rating--input" type="number" id="rating" name="rating" min="1" max="5" wire:model="rating" required>
            <p class="review-form__error-message">
                @error('rating')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="review-form__group">
            <label class="review-form__group-comment" for="comment">コメント:</label>
            <textarea class="review-form__group-comment--text" id="comment" name="comment" wire:model="comment" maxlength="500"></textarea>
            <p class="review-form__error-message">
                @error('comment')
                {{ $message }}
                @enderror
            </p>
        </div>
        <button class="review-form__button" type="submit">送信</button>
    </form>
    @endif
    @endif
</div>