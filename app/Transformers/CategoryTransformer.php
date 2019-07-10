<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'id'          => (int) $category->id,
            'name'        => (string) $category->name,
            'description' => (string) $category->description,
            'created_at'  => (string) $category->created_at,
            'updated_at'  => (string) $category->updated_at,
            'deleted_at'  => isset($category->deleted_at) ? (string) $category->deleted_at : null,
        ];
    }

    public static function getOriginalAttribute($index)
    {
        $attributes =  [
            'id'          => "id",
            'name'        => "name",
            'description' => "description",
            'created_at'  => "created_at",
            'updated_at'  => "updated_at",
            'deleted_at'  => "deleted_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null; 
    }
}
