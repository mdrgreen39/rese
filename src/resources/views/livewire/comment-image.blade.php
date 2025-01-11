<div class="image-upload-box" x-data="{
    imagePreview: null,
    handleDrop(event) {
        const files = event.dataTransfer.files;
        if (files && files.length > 0) {
            const file = files[0];
            this.imagePreview = URL.createObjectURL(file);
            @this.upload('image', file);
        }
    },
    isDragOver: false
}">
    <input
        type="file"
        id="image-upload"
        x-ref="fileInput"
        wire:model="image"
        accept="image/jpeg,image/png"
        style="display:none" />
    <p @click="$refs.fileInput.click()">クリックして写真を追加</p>

    <div
        class="drop-zone"
        :class="{'drag-over': isDragOver}"
        @dragover.prevent
        @dragenter="isDragOver = true"
        @dragleave="isDragOver = false"
        @drop.prevent="handleDrop($event)">
        <p class="drag-drop">またはドラッグアンドドロップ</p>
    </div>

    @if ($image)
    <div class="image-preview">
        <img src="{{ $image->temporaryUrl() }}" alt="Image Preview" style="max-width: 300px; max-height: 300px;">
    </div>
    @endif
</div>