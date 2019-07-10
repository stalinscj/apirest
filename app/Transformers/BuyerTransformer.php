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
