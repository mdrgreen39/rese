<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ShopRequest;
use App\Models\User;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Mail\NotificationEmail;
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

    /* 店舗代表登録画面ページ表示 */
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
        // ユーザーを作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ロールを付与
        $user->assignRole($request->role);

        // メール認証を送信
        $user->sendEmailVerificationNotification();

        // リダイレクトと成功メッセージ
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
        // フォームリクエストで既にバリデーション済み
        $validated = $request->validated();

        $users = collect();

        // 個別の利用者が選択されている場合は取得
        if (!empty($validated['users'])) {
            $users = User::whereIn('id', $validated['users'])->get();
        }

        // 個別の利用者が選択されている場合は取得
        if (!empty($validated['users'])) {
            $users = User::whereIn('id', $validated['users'])->get();
        }


        // 選択されたロールを持つ利用者がいる場合は取得してマージ
        if (!empty($validated['roles'])) {
            $roleUsers = User::whereHas('roles', function ($query) use ($validated) {
                $query->where('id', $validated['roles']);
            })->get();

            // $usersにロールを持つユーザーをマージし、重複を排除
            $users = $users->merge($roleUsers)->unique('id');
        }

        // メール送信処理
        foreach ($users as $user) {
            if ($user->email) {
                // ジョブをキューに追加
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

}
