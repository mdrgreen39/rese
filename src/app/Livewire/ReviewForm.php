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
        'comment' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価は必須項目です',
            'rating.integer' => '評価は整数でなければなりません',
            'rating.max' => '評価は最大5までです',
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
        // バリデーションを試みる
        $this->validate();

        // 予約データを取得し、レビューが可能か確認
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
            // トランザクション開始
            DB::beginTransaction();

            // レビューを作成
            Review::create([
                'user_id' => Auth::id(),
                'shop_id' => $this->shop->id,
                'rating' => $this->rating,
                'comment' => $this->comment,
            ]);

            // 予約の can_review を false にする
            $reservations->each(function ($reservation) {
                if ($reservation->can_review) {
                    $reservation->can_review = false;
                    $reservation->save();
                }
            });

            // トランザクションをコミット
            DB::commit();

            // フォームのリセット
            $this->reset(['rating', 'comment', 'showForm']);

            // リダイレクト
            return redirect()->route('review.success', ['shop' => $this->shop->id]);
        } catch (\Throwable $e) {
            // エラー発生時の処理
            DB::rollBack(); // トランザクションのロールバック
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



