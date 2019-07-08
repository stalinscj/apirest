<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Seller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name'        => 'required',
            'description' => 'required',
            'quantity'    => 'required|integer|min:1',
            'image'       => 'required|image',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Product::PRODUCT_NOT_AVAILABLE;
        $data['image'] = $request->image->store('', 'images');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $this->verifySeller($seller, $product);

        $rules = [
            'quantity' => 'integer|min:1',
            'status'   => 'in:' . Product::PRODUCT_AVAILABLE . ',' . Product::PRODUCT_NOT_AVAILABLE,
            'image'    => 'image',
        ];

        $this->validate($request, $rules);

        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->status == Product::PRODUCT_AVAILABLE && $product->categories()->count() == 0) {
                return $this->errorResponse(409, "Un producto activo debe tener al menos una categoría.");
            }
        }

        if ($request->hasFile('image')) {
            Storage::disk('images')->delete($product->image);
            
            $product->image = $request->image->store('', 'images');
        }

        if ($product->isClean()) {
            return $this->errorResponse(422, "Se debe especificar al menos un valor diferente para actualizar.");
        }

        $product->save();

        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verifySeller($seller, $product);

        Storage::disk('images')->delete($product->image);

        $product->delete();

        return $this->showOne($product);
    }

    protected function verifySeller(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422, "El vendedor especificado no es el vendedor real del producto.");
        }
    }
}
