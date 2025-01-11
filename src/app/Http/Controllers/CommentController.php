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

    public function storeComment(CommentRequest $request, $shopId)
    {
        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->shop_id = $shopId;
        $comment->comment = $request->comment;
        $comment->rating = $request->rating;

        if ($request->hasFile('image')) {
            if (app()->environment('production')) {
                $comment->image = $request->file('image')->store('comments', 's3');
            } else {
                $comment->image = $request->file('image')->store('comments', 'public');
            }
        } elseif ($request->filled('image_url')) {
            $url = $request->input('image_url');
            $fileName = basename($url);
            $fileContents = file_get_contents($url);
            $comment->image = 'comments/' . $fileName;

            if (app()->environment('production')) {
                Storage::disk('s3')->put($comment->image, $fileContents);
            } else {
                Storage::disk('public')->put($comment->image, $fileContents);
            }
        }

        $comment->save();

        return redirect()->route('comment.success');
    }


    // コメントの削除
    public function destroyComment(Comment $comment)
    {
        // ポリシーで削除権限を確認
        $this->authorize('delete', $comment);

        // 画像が存在する場合は削除
        if ($comment->image) {
            if (app()->environment('production')) {
                Storage::disk('s3')->delete($comment->image);
            } else {
                Storage::disk('public')->delete($comment->image);
            }
        }

        // コメントを削除
        $comment->delete();

        // 成功メッセージを表示してリダイレクト
        return redirect()->route('comment.delete');
    }

    // コメント投稿完了ページ表示
    public function commentSuccess()
    {
        return view('comment-success');
    }

    // コメント削除完了ページ表示
    public function commentDelete()
    {
        return view('comment-delete');
    }

}
