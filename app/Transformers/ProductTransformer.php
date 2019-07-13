<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id'          => (int) $product->id,
            'name'        => (string) $product->name,
            'description' => (string) $product->description,
            'quantity'    => (int) $product->quantity,
            'status'      => (string) $product->status,
            'image'       => url("img/{$product->image}"),
            'seller_id'   => (int) $product->seller_id,
            'created_at'  => (string) $product->created_at,
            'updated_at'  => (string) $product->updated_at,
            'deleted_at'  => isset($product->deleted_at) ? (string) $product->deleted_at : null,
            'links'       => [
                [
                    'rel'  => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel'  => 'product.buyers',
                    'href' => route('products.buyers.index', $product->id),
                ],
                [
                    'rel'  => 'product.categories',
                    'href' => route('products.categories.index', $product->id),
                ],
                [
                    'rel'  => 'product.transactions',
                    'href' => route('products.transactions.index', $product->id),
                ],
                [
                    'rel'  => 'seller',
                    'href' => route('sellers.show', $product->seller_id),
                ],
            ],
        ];
    }

    public static function getOriginalAttribute($index)
    {
        $attributes =  [
            'id'          => 'id',
            'name'        => 'name',
            'description' => 'description',
            'quantity'    => 'quantity',
            'status'      => 'status',
            'image'       => 'image',
            'seller_id'   => 'seller_id',
            'created_at'  => 'created_at',
            'updated_at'  => 'updated_at',
            'deleted_at'  => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null; 
    }

    public static function getTransformedAttribute($index)
    {
        $attributes =  [
            'id'          => 'id',
            'name'        => 'name',
            'description' => 'description',
            'quantity'    => 'quantity',
            'status'      => 'status',
            'image'       => 'image',
            'seller_id'   => 'seller_id',
            'created_at'  => 'created_at',
            'updated_at'  => 'updated_at',
            'deleted_at'  => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null; 
    }
}
