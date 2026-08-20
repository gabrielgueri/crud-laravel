<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController
{
    // GET /api/products
    public function index(): JsonResponse
    {
        $products = Product::orderBy('id', 'desc')->get();
        return response()->json($products, 200);
    }

    // POST /api/products
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0.01',
            'stock'       => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    // GET /api/products/{id}
    public function show(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado.'], 404);
        }

        return response()->json($product, 200);
    }

    // PUT/PATCH /api/products/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado.'], 404);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|required|numeric|min:0.01',
            'stock'       => 'sometimes|required|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json($product, 200);
    }

    // DELETE /api/products/{id}
    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado.'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Produto removido com sucesso.'], 200);
    }
}