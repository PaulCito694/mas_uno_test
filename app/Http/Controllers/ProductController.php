<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), Product::rules());
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $request->validate([
                'name' => 'string|max:255',
                'status' => 'in:1,0'
            ]);

            $product = new Product();
            $product->name = $request->input('name');
            $product->slug = $this->slug_generator($request, 'products');
            $product->category_id = $request->input('category_id');
            $product->status = $request->input('status', 1);
            $product->save();

            return response()->json([
                'success' => true,
                'data' => $product
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function index($perPage = 10)
    {
        $products = Product::select('id', 'name', 'slug')->paginate($perPage);

        return response()->json($products);
    }
}
