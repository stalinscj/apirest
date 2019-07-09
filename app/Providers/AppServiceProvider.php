<?php

namespace App\Providers;

use App\User;
use App\Product;
use App\Mail\UserCreatedMail;
use App\Mail\UserUpdatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Product::updated(function ($product){
            if ($product->quantity == 0 && $product->isAvailable()) {
                $product->status = Product::PRODUCT_NOT_AVAILABLE;
                
                $product->save();
            }
        });

        User::created(function ($user){
            retry(5, function () use ($user) {
                Mail::to($user)->send(new UserCreatedMail($user));
            }, 100);
        });

        User::updated(function ($user){
            if ($user->isDirty('email')) {
                retry(5, function () use ($user) {
                    Mail::to($user)->send(new UserUpdatedMail($user));
                }, 100);
            }
        });
    }
}
