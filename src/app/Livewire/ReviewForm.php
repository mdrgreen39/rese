<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewForm extends Component
{
    public $shop;
    public $showForm = false;
    public $rating;
    public $comment;

    public function rules(): array
    {
        return [
        'rating' => ['required', 'integer', 'min:1', 'max:5'],
        'comment' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価は必須項目です',
            'rating.integer' => '評価は整数でなければなりません',
            'rating.max' => '評価は最大5までです',
            'comment.required' => 'コメントを入力してください',
            'comment.string' => 'コメントは文字列でなければなりません',
            'comment.max' => 'コメントは最大500文字までです',
        ];
    }

    public function mount($shop)
    {
        $this->shop = $shop;
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
    }

    public function submit()
    {
        $this->validate();

        $reservations = Auth::user()->reservations()
            ->where('shop_id', $this->shop->id)
            ->get();

        $canReview = $reservations->contains(function ($reservation) {
            return $reservation->can_review && $reservation->visited_at !== null;
        });

        if (!$canReview) {
            session()->flash('error', 'レビューを投稿する権限がありません。');
            return;
        }

        try {
            DB::beginTransaction();

            Review::create([
                'user_id' => Auth::id(),
                'shop_id' => $this->shop->id,
                'rating' => $this->rating,
                'comment' => $this->comment,
            ]);

            $reservations->each(function ($reservation) {
                if ($reservation->can_review) {
                    $reservation->can_review = false;
                    $reservation->save();
                }
            });

            DB::commit();

            $this->reset(['rating', 'comment', 'showForm']);

            return redirect()->route('review.success', ['shop' => $this->shop->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', '予期しないエラーが発生しました。');
            return;
        }
    }

    public function render()
    {
        $canReview = Auth::check() && Auth::user()->reservations()
            ->where('shop_id', $this->shop->id)
            ->where('can_review', true)
            ->whereNotNull('visited_at')
            ->exists();

        return view('livewire.review-form', [
            'canReview' => $canReview,
        ]);
    }

}



