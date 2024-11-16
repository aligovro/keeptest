<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\TransactionService;
use App\Services\UserService;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;
    protected UserService $userService;

    public function __construct(TransactionService $transactionService, UserService $userService)
    {
        $this->transactionService = $transactionService;
        $this->userService = $userService;
    }

    public function buy(Product $product)
    {
        $user = auth('api')->user();

        if (!$this->userService->hasSufficientBalance($user, $product->price)) {
            return response()->json(['error' => 'Insufficient balance'], 403);
        }

        $transaction = $this->transactionService->createPurchaseTransaction($user, $product);

        $this->userService->deductBalance($user, $product->price);
        $product->decrement('quantity');
        return response()->json($transaction);
    }

    public function rent(Product $product, Request $request)
    {
        $user = auth('api')->user();
        $rentalTime = $request->input('hours');

        if (!in_array($rentalTime, [4, 8, 12, 24])) {
            return response()->json(['error' => 'Invalid rental period'], 400);
        }

        if (!$this->userService->hasSufficientBalance($user, $product->rental_price)) {
            return response()->json(['error' => 'Insufficient balance'], 403);
        }

        $transaction = $this->transactionService->createRentalTransaction($user, $product, $rentalTime);

        $this->userService->deductBalance($user, $product->rental_price);

        return response()->json($transaction);
    }

    public function extend(Transaction $transaction, Request $request): \Illuminate\Http\JsonResponse
    {
        $additionalHours = $request->input('hours');
        if (!in_array($additionalHours, [4, 8, 12, 24])) {
            return response()->json(['error' => 'Invalid rental period'], 400);
        }
        try {
            $transaction = $this->transactionService->extendRentalTransaction($transaction, $additionalHours);
            return response()->json($transaction);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


}
