<div>
    <textarea
        id=" comment"
        name="comment"
        placeholder="カジュアルな夜のお出かけにおすすめのスポット"
        maxlength="400"
        wire:model.live="comment"></textarea>
    <div class="comment-form__char-count">{{ strlen($comment) }}/400(最高文字数)</div>
</div>