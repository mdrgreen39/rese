<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Shop;
use App\Models\Comment;


class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    // 口コミ投稿ページ表示
    public function showCommentForm($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        $prefecture = $shop->prefecture;
        $genre = $shop->genre;

        $rating = 0;
        $comment = '';
        $image = null;

        $existingComment = Comment::where('user_id', auth()->id())
            ->where('shop_id', $shop_id)
            ->exists();

        if ($existingComment) {
            // コメント投稿済みの場合はエラーメッセージを表示して店舗詳細ページにリダイレクト
            return redirect()->route('shop.detail', ['shop_id' => $shop_id])
                ->with('error', 'この店舗には既にコメントを投稿しています');
        }

        return view('comment', compact('shop', 'prefecture', 'genre','rating', 'comment', 'image'));
    }

    // 口コミ投稿完了ページ表示
    public function commentSuccess($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        return view('comment-success', compact('shop'));
    }

    // 口コミ編集ページ表示
    public function editComment(Comment $comment, $shop_id)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'このコメントを削除する権限がありません');
        }

        $rating = $comment->rating;
        $existingImage = $comment->image ? $comment->image : null;

        $shop = Shop::findOrFail($shop_id);
        $prefecture = $shop->prefecture;
        $genre = $shop->genre;

        return view('comment-edit', compact('shop', 'prefecture', 'genre', 'rating', 'comment', 'existingImage'));
    }


    // 口コミ更新完了ページ表示
    public function commentUpdate($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        return view('comment-update-success', compact('shop'));
    }

    // 口コミ削除処理
    public function destroyComment(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'このコメントを削除する権限がありません');
        }

        if ($comment->image) {
            $disk = app()->environment('production') ? 's3' : 'public';
            Storage::disk($disk)->delete($comment->image);
        }

        $comment->delete();

        return redirect()->route('comment.delete', ['comment' => $comment->id, 'shop_id' => $comment->shop_id]);
    }


    // 口コミ削除完了ページ表示
    public function commentDelete($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        return view('comment-delete', compact('shop'));
    }

}
