<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
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

            $slug = Str::slug($request->input('name')); // Generar el slug a partir del nombre
            $count = Category::where('slug', 'like', $slug . '%')->count(); // Contar las coincidencias

            if ($count > 0) {
                $slug .= '-' . ($count + 1); // Agregar un número al final del slug
            }

            $category = new Category();
            $category->name = $request->input('name');
            $category->slug = $slug;
            $category->status = $request->input('status', 1); // Opcional: valor predeterminado para el estado
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
}
