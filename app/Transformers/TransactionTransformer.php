<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'id'         => (int) $transaction->id,
            'quantity'   => (int) $transaction->quantity,
            'buyer_id'   => (int) $transaction->buyer_id,
            'product_id' => (int) $transaction->product_id,
            'created_at' => (string) $transaction->created_at,
            'updated_at' => (string) $transaction->updated_at,
            'deleted_at' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
            'links'      => [
                [
                    'rel'  => 'self',
                    'href' => route('transactions.show', $transaction->id),
                ],
                [
                    'rel'  => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $transaction->id),
                ],
                [
                    'rel'  => 'transaction.categories',
                    'href' => route('transactions.categories.index', $transaction->id),
                ],
                [
                    'rel'  => 'buyer',
                    'href' => route('buyers.show', $transaction->buyer_id),
                ],
                [
                    'rel'  => 'product',
                    'href' => route('products.show', $transaction->product_id),
                ],
            ],
        ];
    }

    public static function getOriginalAttribute($index)
    {
        $attributes =  [
            'id'         => 'id',
            'quantity'   => 'quantity',
            'buyer_id'   => 'buyer_id',
            'product_id' => 'product_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null; 
    }

    public static function getTransformedAttribute($index)
    {
        $attributes =  [
            'id'         => 'id',
            'quantity'   => 'quantity',
            'buyer_id'   => 'buyer_id',
            'product_id' => 'product_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null; 
    }
}
