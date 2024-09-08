<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ShopRequest;
use App\Models\User;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{

    /* 管理者・店舗代表登録画面ページ表示 */
    public function showRegisterForm()
    {
        $roles = Role::all(); // 例: 全てのロールを取得する場合

        return view('admin.admin-register', [
            'roles' => $roles,
        ]);
    }

    // 管理者・店舗代表者登録処理
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

        // リダイレクトと成功メッセージ
        return redirect()->back()->with('message', 'ユーザーが正常に登録されました');

    }
}
