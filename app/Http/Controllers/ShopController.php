<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size') ? $request->query('size') : 12;
        $o_column = "";
        $o_order = "";
        $order = $request->query('order') ? $request->query('order') : -1;

        // Order logic (same as before)
        switch ($order) {
            case 1:
                $o_column = "created_at";
                $o_order = "DESC";
                break;
            case 2:
                $o_column = "created_at";
                $o_order = "ASC";
                break;
            case 3:
                $o_column = "sale_price";
                $o_order = "ASC";
                break;
            case 4:
                $o_column = "sale_price";
                $o_order = "DESC";
                break;
            case 5:
                $o_column = "name";
                $o_order = "ASC";
                break;
            case 6:
                $o_column = "name";
                $o_order = "DESC";
                break;
            default:
                $o_column = "id";
                $o_order = "DESC";
        }

        // Category filter
        $category_id = $request->query('category_id');
        $query = Product::query();

        if ($category_id != -1 && $category_id) {
            $query->where('category_id', $category_id);
        }

        // Fetch products based on the query with order and pagination
        $products = $query->orderBy($o_column, $o_order)->paginate($size);

        // Fetch all categories for the dropdown
        $categories = Category::all();

        return view('shop', compact('products', 'size', 'order', 'categories'));
    }


    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $rproducts = Product::where('slug', '<>', $product_slug)->get()->take(8);
        return view('details', compact('product', 'rproducts'));
    }
}
