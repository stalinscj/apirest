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
        ];
    }

    public static function getOriginalAttribute($index)
    {
        $attributes =  [
            'id'         => "id",
            'name'       => "name",
            'email'      => "email",
            'verified'   => "verified",
            'created_at' => "created_at",
            'updated_at' => "updated_at",
            'deleted_at' => "deleted_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null; 
    }
}
