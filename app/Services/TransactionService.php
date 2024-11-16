<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class TransactionService
{
    /**
     * Создает транзакцию для покупки продукта.
     */
    public function createPurchaseTransaction(User $user, Product $product): \Illuminate\Database\Eloquent\Model
    {
        return $user->transactions()->create([
            'product_id' => $product->id,
            'type' => 'purchase',
            'unique_code' => uniqid()
        ]);
    }

    /**
     * Создает транзакцию для аренды продукта.
     */
    public function createRentalTransaction(User $user, Product $product, int $rentalTime): \Illuminate\Database\Eloquent\Model
    {
        return $user->transactions()->create([
            'product_id' => $product->id,
            'type' => 'rental',
            'expires_at' => now()->addHours($rentalTime),
            'unique_code' => uniqid()
        ]);
    }

    /**
     * Продлевает аренду продукта.
     */
    public function extendRentalTransaction(Transaction $transaction, int $additionalHours): \Illuminate\Database\Eloquent\Model
    {
        if ($transaction->type !== 'rental') {
            throw new \Exception('Transaction is not a rental');
        }

        $currentExpiry = Carbon::parse($transaction->expires_at);
        $maxExpiry = Carbon::parse($transaction->created_at)->addHours(24);

        $newExpiry = $currentExpiry->addHours($additionalHours);

        if ($newExpiry > $maxExpiry) {
            throw new \Exception('Rental extension exceeds the maximum allowed duration of 24 hours');
        }

        $transaction->expires_at = $newExpiry;
        $transaction->save();

        return $transaction;
    }
}
