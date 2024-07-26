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
    /* 店舗情報追加ページ表示*/
    public function create()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('admin.create_shop', compact('prefectures', 'genres'));
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
            $shop->image = $request->file('image')->store('shop', 'public');
        }

        if ($request->has('new_genre')) {
            $genre = Genre::firstOrCreate(['name' => $request->new_genre]);
            $shop->genre_id = $request->genre_id;
        }

        $shop->save();

        return redirect()->back()->with('success', '店舗情報が追加されました');
    }

    public function end($id)
    {
        $shop = Shop::findOrFail($id);

        return view('shops.edit', compact('shop'));
    }

    public function update(ShopRequset $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $prefecture = Prefecture::find($request->prefecture_id);
        $genre = Genre::find($request->genre_id);
        $shop->description = $request->description;

        if ($request->has('new_genre')) {
            $genre = Genre::firstOrCreate(['name' => $request->new_genre]);
            $shop->genre_id = $request->genre_id;
        }

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
