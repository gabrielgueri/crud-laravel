<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController
{
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'sucesso',
            'message' => 'ProductController funcionando perfeitamente via API!',
            'data' => []
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'store']);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['message' => 'show']);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json(['message' => 'update']);
    }

    public function destroy(string $id): JsonResponse
    {
        return response()->json(['message' => 'destroy']);
    }
}