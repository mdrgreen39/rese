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

    <div class="drop-zone"
        :class="{'drag-over': isDragOver}"
        @dragover.prevent
        @dragenter="isDragOver = true"
        @dragleave="isDragOver = false"
        @drop.prevent="handleDrop($event)">

        <input
            type="file"
            x-ref="fileInput"
            wire:model="image"
            accept="image/jpeg,image/png"
            style="display:none;" />

        <div class="drop-zone__text">
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