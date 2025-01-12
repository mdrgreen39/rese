<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    // コメント投稿ページ表示
    public function showCommentForm($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        $prefecture = $shop->prefecture;
        $genre = $shop->genre;

        $rating = 0;
        $comment = ''; // 初期値
        $image = null;


        return view('comment', compact('shop', 'prefecture', 'genre','rating', 'comment', 'image'));
    }
   

    // コメント投稿完了ページ表示
    public function commentSuccess($shop_id)
    {
        // コメントを投稿した店舗を取得
        $shop = Shop::findOrFail($shop_id);

        // コメント送信完了ページを表示し、店舗情報を渡す
        return view('comment-success', compact('shop'));
    }

    // 編集ページを表示
    public function editComment($id, $shop_id, $comment)
    {
        // 権限チェック
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'このコメントを削除する権限がありません');
        }

        // コメントの情報を取得
        $comment = Comment::findOrFail($id);
        $rating = $comment->rating;
        $existingImage = $comment->image; // 既存の画像データ

        // コメントから関連する店舗情報を取得
        $shop = Shop::findOrFail($shop_id);
        $prefecture = $shop->prefecture;
        $genre = $shop->genre;

        // 既存の画像データを設定
        $existingImage = $comment->image ? url('storage/' . $comment->image) : null;

        $value = 'some_value';


        // 編集ページに必要な情報を渡す
        return view('comment-edit', compact('shop', 'prefecture', 'genre', 'rating', 'comment', 'existingImage'));
    }

    // コメント編集完了ページ表示
    public function commentUpdate($shop_id)
    {
        // コメントを投稿した店舗を取得
        $shop = Shop::findOrFail($shop_id);

        

        // コメント送信完了ページを表示し、店舗情報を渡す
        return view('comment-update-success', compact('shop'));
    }

    // コメントの削除
    public function destroyComment(Comment $comment)
    {
        // 権限チェック
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'このコメントを削除する権限がありません');
        }

        // 画像が存在する場合は削除
        if ($comment->image) {
            $disk = app()->environment('production') ? 's3' : 'public';
            Storage::disk($disk)->delete($comment->image);
        }

        // コメントを削除
        $comment->delete();

        // 成功メッセージを表示してリダイレクト
        return redirect()->route('comment.delete', ['comment' => $comment->id, 'shop_id' => $comment->shop_id]);
    }


    // コメント削除完了ページ表示
    public function commentDelete($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        return view('comment-delete', compact('shop'));
    }

}
