<div class="store-register-form__group">
    <div class="store-register-form__input-container">
        <i class="fa-solid fa-house fa-xl"></i>
        <input class="store-register-form__input" type="text" name="name" id="name" placeholder="店名を入力してください" value="{{ old('name', $shop->name ?? '') }}" required>
    </div>
    <p class="store-register-form__error-message">
        @error('name')
        {{ $message }}
        @enderror
    </p>
</div>
<div class="store-register-form__group">
    <div class="store-register-form__input-container">
        <i class="fa-solid fa-location-dot fa-xl"></i>
        <select class="store-register-form__select" name="prefecture_id" id="prefecture_id" required>
            <option value="" selected disabled>所在地域を選択してください</option>
            @foreach($prefectures as $prefecture)
            <option value="{{ $prefecture->id }}" {{ old('prefecture_id', $shop->prefecture_id ?? '') == $prefecture->id ? 'selected' : '' }}>
                {{ $prefecture->name }}
            </option>
            @endforeach
        </select>
        <i class="fa-solid fa-caret-down fa-xl"></i>
    </div>
    <p class="store-register-form__error-message">
        @error('prefecture_id')
        {{ $message }}
        @enderror
    </p>
</div>
<div class="store-register-form__group">
    <div class="store-register-form__input-container">
        <i class="fa-solid fa-tag fa-xl"></i>
        <select class="store-register-form__select" name="genre_id" id="genre_id" required>
            <option value="" selected disabled>ジャンルを選択してください</option>
            @foreach($genres as $genre)
            <option value="{{ $genre->id }}" {{ old('genre_id', $shop->genre_id ?? '') == $genre->id ? 'selected' : '' }}>
                {{ $genre->name }}
            </option>
            @endforeach
        </select>
        <i class="fa-solid fa-caret-down fa-xl"></i>
    </div>
    <p class="store-register-form__error-message">
        @error('genre_id')
        {{ $message }}
        @enderror
    </p>
</div>
<div class="store-register-form__group">
    <div class="store-register-form__input-container">
        <i class="fa-solid fa-pen fa-xl"></i>
        <textarea class="store-register-form__textarea" name="description" id="description" placeholder="店舗概要を500文字以内で入力してください" required>{{ old('description', $shop->description ?? '') }}</textarea>
    </div>
    <p class="store-register-form__error-message">
        @error('description')
        {{ $message }}
        @enderror
    </p>
</div>
<div class="store-register-form__group">
    <div class="store-register-form__input-container">
        <i class="fa-regular fa-image fa-xl"></i>
        <input class="store-register-form__image" type="file" name="image" id="image">
    </div>
    <p class="store-register-form__error-message">
        @error('image')
        {{ $message }}
        @enderror
    </p>
    <div class="store-register-form__input-container">
        <input class="store-register-form__image-url" type="text" name="image_url" id="image_url" placeholder="または画像URLを入力してください" value="{{ old('image', $shop->image ?? '') }}">
    </div>
    <p class="store-register-form__error-message">
        @error('image_url')
        {{ $message }}
        @enderror
    </p>
    <p class="store-register-form__error-message">
        @error('image_or_image_url')
        {{ $message }}
        @enderror
    </p>
</div>