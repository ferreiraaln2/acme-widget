<?php

namespace App\Http\Controllers\Api;

use App\Application\Basket\CalculateBasketTotalService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateBasketTotalRequest;
use Illuminate\Http\JsonResponse;

class BasketController extends Controller
{
    public function total(
        CalculateBasketTotalRequest $request,
        CalculateBasketTotalService $calculateBasketTotal,
    ): JsonResponse {
        $result = $calculateBasketTotal->handle($request->itemCodes());

        return response()->json($result->toArray());
    }
}
