<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart',compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add items to the cart.');
        }
    
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
    
        CartModel::create([
            'user_id' => Auth::id(),
            'product_id' => $request->id,
            'name' => $request->name,
            'quantity' => $request->quantity,
        ]);
    
        return redirect()->back();
    }
    

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }
}
