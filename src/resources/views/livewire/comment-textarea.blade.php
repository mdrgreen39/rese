<div>
    <textarea
        id=" comment"
        name="comment"
        placeholder="カジュアルな夜のお出かけにおすすめのスポット"
        wire:model.live="comment"></textarea>
    <div class="comment-form__char-count">{{ mb_strlen($comment, 'UTF-8') }}/400(最高文字数)</div>
</div>