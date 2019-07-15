<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
        $this->middleware('scope:purchase-product')->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse(409, "El comprador debe ser diferente del vendedor.");
        }

        if (!$buyer->isVerified()) {
            return $this->errorResponse(409, "El comprador debe ser un usuario verificado.");
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse(409, "El vendedor debe ser un usuario verificado.");
        }

        if (!$product->isAvailable()) {
            return $this->errorResponse(409, "El producto para esta transacción no está disponible.");
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorResponse(409, "El producto no tiene la cantidad disponible requerida para esta transacción.");
        }

        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction);
        });
    }
}
