<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class CartSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // セッションにカートIDがあるかの確認
        if(!Session::has('cart')) { // セッションにカートIDがない場合
            $cart = Cart::create(); // カートを作成する
            Session::put('cart', $cart->id); // セッションにカートID保存
        }

        return $next($request);
    }
}
