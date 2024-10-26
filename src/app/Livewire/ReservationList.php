<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Jobs\GenerateQrCode;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;


class ReservationList extends Component
{
    public $reservations = [];
    public $editingReservationId = null;
    public $filteredReservations = [];
    public $times = [];
    public $numberOfPeople = [];
    public $date;
    public $time;
    public $people;

    public function mount()
    {
        $this->reservations = auth()->user()->reservations()->with('shop')->get()->keyBy('id');
        $this->filteredReservations = $this->reservations->filter(function ($reservation) {
            return $reservation->date > now()->toDateString() ||
                ($reservation->date === now()->toDateString() && $reservation->time >= now()->toTimeString());
        })->sortBy(function ($reservation) {
            return [$reservation->date, $reservation->time];
        })->values();
        $this->generateTimeOptions('11:00', '21:00', 30);
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
        $this->validate();

        $reservation = Reservation::findOrFail($reservationId);
        $reservation->date = $this->date;
        $reservation->time = $this->time;
        $reservation->people = $this->people;

        if ($reservation->qr_code_path) {
            if (app()->environment('production')) {
                Storage::disk('s3')->delete($reservation->qr_code_path);
            } else {
                Storage::disk('public')->delete($reservation->qr_code_path);
            }
        }

        $reservation->save();

        GenerateQrCode::dispatch($reservation);

        $this->editingReservationId = null;

        // 更新後にフィルタリングされた予約を再取得
        $this->filteredReservations = auth()->user()->reservations()->with('shop')->get()->filter(function ($reservation) {
            return $reservation->date > now();
        })->values();

        return redirect()->route('reservation.updated');
    }

    public function deleteReservation($reservationId)
    {
        \DB::beginTransaction();

        try {
            $reservation = Reservation::findOrFail($reservationId);

            $reservation->delete();

            if ($reservation->qr_code_path) {
                if (app()->environment('production')) {
                    Storage::disk('s3')->delete($reservation->qr_code_path);
                } else {
                    Storage::disk('public')->delete($reservation->qr_code_path);
                }
            }

            \DB::commit();

            $this->filteredReservations = auth()->user()->reservations()->with('shop')->get()->filter(function ($reservation) {
                return $reservation->date > now();
            })->values();

            return redirect()->route('reservation.deleted');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withErrors(['error' => '予約削除に失敗しました。']);
        }
    }

    public function render()
    {
        return view('livewire.reservation-list', [
            'reservations' => $this->filteredReservations,
            'times' => $this->times,
            'numberOfPeople' => $this->numberOfPeople,
        ]);
    }
}
