<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Cart as CartModel;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{

    public function index()
    {
        $items = Cart::instance('wishlist')->content();
        return view('wishlist', compact('items'));
    }

    public function add_to_wishlist(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add items to the wishlist.');
        }

        Cart::instance('wishlist')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->id,
            'name' => $request->name,
        ]);
    
        return redirect()->back()->with('success', 'Item added to wishlist successfully!');
    }
    
    public function remove_item($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back();
    }
    public function empty_wishlist()
    {
        Cart::instance('wishlist')->destroy();
        return redirect()->back();
    }

    public function move_to_cart($rowId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add items to the cart.');
        }
    
        $item = Cart::instance('wishlist')->get($rowId);
        
        Cart::instance('wishlist')->remove($rowId);
        
        Cart::instance('cart')->add($item->id, $item->name, $item->qty, $item->price)->associate('App\Models\Product');
        
        CartModel::create([
            'user_id' => Auth::id(),
            'product_id' => $item->id,
            'name' => $item->name,
            'quantity' => $item->qty,
        ]);
        
        return redirect()->back()->with('success', 'Item moved to cart successfully!');
    }
    
}
