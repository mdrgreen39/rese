<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Comment;
use App\Jobs\SendNotificationEmail;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\SendNotificationRequest;
use App\Imports\ShopImport;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // 管理者画面ページ表示
    public function index()
    {
        return view('admin.admin-dashboard');
    }

    // 店舗代表登録画面ページ表示
    public function showRegisterForm()
    {
        $roles = Role::where('name', 'store_manager')->get();

        return view('admin.owner-register', [
            'roles' => $roles,
        ]);
    }

    // 店舗代表者登録処理
    public function store(AdminRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        $user->sendEmailVerificationNotification();

        return redirect()->route('admin.ownerRegisterDone');
    }

    // 店舗代表者登録完了ページ表示
    public function registerDone()
    {
        return view('admin.owner-register-done');
    }

    // お知らせメール送信画面表示
    public function showNotificationForm()
    {
        $users = User::all();
        $roles = Role::all();

        return view('admin.email-notification', compact('users', 'roles'));
    }

    // お知らせメール送信処理
    public function sendNotification(SendNotificationRequest $request)
    {
        $validated = $request->validated();

        $users = collect();

        if (!empty($validated['users'])) {
            $users = User::whereIn('id', $validated['users'])->get();
        }

        if (!empty($validated['users'])) {
            $users = User::whereIn('id', $validated['users'])->get();
        }

        if (!empty($validated['roles'])) {
            $roleUsers = User::whereHas('roles', function ($query) use ($validated) {
                $query->where('id', $validated['roles']);
            })->get();

            $users = $users->merge($roleUsers)->unique('id');
        }

        foreach ($users as $user) {
            if ($user->email) {
                SendNotificationEmail::dispatch($user->email, $validated['subject'], $validated['message']);
            }
        }

        return redirect()->route('admin.emailNotificationSent');
    }

    // おせらせメール送信完了ページ表示
    public function emailSent()
    {
        return view('admin.email-sent');
    }

    // 口コミ一覧表示
    public function showComments()
    {
        $comments = Comment::with('shop')->orderBy('created_at', 'desc')->get();

        $comments = Comment::paginate(10); 

        return view('admin.comments-all', compact('comments'));
    }

    // コメント削除メソッド
    public function destroyComments(Comment $comment)
    {
        // 画像が存在する場合は削除
        if ($comment->image) {
            if (app()->environment('production')) {
                Storage::disk('s3')->delete($comment->image);
            } else {
                Storage::disk('public')->delete($comment->image);
            }
        }

        // コメント削除
        $comment->delete();

        // 成功メッセージを表示してリダイレクト
        return redirect()->route('admin.showComments')->with('success', 'コメントが削除されました');
    }

    // CSVインポートページ表示
    public function showImportForm()
    {
        return view('admin.shop-import');
    }

    // 新規店舗登録CSVインポート
    public function import(Request $request)
    {
        // バリデーション
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'csv.required' => 'CSVファイルが選択されていません',
            'csv.file' => 'アップロードするファイルが無効です',
            'csv.mimes' => 'CSV形式のファイルを選択してください',
            'csv.max' => 'CSVファイルのサイズは2MB以下でなければなりません',
        ]);

        // ファイルがアップロードされているか確認
        if (!$request->hasFile('csv') || !$request->file('csv')->isValid()) {
            return back()->with('error', 'CSVファイルを選択してください。');
        }

        // ファイルが選択されている場合にのみ処理を実行
        $filePath = $request->file('csv')->getPathname();

        // ファイルパスが取得できた場合のみ handleCSVImport を呼び出す
        if (!$filePath) {
            return back()->with('error', 'CSVファイルが選択されていないか無効です。');
        }

        try {
            $this->handleCSVImport($filePath);  // ファイルパスを渡す
        } catch (\Exception $e) {
            return back()->with('error', 'CSVインポート処理中にエラーが発生しました。');
        }

        return redirect()->back()->with('success', 'CSVデータをインポートしました！');
    }

    public function handleCSVImport($filePath)
    {
        // CSVファイルを読み込み
        $csvData = array_map('str_getcsv', file($filePath));

        // ストレージの保存先ディレクトリ
        $storageDirectory = 'shops/';

        // CSVファイルの内容を処理
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle); // ヘッダー行を取得

            // 各行を処理
            while (($data = fgetcsv($handle)) !== false) {
                try {
                    // 必要なデータを取得（CSVのカラム順を確認）
                    $name = $data[0];               // 店舗名
                    $userName = $data[1];           // ユーザー名
                    $prefecture = $data[2];         // 地域
                    $genre = $data[3];              // ジャンル
                    $description = $data[4];        // 説明
                    $imageUrl = $data[5];           // 画像URL

                    // CSVデータのチェック
                    if (empty($name) || empty($userName) || empty($prefecture) || empty($genre) || empty($description) || empty($imageUrl)) {

                    }

                    // ユーザー名からユーザーIDを取得
                    $user = User::where('name', $userName)->first();
                    if (!$user) {
                        logger()->error("ユーザーが見つかりません: {$userName}");
                        continue; // 次の行に進む
                    }

                    // 画像URLが無効でないかチェック
                    if (@getimagesize($imageUrl) === false) {
                        logger()->error("無効な画像URL: {$imageUrl}");
                        continue; // 次の行に進む
                    }

                    // 画像を保存
                    $imageName = basename($imageUrl); // URLからファイル名を取得
                    $storagePath = $storageDirectory . $imageName;

                    if (app()->environment('production')) {
                        // 本番環境：S3に保存
                        Storage::disk('s3')->put($storagePath, file_get_contents($imageUrl));
                    } else {
                        // ローカル環境：public/shops に保存
                        Storage::disk('public')->put($storagePath, file_get_contents($imageUrl));
                    }

                    // Prefecture と Genre を取得してデータベースに登録
                    $prefectureId = Prefecture::where('name', $prefecture)->firstOrFail()->id;
                    $genreId = Genre::where('name', $genre)->firstOrFail()->id;

                    // データベースに登録
                    Shop::create([
                        'name' => $name,
                        'user_id' => $user->id,        // ユーザーID
                        'prefecture_id' => $prefectureId,
                        'genre_id' => $genreId,
                        'description' => $description,
                        'image' => $storagePath,      // 保存した画像のパスを登録
                    ]);
                } catch (\Exception $e) {
                    logger()->error("CSVインポートエラー: {$e->getMessage()}", ['data' => $data]);
                    continue; // エラーが発生した場合は次の行に進む
                }
            }
            fclose($handle);
        }
    }


}

