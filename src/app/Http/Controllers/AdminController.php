<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Comment;
use App\Jobs\SendNotificationEmail;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\SendNotificationRequest;
use Spatie\Permission\Models\Role;

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

    // 口コミ削除処理
    public function destroyComments(Comment $comment)
    {
        if ($comment->image) {
            if (app()->environment('production')) {
                Storage::disk('s3')->delete($comment->image);
            } else {
                Storage::disk('public')->delete($comment->image);
            }
        }

        $comment->delete();

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
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'csv.required' => 'CSVファイルが選択されていません',
            'csv.file' => 'アップロードするファイルが無効です',
            'csv.mimes' => 'CSV形式のファイルを選択してください',
            'csv.max' => 'CSVファイルのサイズは2MB以下でなければなりません',
        ]);

        if (!$request->hasFile('csv') || !$request->file('csv')->isValid()) {
            return back()->with('error', 'CSVファイルを選択してください');
        }

        $filePath = $request->file('csv')->getPathname();

        if (!$filePath) {
            return back()->with('error', 'CSVファイルが選択されていないか無効です');
        }

        $fileData = file_get_contents($filePath);

        if (!$this->isValidCSV($fileData)) {
            return back()->withInput()->withErrors(['csv' => 'CSVの内容が不正です']);
        }

        try {
            $this->handleCSVImport($filePath);
        } catch (\Exception $e) {
            return back()->with('error', 'CSVインポート処理中にエラーが発生しました');
        }

        return redirect()->back()->with('success', 'CSVデータをインポートしました！');
    }

    private function isValidCSV($fileData)
    {
        $lines = explode(PHP_EOL, $fileData);

        $header = str_getcsv($lines[0]);

        $requiredColumns = ['店舗名', 'ユーザー名', '地域', 'ジャンル', '店舗概要', '画像URL'];

        foreach ($requiredColumns as $column) {
            if (!in_array($column, $header)) {
                return false;
            }
        }

        foreach (array_slice($lines, 1) as $line) {
            $columns = str_getcsv($line);

            if (count($columns) < count($requiredColumns)) {
                return false;
            }
        }

        return true;
    }

    public function handleCSVImport($filePath)
    {
        $csvData = array_map('str_getcsv', file($filePath));

        $storageDirectory = 'shops/';
        $errors = [];
        $successMessages = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {

                $validator = Validator::make([
                    'name' => $data[0],
                    'user_name' => $data[1],
                    'prefecture' => $data[2],
                    'genre' => $data[3],
                    'description' => $data[4],
                    'image_url' => $data[5],
                ], [
                    'name' => 'required|string',
                    'user_name' => 'required|string',
                    'prefecture' => 'required|in:東京都,大阪府,福岡県',
                    'genre' => 'required|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
                    'description' => 'required|string',
                    'image_url' => 'required|url',
                ], [
                    'name.required' => '店舗名は必須です',
                    'name.string' => '店舗名は文字列でなければなりません',
                    'user_name.required' => 'ユーザー名は必須です',
                    'user_name.string' => 'ユーザー名は文字列でなければなりません',
                    'prefecture.required' => '地域は必須です',
                    'prefecture.in' => '指定された地域は無効です。東京都、大阪府、福岡県のいずれかを選択してください',
                    'genre.required' => 'ジャンルは必須です',
                    'genre.in' => 'ジャンルは寿司、焼肉、イタリアン、居酒屋、ラーメンのいずれかを選択してください',
                    'description.required' => '店舗概要は必須です',
                    'description.string' => '店舗概要は文字列でなければなりません',
                    'image_url.required' => '画像URLは必須です',
                    'image_url.url' => '画像URLは有効なURL形式でなければなりません',
                ]);

                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        $errors[] = "店舗「{$data[0]}」: {$error}";
                    }
                    continue;
                }

                try {
                    $name = $data[0];
                    $userName = $data[1];
                    $prefecture = $data[2];
                    $genre = $data[3];
                    $description = $data[4];
                    $imageUrl = $data[5];

                    $user = User::where('name', $userName)->first();
                    if (!$user) {
                        $errors[] = "ユーザーが見つかりません: {$userName}";
                        continue;
                    }

                    if (!$user->hasRole('store_manager')) {
                        $errors[] = "店舗代表者ではないユーザーです: {$userName}";
                        continue;
                    }

                    if (@getimagesize($imageUrl) === false) {
                        $errors[] = '無効な画像URLです';
                        continue;
                    }

                    $imageName = basename($imageUrl);
                    $storagePath = $storageDirectory . $imageName;

                    if (app()->environment('production')) {
                        Storage::disk('s3')->put($storagePath, file_get_contents($imageUrl));
                    } else {
                        Storage::disk('public')->put($storagePath, file_get_contents($imageUrl));
                    }

                    $prefectureId = Prefecture::where('name', $prefecture)->firstOrFail()->id;
                    $genreId = Genre::where('name', $genre)->firstOrFail()->id;

                    Shop::create([
                        'name' => $name,
                        'user_id' => $user->id,
                        'prefecture_id' => $prefectureId,
                        'genre_id' => $genreId,
                        'description' => $description,
                        'image' => $storagePath,
                    ]);

                    $successMessages[] = "店舗「{$name}」のインポートに成功しました";

                } catch (\Exception $e) {
                    $errors[] = "データの処理中にエラーが発生しました: {$e->getMessage()}";
                }
            }
            if (count($errors) > 0) {
                return back()->with('import_success', $successMessages)
                    ->withErrors(['import_errors' => $errors])
                    ->withInput();
            } else {
                return back()->with('import_success', $successMessages)->withErrors([]);
            }
        }
    }
}

