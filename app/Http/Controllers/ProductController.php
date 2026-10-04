<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\UploadImgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index (Request $request)
    {

        // if($request->search ){
        //     dd($request->search . " ". $request->category. " ". $request->brand);
           
        // }

        $query = Product::query();
        if($request->search){
            $query->where('name', 'like', "%{$request->search}%");
        }

        if($request->category){
            $query->where('category_id', $request->category);
        }
        if($request->brand){
            $query->where('brand_id', $request->brand);
        }

        $products = $query->with('category', 'brand')
             ->orderBy('id', 'desc')
             ->paginate();




        $categories = Category::orderBY('name','asc')->get();
        $brands = Brand::orderBY('name','asc')->get();
        // $products = Product::with('category','brand' )->orderby('id', 'desc')->paginate(10);
        
        // dd($products->first()->category);
        return view('admin.pages.product.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderby('name', 'asc')->get();
        $brands= Brand::orderby('name', 'asc')->get();
        return view('admin.pages.product.create', compact('categories', 'brands' ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            // 'iamge' =>'required|array',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'name'=>'required|min:3',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:500',

        ] ,[
             'name.min'=>'please enter at least 3 characters',
             'image.max'=>'Image size is too learge . Maximum size is 500kb',
            //  'image.mimes'=>'please upload valid file',
        ]);

        if($request->hasFile('image')){
            // dd($request->image->extension());
            // $imgName= time() . '.' . $request->image->extension();
            // $request->image->move(public_path('uploads'), $imgName );

            $imgName= UploadImgService::upload($request->image, 'uploads/products');

            Product::create([
              'name'           => $request->name,
              'price'          => $request->price,
              'quantity'       => $request->qty,
              'reorder_level'  => $request->reorder,
              'description'    => $request->desc,
              'category_id'    => $request->category_id,
              'brand_id'       => $request->brand_id,
              'active'         => $request->active ? 1: 0,
              'image'          =>  $imgName,
            ]);
            return redirect()->route('products.index')->with('success', 'product created successfully');

        }else{
        //    dd('no Image');

        Product::create([
              'name'  => $request->name,
              'price'  => $request->price,
              'quantity'  => $request->qty,
              'reorder_level'  => $request->reorder,
              'description'  => $request->desc,
              'category_id'  => $request->category_id,
              'brand_id'  => $request->brand_id,
              'active'  => $request->active ? 1: 0,
            ]);
            return redirect()->route('products.index')->with('success', 'product created successfully');


        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // dd($product);
        if($product->image ){
            unlink(public_path($product->image));
        }
        Product::destroy($product->id);
        return redirect()->route('products.index')->with('success', 'Product delated successfully');

    }
}
