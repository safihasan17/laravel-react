<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

        $mobile       = $this->LatestProduct(1);
        $watch        = $this->LatestProduct(2);
        $camera       = $this->LatestProduct(3);
        $assessories  = $this->LatestProduct(4);

        return view('site.pages.home', compact('mobile', 'watch', 'camera', 'assessories'));
    }

    public function LatestProduct($_category_id)
    {
         $products = Product::select('id', 'name', 'price', 'image', 'quantity')
                   ->where('category_id', $_category_id)
                   ->where('active', 1)
                   ->orderBy('id', 'desc')
                   ->limit(5)
                   ->get();
            
        return $products;
    }


    public function details($id)
    {
        $product = Product::findOrFail($id);
         return view('site.pages.product-details' , compact('product'));
    }

    public function cart()
    {
        return view('site.pages.cart');
    }



}
