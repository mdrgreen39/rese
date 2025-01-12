<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        $users = User::where('role', 'user')->pluck('id');
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

        // 画像はpublic/commentsに保存されると仮定
        $image = $this->faker->randomElement([
            null, // 画像なし
            'comments/' . $this->faker->image(storage_path('app/public/comments'), 640, 480, null, false), // ランダムな画像
        ]);

        return [
            'user_id' => $userId,
            'shop_id' => $shopId,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->text(),
            'image' => $image,
        ];
    }
}
