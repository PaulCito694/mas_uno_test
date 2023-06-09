<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $order_by = $request->input('order_by', 'asc');

        $categories = Category::orderBy('name', $order_by)->get();

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), Category::rules());
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'in:1,0'
            ]);

            $category = new Category();
            $category->name = $request->input('name');
            $category->slug = $this->slug_generator($request, 'categories');
            $category->status = $request->input('status', 1);
            $category->save();

            return response()->json([
                'success' => true,
                'data' => $category
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function getCategoriesWithProducts($order = 'desc')
    {
        $categories = Category::leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select('categories.*', DB::raw('count(products.id) as product_count'))
            ->groupBy('categories.id', 'categories.name', 'categories.slug', 'categories.created_at', 'categories.updated_at', 'categories.status')
            ->orderBy('product_count', $order)
            ->get();

        return response()->json($categories);
    }
}
