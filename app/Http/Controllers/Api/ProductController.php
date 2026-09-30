<?php

namespace App\Http\Controllers\Api;

use App\Application\Product\ListProductsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(ListProductsService $listProducts): JsonResponse
    {
        return response()->json([
            'data' => $listProducts->handle(),
        ]);
    }
}
