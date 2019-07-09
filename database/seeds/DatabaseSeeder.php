<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        DB::table('category_product')->truncate();
        Transaction::truncate();
        Product::truncate();
        Category::truncate();
        User::truncate();

        User::flushEventListeners();
        Product::flushEventListeners();
        Category::flushEventListeners();
        Transaction::flushEventListeners();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
