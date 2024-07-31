<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ShopRequest;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;

class AdminController extends Controller
{

    public function create()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('admin.shop_register', compact('prefectures', 'genres'));
    }

    /* 店舗情報追加処理 */
    public function store(ShopRequest $request)
    {
        $shop = new Shop();
        $shop->name = $request->name;
        $prefecture = Prefecture::find($request->prefecture_id);
        $genre = Genre::find($request->genre_id);
        $shop->description = $request->description;

        if ($request->hasFile('image')) {
            $shop->image = $request->file('image')->store('shops', 'public');
        }


        $shop->save();

        return redirect()->back()->with('success', '店舗情報が追加されました');
    }

    public function end($id)
    {
        $shop = Shop::findOrFail($id);

        return view('shops.edit', compact('shop'));
    }

    public function update(ShopRequest $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $prefecture = Prefecture::find($request->prefecture_id);
        $genre = Genre::find($request->genre_id);
        $shop->description = $request->description;


        if ($request->hasFile('image')) {
            if ($shop->image) {
                Storage::disk('public')->delete($shop->image);
            }

            $shop->image = $request->file('image')->store('shops', 'public');
        }

        $shop->save();

        return redirect()->route('shops.index')->with('success', '店舗情報が更新されました');

    }
}
