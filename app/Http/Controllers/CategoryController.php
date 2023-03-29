<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $order_by = $request->input('order_by', 'asc');

        $categories = Category::orderBy('name', $order_by)->get();

        return response()->json(['data' => $categories]);
    }
}
