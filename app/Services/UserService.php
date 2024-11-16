<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;

class UserService
{
    /**
     * Проверяет, достаточно ли у пользователя средств для покупки.
     */
    public function hasSufficientBalance($user, float $amount): bool
    {
        return $user->balance >= $amount;
    }

    /**
     * Уменьшает баланс пользователя после покупки или аренды.
     */
    public function deductBalance($user, float $amount): bool
    {
        if (!$this->hasSufficientBalance($user, $amount)) {
            return false;
        }
        $user->balance = $user->balance - $amount;
        return $user->save();
    }
}
