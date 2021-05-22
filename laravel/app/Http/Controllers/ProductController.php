<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.index')
            // with: Bladeテンプレートに値を渡す
            ->with('products', Product::get());
    }
}
