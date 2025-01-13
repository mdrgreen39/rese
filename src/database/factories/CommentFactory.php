<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Shop;
use App\Models\Comment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Comment::class;

    public function definition(): array
    {
        // roleが'user'のユーザーIDをランダムに取得
        $users = User::role('user')->pluck('id');

        // 全てのショップIDを取得
        $shops = Shop::pluck('id');

        // ユーザーIDとショップIDをランダムに選択
        $userId = $this->faker->randomElement($users);
        $shopId = $this->faker->randomElement($shops);

        // そのユーザーがそのショップに対して投稿したコメント数を取得
        $commentCount = Comment::where('user_id', $userId)
            ->where('shop_id', $shopId)
            ->count();

        // もしすでに2回コメントがある場合は新しいコメントを生成しない
        if ($commentCount >= 2) {
            return [];  // コメントを生成しないため、空の配列を返す
        }

        // 初期設定
        $image = null;
        $imageName = 'comment_image_' . uniqid() . '.jpg'; // 画像名を定義

        // 画像をランダムに選んでpublic/commentsに保存
        if ($this->faker->boolean(50)) { // 50%の確率で画像を設定
            // images/comments フォルダ内のファイルをランダムに選択
            $randomImage = $this->faker->randomElement(File::files(public_path('images/comments')));
            $sourcePath = $randomImage->getRealPath(); // ランダムに選ばれた画像のパス
            $destinationPath = storage_path('app/public/comments/' . $imageName); // ストレージのパス

            // 画像をコピー
            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $destinationPath);
                $image = 'comments/' . $imageName; // データベースに保存するパス（public/storage経由でアクセス）
            }
        }

        return [
            'user_id' => $userId,
            'shop_id' => $shopId,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->text(),
            'image' => $image,
        ];

    }
}
