<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = Currency::create([
            'slug' => 'mmk',
            'name' => 'MMK'
        ]);

        $currency->exchangerates()->create([
            'rate' => 1,
            'mmk' => 1,
            'user_id' => 1,
        ]);
    }
}
