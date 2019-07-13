<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'id'         => (int) $buyer->id,
            'name'       => (string) $buyer->name,
            'email'      => (string) $buyer->email,
            'verified'   => (int) $buyer->verified,
            'created_at' => (string) $buyer->created_at,
            'updated_at' => (string) $buyer->updated_at,
            'deleted_at' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
            'links'      => [
                [
                    'rel'  => 'self',
                    'href' => route('buyers.show', $buyer->id),
                ],
                [
                    'rel'  => 'buyer.categories',
                    'href' => route('buyers.categories.index', $buyer->id),
                ],
                [
                    'rel'  => 'buyer.products',
                    'href' => route('buyers.products.index', $buyer->id),
                ],
                [
                    'rel'  => 'buyer.sellers',
                    'href' => route('buyers.sellers.index', $buyer->id),
                ],
                [
                    'rel'  => 'buyer.transactions',
                    'href' => route('buyers.transactions.index', $buyer->id),
                ],
                [
                    'rel'  => 'user',
                    'href' => route('users.show', $buyer->id),
                ],
            ],
        ];
    }

    public static function getOriginalAttribute($index)
    {
        $attributes =  [
            'id'         => 'id',
            'name'       => 'name',
            'email'      => 'email',
            'verified'   => 'verified',
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
            'name'       => 'name',
            'email'      => 'email',
            'verified'   => 'verified',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null; 
    }
}
