<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'rental_price', 'image', 'quantity'];

    public function setPriceAttribute($value): void
    {
        $this->attributes['price'] = $value * 100;
    }
    public function getPriceAttribute($value)
    {
        return $value / 100;
    }
    public function setRentalPriceAttribute($value): void
    {
        $this->attributes['rental_price'] = $value * 100;
    }
    public function getRentalPriceAttribute($value)
    {
        return $value / 100;
    }
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function rentalTransactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class)->where('type', 'rental');
    }

}
