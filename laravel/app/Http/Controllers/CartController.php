<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\LineItem;

class CartController extends Controller
{
    public function index()
    {
        // セッションからカートIDを取得
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

    public function checkout()
    {
        // セッションからカートIDを取得
        $cart_id = Session::get('cart');
        // 取得したカートIDでcartsテーブルのレコードを検索
        $cart = Cart::find($cart_id);

        // Stripe Checkoutへ渡す購入商品のリストを作成
        $line_items = [];
        foreach($cart->products as $product) {
            $line_item = [
                'name' => $product->name, // 商品名
                'description' => $product->description, // 商品の説明文
                'amount' => $product->price, // 価格
                'currency' => 'jpy', // 価格の単位
                'quantity' => $product->pivot->quantity, // 個数
            ];
            // 購入する商品のリストを$line_itemsへ格納
            array_push($line_items, $line_item);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            // 支払い方法の指定
            'payment_method_types' => ['card'],
            // 購入商品のセット
            'line_items' => [$line_items],
            // 決済成功時のリダイレクトURL
            'success_url' => route('cart.success'),
            // 決済失敗時のリダイレクトURL
            'cancel_url' => route('cart.index'),
        ]);

        return view('cart.checkout', [
            'session' => $session,
            'publicKey' => env('STRIPE_PUBLIC_KEY')
        ]);
    }

    public function success()
    {
        // セッションからカートIDを取得
        $cart_id = Session::get('cart');
        // line_itemsテーブルからカートIDで検索
        LineItem::where('cart_id', $cart_id)->delete();

        // 商品一覧画面へリダイレクト
        return redirect(route('product.index'));
    }
}
