<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        // カートIDを取得
        $cart_id = Session::get('cart');
        // 取得したカートIDでcartsテーブルのレコードを検索
        $cart = Cart::find($cart_id);

        // 合計金額の算出
        $total_price = 0;
        foreach($cart->products as $product) {
            $total_price += $product->price * $product->pivot->quantity;
        }

        return view('cart.index')
            ->with('line_items', $cart->products)
            ->with('total_price', $total_price);
    }
}
