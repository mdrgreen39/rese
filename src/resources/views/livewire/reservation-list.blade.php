<div class="mypage-reserve">
    <h3 class="mypage-reserve__heading">予約状況</h3>

    @if(!empty($filteredReservations) && $filteredReservations->count() > 0)

    <a class="mypage-reserve__button-payment" href="{{ route('payment.show') }}">支払う</a>

    @foreach ($filteredReservations as $reservation)
    <div class="mypage-reserve__confirm">
        <div class="mypage-reserve__confirm-top">
            <i class="fa-solid fa-clock"></i>
            <p class="mypage-reserve__confirm-title">予約{{ $loop->iteration }}</p>
            <button class="mypage-reserve__confirm-button--edit" wire:click="toggleEdit({{ $reservation->id }})">編集</button>
            <input type="checkbox" id="toggle-check-{{ $reservation->id }}" wire:click="deleteReservation({{ $reservation->id }})">
            <label for="toggle-check-{{ $reservation->id }}" class="toggle-label">
                <span class="toggle-checkmark"></span>
            </label>
        </div>

        @if ($editingReservationId === $reservation->id)
        <div class="mypage-reserve-form__group">
            <input class="mypage-reserve-form__select-date" type="date" wire:model.live="date" name="date" id="date-{{ $reservation->id }}" min="{{ date('Y-m-d') }}" max="9999-12-31" required novalidate>
            <p class="mypage-reserve-form__error-message">
                @error('date')
                {{ $message }}
                @enderror
            </p>
            <div class="mypage-reserve-form__select-wrapper">
                <select class="mypage-reserve-form__select-time" wire:model.live="time" id="time-{{ $reservation->id }}" name="time" required novalidate>
                    <option value="" disabled selected>
                        予約時間を選択してください
                    </option>
                    @foreach ($times as $timeOption)
                    <option value="{{ $timeOption }}">
                        {{ $timeOption }}
                    </option>
                    @endforeach
                </select>
                </select>
                <i class="fa-solid fa-caret-down fa-xl"></i>
            </div>
            <p class="mypage-reserve-form__error-message">
                @error('time')
                {{ $message }}
                @enderror
            </p>
            <div class="mypage-reserve-form__select-wrapper">
                <select class="mypage-reserve-form__select-number" wire:model.live="people" id="people-{{ $reservation->id }}" name="people" required novalidate>
                    <option value="" disabled selected>
                        予約人数を選択してください
                    </option>
                    @foreach ($numberOfPeople as $number)
                    <option value="{{ $number }}">
                        {{ $number }}人
                    </option>
                    @endforeach
                </select>
                <i class="fa-solid fa-caret-down fa-xl"></i>
            </div>
            <p class="mypage-reserve-form__error-message">
                @error('people')
                {{ $message }}
                @enderror
            </p>
            <button class="mypage-reserve__confirm-button--update" wire:click="updateReservation({{ $reservation->id }})">更新</button>
        </div>

        @endif

        <table class="mypage-reserve__confirm-table">
            @if ($errors->any())
            <div class="mypage-reserve-form__error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <tr>
                <th>Shop</th>
                <td>{{ $reservation->shop->name }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $reservation->date }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ substr($reservation->time, 0, 5) }}</td>
            </tr>
            <tr>
                <th>Number</th>
                <td>{{ $reservation->people }}人</td>
            </tr>
        </table>
        <div class="mypage-reserve__confirm-check">
            <p class="mypage-reserve__confirm-check-text">QRコードを来店時に店舗スタッフに見せてください</p>
            <img class="mypage-reserve__confirm-check-qrcoad" src="{{ $reservation->qr_code_url }}" alt="QRコード">
        </div>
    </div>
    @endforeach
    @else
    <p class="mypage-reserve__status">予約がありません</p>
    @endif
</div>