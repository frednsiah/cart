<?php

namespace App\Http\Controllers;
use App\Models\Product;

use App\Http\Requests\ProductRequest;
use Storage;

class ProductsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $products = Product::paginate(10);
        return view('home', compact('products'));
    }

    public function create(ProductRequest $request) {
        if(!$product = Product::create($request->all())) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => [
                        'error' => __('Something wrong, please try again')
                        ]
                ], 
                500
            );
        }
        Storage::disk('local')
        ->put(
            "products/{$product->id}-{$product->created_at->toDateString()}.json",
            collect(['product' => $product])->toJson()
        );
        return [
            'success' => true,
            'message' => __('Product Created'),
            'data' => $product
        ];
    }

    public function edit(Product $product) {
        return view('edit', compact('product'));
    }

    public function store(Product $product, ProductRequest $request) {
        $product->fill($request->all());

        if(!$product->save()) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => [
                        'error' => __('Something wrong, please try again')
                        ]
                ], 
                500
            );
        }
        Storage::disk('local')
        ->put(
            "products/{$product->id}-{$product->created_at->toDateString()}.json",
            collect(['product' => $product])->toJson()
        );

        return [
            'success' => true,
            'message' => __('Product Updated'),
            'data' => $product
        ];
    }

    public function download(Product $product) {
        $file_path = "products/{$product->id}-{$product->created_at->toDateString()}.json";

        if(!Storage::disk('local')->exists($file_path)) {
            abort(404);
        }
        return Storage::disk('local')->download($file_path);
    }

}