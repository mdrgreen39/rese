<div class="reserve-form">
    <h2 class="reserve-form__heading">予約</h2>
    <form class="reserve-form__form" action="{{ route('reservations.store', ['shop' => $shop->id] )}}" method="post" novalidate>
        @csrf
        <div class="reserve-form__group">

            <input class="reserve-form__select-date" type="date" wire:model.live="date" name="date" id="date" min="{{ date('Y-m-d') }}" max="9999-12-31" value="" required novalidate>
            <p class="reserve-form__error-message">
                @error('date')
                {{ $message }}
                @enderror
            </p>
            <div class="reserve-form__select-wrapper">
                <select class="reserve-form__select-time" wire:model.live="time" id="time" name="time" required novalidate>
                    <option value="" disabled selected>予約時間を選択してください</option>
                    @foreach ($times as $timeOption)
                    <option value="{{ $timeOption }}">{{ $timeOption }}
                    </option>
                    @endforeach
                </select>
                <img class="reserve-form__caretdown-icon" src="/images/icons/icon_caretdown.svg" alt="caretdown_icon">
            </div>

            <p class="reserve-form__error-message">
                @error('time')
                {{ $message }}
                @enderror
            </p>
            <div class="reserve-form__select-wrapper">
                <select class="reserve-form__select-number" wire:model.live="people" id="people" name="people" required novalidate>
                    <option value="" disabled selected>予約人数を選択してください</option>
                    @foreach ($numberOfPeople as $number)
                    <option value=" {{ $number }} ">{{ $number }}人</option>
                    @endforeach
                </select>
                <img class="reserve-form__caretdown-icon" src="/images/icons/icon_caretdown.svg" alt="caretdown_icon">
            </div>
            <p class="reserve-form__error-message">
                @error('people')
                {{ $message }}
                @enderror
            </p>

        </div>

        <div class="reserve-form__confirm">
            <table class="reserve-form__confirm-table">
                <tr>
                    <th>Shop</th>
                    <td>{{ $shop->name }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ $date }}</td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td>{{ $time }}</td>
                </tr>
                <tr>
                    <th>Number</th>
                    <td>{{ $people }}</td>
                </tr>
            </table>

        </div>
        <div class="reserve-form__button-container">
            <button class="reserve-form__button" type="submit">予約する</button>
        </div>

    </form>
</div>