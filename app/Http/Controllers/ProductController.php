<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\UploadImgService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::from('products as p')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->join('brands as b', 'b.id', '=', 'p.brand_id')
            ->select('p.*', 'c.name as category', 'b.name as brand');

        if ($request->search) {
            $query->where('p.name', 'like', '%' . $request->search . '%');
        }
        if ($request->category_id) {
            $query->where('p.category_id', $request->category_id);
        }
        if ($request->brand_id) {
            $query->where('p.brand_id', $request->brand_id);
        }

        $products = $query->orderBy('p.id', 'desc')->paginate(15);

        return response()->json([
            'success'   => true,
            'products'  => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        return view('admin.pages.product.create', [
            'categories'    => $categories,
            'brands'        => $brands
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if($request->hasFile('image')){
        //     return response()->json('iamge found');
        // }else{
        //     return response()->json('iamge not  found');
        // }
        $request->validate(
            [
                'name'          => 'required|min:3|max:255',
                'price'         => 'required|numeric|min:0',
                'quantity'      => 'required|integer|min:0',
                'reorder_level' => 'integer|min:0',
                'description'   => 'required|string',
                'category_id'   => 'required|exists:categories,id',
                'brand_id'      => 'required|exists:brands,id',
                // For single image
                'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:500',
            ],
            [
                'name.min' => 'Please enter at least 3 characters',
                'image.max' => 'Sorry! Image size is too large. Maximum size is 500kb.',
            ]
        );


        $image = null;
        if ($request->hasFile('image')) {
            $image = UploadImgService::upload($request->image, 'uploads/products');
        }

        $product = Product::create([
            'name'          => $request->name,
            'price'         => $request->price,
            'quantity'      => $request->quantity,
            'reorder_level' => $request->reorder_level,
            'description'   => $request->description,
            'category_id'   => $request->category_id,
            'brand_id'      => $request->brand_id,
            'active'        => $request->has('active') ? ($request->boolean('active') ? 1 : 0) : 1,
            'image'         => $image,
        ]);

        if ($product) {
            return response()->json([
                'success' => 'Product created successfully',
                'product' => $product
            ]);
        } else {
            return response()->json([
                'error' => 'Product not created. Try again later.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::from('products as p')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->join('brands as b', 'b.id', '=', 'p.brand_id')
            ->where('p.id', $id)
            ->select('p.*', 'c.name as category', 'b.name as brand')
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Product not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        $product = Product::find($id);

        return view('admin.pages.product.edit', [
            'product'       => $product,
            'categories'    => $categories,
            'brands'        => $brands
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'error' => true,
                'message' => 'Product not found'
            ], 404);
        }

        $request->validate(
            [
                'name'          => 'required|min:3|max:255',
                'price'         => 'required|numeric|min:0',
                'quantity'      => 'required|integer|min:0',
                'reorder_level' => 'required|integer|min:0',
                'description'   => 'required|string',
                'category_id'   => 'required|exists:categories,id',
                'brand_id'      => 'required|exists:brands,id',
                'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:500',
            ],
            [
                'name.min' => 'Please enter at least 3 characters',
                'image.max' => 'Sorry! Image size is too large. Maximum size is 500kb.',
            ]
        );

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->image = UploadImgService::upload($request->image, 'uploads/products');
        }

        $product->name          = $request->name;
        $product->price         = $request->price;
        $product->quantity      = $request->quantity;
        $product->reorder_level = $request->reorder_level;
        $product->description   = $request->description;
        $product->category_id   = $request->category_id;
        $product->brand_id      = $request->brand_id;
        $product->active        = $request->has('active') ? ($request->boolean('active') ? 1 : 0) : 0;

        if ($product->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong. Product not updated'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if ($product) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Product not found'
            ], 404);
        }
    }
}
