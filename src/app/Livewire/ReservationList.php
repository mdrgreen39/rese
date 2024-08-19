<?php

namespace App\Livewire;

use App\Http\Requests\ReservationRequest;
use Livewire\Component;
use App\Models\Reservation;
use Illuminate\Support\Facades\Validator;


class ReservationList extends Component
{
    public $reservations = [];
    public $editingReservationId = null;
    public $times = [];
    public $numberOfPeople = [];
    public $date;
    public $time;
    public $people;

    public function mount()
    {
        $this->reservations = auth()->user()->reservations()->with('shop')->get()->keyBy('id');
        $this->generateTimeOptions('11:00', '21:00', 30);// 時間のオプションを設定
        $this->numberOfPeople = range(1, 10);
    }

    public function generateTimeOptions($startTime, $endTime, $interval)
    {
        $current = new \DateTime($startTime);
        $end = new \DateTime($endTime);

        while ($current <= $end) {
            $this->times[] = $current->format('H:i');
            $current->add(new \DateInterval('PT' . $interval . 'M'));
        }
    }

    public function toggleEdit($reservationId)
    {
        if ($this->editingReservationId === $reservationId) {
            $this->editingReservationId = null;
        } else {
            $this->editingReservationId = $reservationId;

            $this->defaultReservationValues($reservationId);
        }
    }

    private function defaultReservationValues($reservationId)
    {
        $reservation = $this->reservations->get($reservationId);

        // 現在の予約時間や人数をデフォルト値として設定
        $this->date = $reservation->date;
        $this->time = substr($reservation->time, 0, 5);
        $this->people = $reservation->people;
    }

public function rules(): array
    {
        $now = new \DateTime();
        $currentTime = $now->format('H:i');
        $currentDate = $now->format('Y-m-d');

        return [
            'date' => ['required', 'date'],
            'time' => ['required', 'string', 'date_format:H:i', function ($attribute, $value, $fail) use ($currentTime, $currentDate) {
                if ($this->date === $currentDate && $value < $currentTime) {
                    $fail('当日の予約時間は現在時刻を過ぎているため、予約できません。');
                }
            }],
            'people' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'date.required' => '日付を選択してください',
            'date.date' => '有効な日付を選択してください',
            'time.required' => '時間を選択してください',
            'time.string' => '時間は文字列として入力してください',
            'time.date_format' => '時間は24時間形式で HH:MM で入力してください',
            'people.required' => '人数を選択してください',
            'people.integer' => '人数は整数で入力してください',
            'people.min' => '人数は少なくてとも1人である必要があります',
        ];
    }

    public function updateReservation($reservationId)
    {
        // バリデーションの適用
        $this->validate();

        // レコードの更新処理
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->date = $this->date;
        $reservation->time = $this->time;
        $reservation->people = $this->people;
        $reservation->save();

        // 編集モードを終了
        $this->editingReservationId = null;

        return redirect()->route('reservation.updated');
    }













    public function deleteReservation($reservationId)
    {
        // Reservation を削除
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->delete();

        // 削除後、予約リストを更新
        $this->reservations = auth()->user()->reservations()->with('shop')->get();

       // リダイレクトして deleted.blade.php を表示
        return redirect()->route('reservation.deleted');
    }


    public function render()
    {
        return view('livewire.reservation-list', [
            'reservations' => $this->reservations,
            'times' => $this->times,
            'numberOfPeople' => $this->numberOfPeople,
        ]);
    }
}
