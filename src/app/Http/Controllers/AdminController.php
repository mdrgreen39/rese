<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
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
}
