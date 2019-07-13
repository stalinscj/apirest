<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'id'         => (int) $seller->id,
            'name'       => (string) $seller->name,
            'email'      => (string) $seller->email,
            'verified'   => (int) $seller->verified,
            'created_at' => (string) $seller->created_at,
            'updated_at' => (string) $seller->updated_at,
            'deleted_at' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,
            'links'      => [
                [
                    'rel'  => 'self',
                    'href' => route('sellers.show', $seller->id),
                ],
                [
                    'rel'  => 'seller.categories',
                    'href' => route('sellers.categories.index', $seller->id),
                ],
                [
                    'rel'  => 'seller.products',
                    'href' => route('sellers.products.index', $seller->id),
                ],
                [
                    'rel'  => 'seller.buyers',
                    'href' => route('sellers.buyers.index', $seller->id),
                ],
                [
                    'rel'  => 'seller.transactions',
                    'href' => route('sellers.transactions.index', $seller->id),
                ],
                [
                    'rel'  => 'user',
                    'href' => route('users.show', $seller->id),
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
