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
            <p class="review-form__group-rating--description">
                1: 非常に悪い - 期待外れで、改善が必要です<br>
                2: 悪い - あまり満足していませんが、少し改善の余地があります<br>
                3: 普通 - 期待通りで、特に問題はありませんが、目立った特長もありません<br>
                4: 良い - 期待以上で、満足しています<br>
                5: 非常に良い - 素晴らしく、全体的に非常に満足しています
            </p>
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