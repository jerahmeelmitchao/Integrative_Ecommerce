<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price','<>','')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured',1)->get()->take(4);
        $sfproducts = Product::where('featured',1)->get()->take(2);
        return view('index',compact('categories','sproducts','fproducts','sfproducts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $query = addcslashes($query, '%_'); // Escape special characters for LIKE
        $results = Product::where('name', 'LIKE', "%{$query}%")->take(8)->get();
    
        return response()->json($results);
    }
    
}
