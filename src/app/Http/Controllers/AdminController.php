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

    // 管理者ダッシュボードを表示するアクション
    public function index()
    {
        return view('admin.admin-dashboard');
    }

    /* 管理者・店舗代表登録画面ページ表示 */
    public function showRegisterForm()
    {
        $roles = Role::where('name', 'store_manager')->get();


        return view('admin.admin-register', [
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
        return redirect()->back()->with('message', 'ユーザーが正常に登録されました');

    }

    // メール送信画面表示
    public function showNotificationForm()
    {
        $users = User::all();
        $roles = Role::all();

        return view('admin.email-notification', compact('users', 'roles'));
    }

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
            // 取得したロールを確認
            $role = Role::where('id', $validated['roles'])->first();

            if ($role) {
                $roleUsers = User::whereHas('roles', function ($query) use ($role) {
                    $query->where('id', $role->id);
                })->get();

                // ロールを持つユーザーの情報を確認
                logger()->info('Role Users:', $roleUsers->toArray()); // ここでログに記録する
            } else {
                logger()->warning('Role not found for ID:', ['role_id' => $validated['roles']]); // 警告をログに記録
            }

            // $usersにロールを持つユーザーをマージ
            $users = $users->merge($roleUsers)->unique('id');
        }

        // メール送信処理
        foreach ($users as $user) {
            if ($user->email) {
                // ジョブをキューに追加
                SendNotificationEmail::dispatch($user->email, $validated['subject'], $validated['message']);
                logger()->info('Email dispatched for:', ['email' => $user->email]);
            } else {
                logger()->warning('No email found for user:', ['user_id' => $user->id]);
            }
        }

        return back()->with('message', 'お知らせメールを送信しました');
    }

}
