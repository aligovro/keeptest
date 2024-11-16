<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //

    public function index(): JsonResponse
    {
        $products = Product::paginate(18);
        return response()->json($products);
    }
}
